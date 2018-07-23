<template>
  <div class="form-section-group">
    <h2>
      {{ trans('forms.business_case.tabs["'+ entity.replace('-', '_') +'"]') }}
      <div class="header-controls">
        <el-button type="text" size="mini" @click="expandAll = true">Expand All</el-button>
        <el-button type="text" size="mini" @click="expandAll = false">Collapse All</el-button>
      </div>
    </h2>
    <el-collapse :value="activePanels">
      <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="index">
        <template slot="title">
          {{ trans('forms.business_case.tabs["'+ entity.replace('-', '_') +'"]') }} {{ index + 1 }}
          <el-button v-if="groups.length > 1" class="remove-group" type="danger" icon="el-icon-delete" size="mini" @click.stop="removeGroup(index, item)"></el-button>
        </template>
        <component ref="component" :is="entity" :data="data" class="form-item-group" :index="index" :value="item" :isLoading="isLoading"></component>
      </el-collapse-item>
    </el-collapse>
    <el-button class="add-group" type="primary" icon="el-icon-plus" @click="addGroup()"></el-button>
  </div>
</template>

<script>
  import EventBus from '@/event-bus.js';

  import DepartmentalBenefit from './entities/departmental-benefit';
  import LearnersBenefit from './entities/learners-benefit';

  export default {
    name: 'form-section-group',

    components: { DepartmentalBenefit, LearnersBenefit },

    props: {
      entity: {
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
      /**
       * This function makes sure that when we have for example: { prop, prop_id, ...}
       * that we assign the value of prop to prop_id and remove prop
       * so that we don't send it to the server.
       * Make sure to also deep set the values so that they are all reactives.
      */
      formatGroups() {
        let that = this;
        _.forEach(this.groups, (group, key) => {
          _.forIn(group, (value, subKey) => {
            if (group.hasOwnProperty(subKey + '_id')) {
              that.$set(group, subKey + '_id', _.get(value, 'id'));
              delete group[subKey];
            }
          });
        });
        this.$emit('update:value', this.groups);
      },

      removeGroup(index, item) {
        this.groups.splice(index, 1);
        EventBus.$emit('FormUtils:fieldsAddedOrRemoved');
      },

      addGroup() {
        // add another group, later based on the defaults
        this.$nextTick(() => {
          let groupsLength = this.groups.length - 1;
          let defaults = this.$refs.component[groupsLength].defaults;
          if (_.isUndefined(defaults)) {
            this.$log.error(`Entity '${this.entity}' should have a 'defaults' attribute in its data.`);
            return;
          }
          this.groups.push(defaults);
          // for (const key in defaults) {
          //   this.$set(this.groups[groupsLength], key, defaults[key]);
          // }
        });
        EventBus.$emit('FormUtils:fieldsAddedOrRemoved');
      }
    },

    beforeDestroy() {
      EventBus.$off('FormEntity:formDataUpdate', this.formatGroups);
    },

    created() {
      this.formatGroups();
      // if min is set and there is no groups, addGroup
      if (!_.isUndefined(this.$props.min) && !this.groups.length) {
        this.addGroup();
      }
    },

    mounted() {
      EventBus.$on('FormEntity:formDataUpdate', this.formatGroups);
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
