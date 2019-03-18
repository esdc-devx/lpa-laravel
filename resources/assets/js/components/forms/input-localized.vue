<template>
  <div class="input-localized">
    <el-card v-if="toTranslate" class="to-translate" shadow="never">
      <i>{{ toTranslate }}</i>
    </el-card>
    <input-wrap
      v-model="inputEn"
      :data-vv-as="dataVvAsEn"
      v-validate="validate"
      :disabled="disabled"
      :name="nameEn"
      :maxlength="maxlength"
      :type="type"
    >
      <template slot="prepend">{{ trans('entities.form.en') }}</template>
    </input-wrap>
    <input-wrap
      v-model="inputFr"
      :data-vv-as="dataVvAsFr"
      v-validate="validate"
      :disabled="disabled"
      :name="nameFr"
      :maxlength="maxlength"
      :type="type"
    >
      <template slot="prepend">{{ trans('entities.form.fr') }}</template>
    </input-wrap>
  </div>
</template>

<script>
  import InputWrap from './input-wrap.vue';

  export default {
    name: 'input-localized',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { InputWrap },

    props: {
      modelEn: {
        type: String,
      },
      modelFr: {
        type: String,
      },
      nameEn: {
        type: String,
        required: true
      },
      nameFr: {
        type: String,
        required: true
      },
      maxlength: {
        type: String | Number
      },
      toTranslate: {
        type: String
      },
      disabled: {
        type: Boolean
      },
      validate: {
        type: String | Object,
        default: ''
      },
      dataVvAsEn: {
        type: String,
        default() {
          return this.nameEn;
        }
      },
      dataVvAsFr: {
        type: String,
        default() {
          return this.nameFr;
        }
      },
      type: {
        type: String,
        default: 'input',
        validator(val) {
          // The value must match one of these strings
          return ['input', 'textarea'].indexOf(val) !== -1
        }
      }
    },

    computed: {
      inputEn: {
        get() {
          return this.modelEn;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelEn', val);
        }
      },
      inputFr: {
        get() {
          return this.modelFr;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelFr', val);
        }
      },
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .input-localized {
    .to-translate {
      background-color: $--background-color-base;
      margin-bottom: 20px;
      .el-card__body {
        padding: 10px 20px;
        line-height: 1.6;
      }
    }
    .el-input-group__prepend {
      width: 40px !important;
    }
  }
</style>
