<template>
  <div class="user-search">
    <el-autocomplete
      name="manager"
      :data-vv-as="label"
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
  </div>
</template>

<script>

import FormError from './error.vue';

export default {
  name: 'el-user-search',

  inheritAttrs: false,

  // Gives us the ability to inject validation in child components
  // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
  inject: ['$validator'],

  components: { FormError },

  props: {
    // @todo: remove when Element UI will add an options attribute to the select

    name: {
      type: String,
      required: true
    },
    isLoading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    value: {
      type: String,
      default: ''
    },
    label:{
      type: String,
      default: '[Unknown]'
    }
  },

  methods: {
    updateValue(value) {
      this.$emit('input', value);
    }
  }
}
</script>

<style>
</style>


-----

<script>

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
    }

</script>

<style lang="scss">
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