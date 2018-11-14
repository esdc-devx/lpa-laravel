<template>
  <div class="learning-product-create content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-learning-products"></i>{{ trans('pages.learning_product_create.title') }}</h2>
      </span>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">
        <el-form-item-wrap
          :label="trans('entities.general.name')"
          prop="name"
          required>
          <input-wrap
            id="name"
            name="name"
            :data-vv-as="trans('entities.general.name')"
            v-model="form.name"
            maxlength="175"
            v-validate="'required'"
            auto-complete="off">
          </input-wrap>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit_id"
          required>
          <el-select-wrap
            v-model="form.organizational_unit_id"
            name="organizational_unit_id"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.parent_project')"
          prop="project_id"
          required>
          <el-select-wrap
            v-model="form.project_id"
            filterable
            :clearable=true
            name="project_id"
            :data-vv-as="trans('entities.learning_product.parent_project')"
            v-validate="'required'"
            :options="projects"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.type')"
          prop="type_id"
          required>
          <el-cascader
            v-model="learningProductType"
            name="type_id"
            :disabled="!projectSelected"
            :clearable=true
            element-loading-spinner="el-icon-loading"
            v-validate="'required'"
            :data-vv-as="trans('entities.learning_product.type')"
            :options="learningProductTypeList"
            :props="learningProductTypeOptions">
          </el-cascader>
          <form-error name="type_id"></form-error>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.manager')"
          prop="manager"
          required>
          <user-search
            name="manager"
            :label="trans('entities.learning_product.manager')"
            v-bind:user.sync="form.manager">
          </user-search>
          <form-error name="manager"></form-error>
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="trans('entities.learning_product.primary_contact')"
          prop="primary_contact"
          required>
          <user-search
            name="primary_contact"
            :label="trans('entities.learning_product.primary_contact')"
            v-bind:user.sync="form.primary_contact">
          </user-search>
          <form-error name="primary_contact"></form-error>
        </el-form-item-wrap>

        <el-form-item class="form-footer">
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ trans('base.actions.cancel') }}</el-button>
          <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.create') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import FormError from '@components/forms/error.vue';
  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import UserSearch from '@components/forms/user-search';
  import InputWrap from '@components/forms/input-wrap';

  import FormUtils from '@mixins/form/utils.js';

  import ListsAPI from '@api/lists';

  let namespace = 'learningProducts';

  const loadData = async function () {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`${namespace}/loadLearningProductCreateInfo`)
    );

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'learning-product-create',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError, UserSearch },

    watch: {
      learningProductType: function (value) {
        // Update learning product type and sub type id when interacting with cascader control.
        this.form.type_id = value[0];
        this.form.sub_type_id = value[1];
      },

      'form.project_id': function (projectId) {
        if (!projectId) {
          this.projectSelected = false;
          this.learningProductType = [];
          return;
        }

        this.projectSelected = true;

        let project = _.find(this.innerProjects, { id: projectId });
        let availableProductTypes = _.cloneDeep(project.available_learning_product_types);

        // If a learning product type was entered beforehand, clear its value.
        if (this.learningProductType.length !== 0) {
          this.learningProductType = [];
        }

        // Update learning product type list when selecting a project
        // to only enable the available learning product types.
        _.forEach(this.learningProductTypeList, productType => {
          _.forEach(productType.children, productSubType => {
            productSubType.disabled = true;
            _.forEach(availableProductTypes, (availableProductType, index) => {
              if (availableProductType && availableProductType.sub_type_id === productSubType.id) {
                productSubType.disabled = false;
                availableProductTypes.splice(index, 1);
                return false;
              }
            });
          });
        });
      }
    },

    computed: {
      ...mapState(`${namespace}`, [
        'projects'
      ]),
      ...mapGetters({
        viewingLearningProduct: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`
      })
    },

    data() {
      return {
        form: {
          project_id: null,
          name: '',
          organizational_unit_id: null,
          type_id: null,
          sub_type_id: null,
          manager: {},
          primary_contact: {}
        },
        innerProjects: [],
        learningProductType: [],
        learningProductTypeList: [],
        learningProductTypeOptions: {
          label: 'name',
          value: 'id'
        },
        projectSelected: false
      }
    },

    methods: {
      ...mapActions({
        createLearningProduct: `${namespace}/create`
      }),

      // Form handlers
      async onSubmit() {
        this.submit(this.create);
      },

      async create() {
        // Update form data before submission to only submit usernames.
        let formData = Object.assign({}, this.form);
        formData.manager = this.form.manager.username;
        formData.primary_contact = this.form.primary_contact.username;
        await this.createLearningProduct(formData);

        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.learning_product_created')
        });

        this.$router.push(`/${this.language}/learning-products/${this.viewingLearningProduct.id}`);
      },

      // Remove children attribute when empty and disable all terms by default.
      formatLearningProductList(list) {
        _.forEach(list, item => {
          _.forEach(item.children, itemSub => {
            itemSub.disabled = true;
            if (itemSub.children && itemSub.children.length === 0) {
              delete itemSub.children;
            }
          });
        });
        return list;
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await loadData();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('learningProducts/loadCanCreate', to.params.learningProductId);
      if (store.state.learningProducts.permissions.canCreate) {
        await loadData();
        next(async (vm) => {
          vm.learningProductTypeList = vm.formatLearningProductList(await ListsAPI.getList('learning-product-type'));
          vm.innerProjects = _.cloneDeep(vm.projects);
          // Add LPA number to each project name.
          _.forEach(vm.innerProjects, project => {
            project.name = vm.$options.filters.LPANumFilter(project.id) + ' - ' + project.name;
          });
        });
      } else {
        router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.$store.dispatch('learningProducts/loadCanCreate', to.params.learningProductId);
      if (this.$store.state.learningProducts.permissions.canCreate) {
        await this.onLanguageUpdate();
        this.learningProductTypeList = this.formatLearningProductList(await ListsAPI.getList('learning-product-type'));
        this.innerProjects = _.cloneDeep(this.projects);
        // Add LPA number to each project name.
        _.forEach(this.innerProjects, project => {
          project.name = `${this.$options.filters.LPANumFilter(project.id)} - ${project.name}`;
        });
        next();
      } else {
        this.$router.replace({ name: 'forbidden', params: { '0': to.path } });
      }
    },

    mounted() {
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-create {
    margin: 0 auto;
    h2 {
      position: relative;
      margin: 0;
      padding-left: 34px;
      display: inline-block;
      i {
        @include svg(learning-product, $--color-primary);
        width: 24px;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
      }
    }

    .el-form {
      .el-form-item:last-of-type {
        float: right;
      }
      label {
        padding: 0;
        font-weight: bold;
        color: $--color-primary;
      }
      .el-select {
        width: 100%;
      }
    }
    .el-cascader {
      width: 35%;
    }
  }
</style>
