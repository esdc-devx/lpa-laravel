<template>
  <div class="learning-product-create content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-learning-products"></i>{{ trans('pages.learning_product_create.title') }}</h2>
      </span>

      <el-form ref="form" :model="form" label-position="top" @submit.native.prevent :disabled="isFormDisabled">

        <el-form-item-wrap
          :label="trans('entities.learning_product.parent_project')"
          prop="parent_project"
          required>
          <el-select-wrap
            v-model="form.parent_project"
            filterable
            name="parent_project"
            :data-vv-as="trans('entities.learning_product.parent_project')"
            v-validate="'required'"
            :options="projects"
          />
        </el-form-item-wrap>

        <el-form-item-wrap
          :label="$tc('entities.general.organizational_units')"
          prop="organizational_unit"
          required>
          <el-select-wrap
            v-model="form.organizational_unit"
            name="organizational_unit"
            :data-vv-as="$tc('entities.general.organizational_units')"
            v-validate="'required'"
            :options="organizationalUnits"
          />
        </el-form-item-wrap>

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

        <!-- <el-form-item-wrap
          :label="trans('entities.learning_product.type')"
          prop="type_id"
          required>
          <el-cascader
            v-model="typeModel"
            name="type_id"
            element-loading-spinner="el-icon-loading"
            v-validate="'required'"
            :data-vv-as="trans('entities.learning_product.type')"
            :options="typeList"
            :props="typeOptions">
          </el-cascader>
          <form-error :name="type_id"></form-error>
        </el-form-item-wrap> -->

        <el-form-item-wrap 
          :label="trans('entities.learning_product.manager')" 
          prop="manager" 
          required>
          <el-autocomplete
            name="manager"
            :data-vv-as="trans('entities.learning_product.manager')"
            popper-class="name-autocomplete"
            v-validate="nameRules"
            v-model="form.manager"
            :fetch-suggestions="querySearchAsync"
            :trigger-on-focus="false"
            valueKey="name"
            @select="handleSelect">
            <template slot-scope="props">
              <div class="autocomplete-popper-inner-wrap" :title="props.item.name">
                <div class="value">{{ props.item.name }}</div>
                <span class="link">{{ props.item.email }}</span>
              </div>
            </template>
          </el-autocomplete>
          <form-error name="manager"></form-error>
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
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';
  import FormError from '@components/forms/error.vue';
  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';
  import InputWrap from '@components/forms/input-wrap';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-create',

    inject: ['$validator'],

    mixins: [ FormUtils, PageUtils ],

    components: { ElFormItemWrap, ElSelectWrap, InputWrap, FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        viewingLearningProduct: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`
      }),

      // typeModel: {
      //   get() {
      //     // make sure to not return an array of nulls here
      //     // since vee-validate would think that it is a valide value
      //     if (!_.isNumber(this.form.type_id) && !_.isNumber(this.form.sub_type_id)) {
      //       return null;
      //     }
      //     return [this.form.type_id, this.form.sub_type_id];
      //   },
      //   set(value) {
      //     let typeId = value[0];
      //     let subTypeId = value[1];

      //     [this.form.type_id, this.form.sub_type_id] = [typeId, subTypeId];
      //   }
      // },

      // typeList: {
      //   get() {
      //     return this.projects ? this.projects[0].available_learning_product_types : [];
      //   }
      // },

      nameRules() {
        return {
          required: true,
          in: this.inUserList
        }
      }
    },

    data() {
      return {
        form: {
          parent_project: null,
          name: '',
          organizational_unit: null,
          type_id: null,
          sub_type_id: null,
          primary_contact: null,
          manager: null
        },
        projects: [],
        // typeOptions: {
        //   label: 'name',
        //   value: 'id'
        // },
        inUserList: []
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        createLearningProduct: `${namespace}/create`,
        loadLearningProductCreateInfo: `${namespace}/loadLearningProductCreateInfo`,
        searchUser: `users/search`,
      }),

      search(name) {
        return this.searchUser(name);
      },

      async querySearchAsync(queryString, callback) {
        let users;
        try {
          users = await this.search(queryString);
          this.inUserList = _.map(users, 'name');
          callback(users);
        } catch (e) {
          this.notifyError({
            message: 'Unable to retrieve users.'
          });
          this.$log.error(`[learning-product-create][querySearchAsync] ${e}`);
        }
      },

      handleSelect(item) {
        this.form.manager = item.username;
      },

      // Form handlers
      async onSubmit() {
        this.submit(this.create);
      },

      async create() {
        // @note: no try-catch required here
        // since we already do it in the form utils
        await this.createLearningProduct(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.created', { name: this.form.name })
        });
        this.$router.push(`/${this.language}/learning-products/${this.viewingLearningProduct.id}`);
      },
      async fetch() {
        await this.showMainLoading();
        let response = await this.loadLearningProductCreateInfo();
        this.projects = response.projects;
        await this.hideMainLoading();
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await this.fetch();
      }
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    async mounted() {
      EventBus.$emit('App:ready');
      // @note: hide the loading that was shown
      // in the router's beforeEnter
      this.$nextTick(async () => {
        await this.hideMainLoading();
      });
      await this.fetch();
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
  }

  .el-autocomplete-suggestion.name-autocomplete {
    li {
      line-height: 20px;
      padding: 7px 10px;

      .autocomplete-popper-inner-wrap {
        overflow: hidden;
        .value {
          text-overflow: ellipsis;
          overflow: hidden;
        }
        .link {
          font-size: 12px;
          color: #b4b4b4;
        }
      }
    }
  }
</style>
