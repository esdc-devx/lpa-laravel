<template>
  <div class="learning-product-search">
    <el-select-wrap
      v-bind="$attrs"
      :value.sync="model"
      v-validate="validate"
      :data-vv-as="dataVvAs"
      optionLabel="code"
      default-first-option
      filterable
      remote
      :remote-method="querySearchAsync"
      @input="updateValue($event)"
      :options="options"
    >
    </el-select-wrap>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex';

  import ElSelectWrap from './el-select-wrap';

  export default {
    name: 'learning-product-search',

    components: { ElSelectWrap },

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      model: {
        type: Number | Array,
        required: true
      },
      validate: {
        type: Object | String,
        default: ''
      },
      dataVvAs: {
        type: String,
        default: ''
      },
      codes: {
        type: Array,
        required: true
      }
    },

    data() {
      return {
        options: []
      };
    },

    watch: {
      codes() {
        // filter through the codes and return the ones
        // that corresponds to the ids received
        if (!this.model) {
          this.options = [];
        } else {
          this.options = _(this.codes).keyBy('id').at(this.model).value();
        }
      }
    },

    methods: {
      ...mapActions('entities/learningProducts', [
        'search'
      ]),

      async querySearchAsync(queryString) {
        if (!_.isEmpty(queryString) && /[a-zA-Z0-9\-]/.test(queryString)) {
          const response = await this.search(queryString);
          this.options = response.data;
        } else {
          this.options = [];
        }
      },

      updateValue(value) {
        this.$emit('update:model', value);
      }
    }
  };
</script>

<style lang="scss">

</style>
