 <template>
  <el-form-item
    :label="innerLabel"
    v-if="innerLabel || $slots.label"
    :for="labelFor"
    :class="[classes, { 'is-required': required, 'is-error': verrors.collect(labelFor).length }]"
  >
    <slot name="label">
      <span slot="label">
        {{ innerLabel }}
        <span class="label-addons" v-if="innerDescription || innerHelp || innerInstruction">
          <el-popover-wrap
            v-if="innerDescription || innerHelp"
            :description="innerDescription"
            :help="innerHelp">
          </el-popover-wrap>
          <span v-if="innerInstruction" class="instruction" v-html="innerInstruction"></span>
        </span>
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

</style>
