<template>
  <div class="form-section-group">
    <h2>
      {{ $tc(`forms.${entityFormKey}.tabs.${entitySectionKey}`, 2) }}
      <div class="header-controls">
        <el-button type="text" size="mini" @click="expandAll = true">
          {{ trans('entities.general.expand_all') }}
        </el-button>
        <el-button type="text" size="mini" @click="expandAll = false">
          {{ trans('entities.general.collapse_all') }}
        </el-button>
      </div>
    </h2>
    <el-collapse :value="activePanels">
      <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="index">
        <template slot="title">
          {{ $tc(`forms.${entityFormKey}.tabs.${entitySectionKey}`) }} {{ index + 1 }}
          <!--
            only show the delete button:
            if the min value is not specified (0) always show it
            else show it only when there are more groups than the min value
          -->
          <el-button v-if="min ? groups.length > min : true" class="remove-group" type="danger" icon="el-icon-delete" size="mini" @click.stop="removeGroup(index, item)"></el-button>
        </template>
        <component ref="component" :is="entitySection" :data="data" class="form-item-group" :index="index" :value="item" :isLoading="isLoading"></component>
      </el-collapse-item>
    </el-collapse>
    <el-button class="add-group" type="primary" icon="el-icon-plus" @click="addGroup()"></el-button>
  </div>
</template>

<script>
  import EventBus from '@/event-bus.js';

  // Form sections
  import DepartmentalBenefit from './entities/departmental-benefit';
  import LearnersBenefit from './entities/learners-benefit';
  import Risk from './entities/risk';
  import PlannedProduct from './entities/planned-product';

  export default {
    name: 'form-section-group',

    components: { DepartmentalBenefit, LearnersBenefit, Risk, PlannedProduct },

    props: {
      entityForm: {
        type: String,
        required: true
      },
      entitySection: {
        type: String,
        required: true
      },
      data: {
        type: Object,
        required: true
      },
      min: {
        type: Number,
        default: 0
      },
      isLoading: {
        type: Boolean,
        required: true
      },
      value: Array
    },

    computed: {
      entityFormKey() {
        return this.entityForm.replace('-', '_');
      },
      entitySectionKey() {
        return this.entitySection.replace('-', '_');
      },

      activePanels() {
        let panels = [];
        let length = this.expandAll ? this.groups.length : 0;
        for (let i = 0; i < length; i++) {
          panels.push(i + 1);
        }
        return panels;
      },

      groups: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    data() {
      return {
        expandAll: true
      };
    },

    methods: {
      removeGroup(index, item) {
        this.groups.splice(index, 1);
        EventBus.$emit('FormUtils:fieldsAddedOrRemoved');
      },

      addGroup(isPreparing) {
        // push a new group now so that the component can render it
        // and we can access to its defaults
        this.groups.push({});
        this.$nextTick(() => {
          let groupsLength = this.groups.length - 1;
          let defaults = this.$refs.component[groupsLength].defaults;
          if (_.isUndefined(defaults)) {
            this.$log.error(`Entity '${this.entitySection}' should have a 'defaults' attribute in its data.`);
            return;
          }
          for (const key in defaults) {
            this.$set(this.groups[groupsLength], key, defaults[key]);
          }
        });
        if (!isPreparing) {
          EventBus.$emit('FormUtils:fieldsAddedOrRemoved');
        }
      },

      prepareGroups() {
        if (!_.isUndefined(this.min) && !this.groups.length) {
          // add the ammount of groups provided
          for (let i = 0; i < this.min; i++) {
            this.addGroup(true);
          }
        }
      }
    },

    beforeDestroy() {
      EventBus.$off('FormEntity:discardChanges', this.prepareGroups);
    },

    created() {
      this.prepareGroups();
    },

    mounted() {
      EventBus.$on('FormEntity:discardChanges', this.prepareGroups);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .form-section-group {
    .el-collapse {
      .el-collapse-item__arrow {
        float: left;
      }
      .el-button.remove-group {
        float: right;
        border-radius: 0;
      }
    }

    h2 {
      display: flex;
      .header-controls {
        display: flex;
        flex: 1;
        justify-content: flex-end;
        button {
          text-decoration: underline;
        }
      }
    }

    // make sure that the el-collapse following a h2
    // goes on top of the h2's margin-bottom
    h2 + .el-collapse {
      margin-top: -1.1em;
      // and remove its border-top
      .el-collapse {
        border-top: none;
      }
    }

    .el-collapse-item__header {
      padding-left: 10px;
      line-height: 30px;
      height: 30px;
      background-color: $--background-color-base;
      transition: $bg-color-transition-base;
      &:hover {
        background-color: mix($--color-primary, $--background-color-base, 5%);
      }
      i.el-collapse-item__arrow {
        line-height: 30px;
      }
      button.remove-group {
        height: 100%;
      }
    }

    .form-item-group {
      margin-top: 20px;
      margin-left: 20px;
    }

    button.add-group {
      margin-top: 20px;
    }
  }
</style>
