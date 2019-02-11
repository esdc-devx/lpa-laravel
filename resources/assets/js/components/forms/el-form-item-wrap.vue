 <template>
  <el-form-item
    :label="innerLabel"
    v-if="innerLabel || $slots.label"
    :for="labelFor"
    :class="[classes, { 'is-required': required, 'is-error': verrors.collect(labelFor).length }]"
  >
    <slot name="label">
      <span
        slot="label"
        :class="{ 'wrapper': innerDescription || innerHelp || innerInstruction }"
      >
        {{ innerLabel }}
        <el-popover-wrap
          v-if="innerDescription || innerHelp"
          :description="innerDescription"
          :help="innerHelp">
        </el-popover-wrap>
        <span v-if="innerInstruction" class="instruction" v-html="innerInstruction"></span>
      </span>
    </slot>
    <slot></slot>
  </el-form-item>
</template>

<script>
  import ElPopoverWrap from '../el-popover-wrap';

  export default {
    name: 'el-form-item-wrap',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      label: {
        type: String
      },
      contextPath: {
        type: String
      },
      isLabelPlural: {
        type: Boolean,
        default: false
      },
      description: {
        type: String
      },
      help: {
        type: String
      },
      instruction: {
        type: String
      },
      prop: {
        type: String
      },
      classes: {
        type: Array
      },
      required: {
        type: Boolean,
        default: false
      },
      for: {
        type: String
      }
    },

    components: { ElPopoverWrap },

    computed: {
      labelFor() {
        return this.for || this.prop
      },

      labelPluralCount() {
        return this.isLabelPlural ? 1 : 2;
      },

      innerLabel() {
        let string = this.getStringFromPath(`${this.contextPath}.label`);
        return this.label ?
               this.label : string ?
               this.$tc(`${this.contextPath}.label`, this.labelPluralCount) : '';
      },

      innerDescription() {
        let string = this.getStringFromPath(`${this.contextPath}.description`);
        return this.description ?
               this.description : string ?
               this.$tc(`${this.contextPath}.description`) : '';
      },

      innerHelp() {
        let string = this.getStringFromPath(`${this.contextPath}.help`);
        return this.help ?
               this.help : string ?
               this.$tc(`${this.contextPath}.help`) : '';
      },

      innerInstruction() {
        let string = this.getStringFromPath(`${this.contextPath}.instruction`);
        return this.instruction ?
               this.instruction : string ?
               this.$tc(`${this.contextPath}.instruction`) : '';
      }
    },

    methods: {
      getStringFromPath(path) {
        return _.get(window.i18n.messages[window.i18n.locale], path);
      }
    }
  }
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .el-form-item {
    // override ElementUI
    // this is to ensure that the char-count height is considerated
    margin-bottom: 28px;
    &__label {
      position: relative;
      // Fixes a too short width in IE11
      width: 100%;
      color: $--color-primary;
      &:before {
        display: inline-block;
        // make sure that the label is always aligned with its control
        // to do that, we move to the left any required *
        // by doing that, this shifts left the entire label
        // when there is a required * next to it.
        margin-left: -10px;
      }
      @at-root &, .el-form-item label {
        color: $--color-primary;
        font-weight: 500;
        line-height: 10px;
      }
      // this styles cases where we might want multiple info
      // inside the same label
      > span.wrapper {
        display: inline-block;
        vertical-align: text-top;
      }
      .instruction {
        display: block;
        font-style: italic;
        line-height: normal;
        color: $--color-text-regular;
      }
    }
    // ElementUI override so that we can have multiple errors shown as a list
    &__error {
      position: relative;
      top: 0;
      display: flex;
    }
  }
</style>
