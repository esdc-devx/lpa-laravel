<template>
  <div class="form-section-group">
    <h2>
      {{ trans('forms.business_case.tabs.departmental_benefit') }}
      <div class="header-controls">
        <el-button type="text" size="mini" @click="expandAll = true">Expand All</el-button>
        <el-button type="text" size="mini" @click="expandAll = false">Collapse All</el-button>
      </div>
    </h2>
    <el-collapse :value="activePanels">
      <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="index">
        <template slot="title">
          {{ trans('forms.business_case.tabs.departmental_benefit') }} {{ index + 1 }}
          <el-button v-if="groups.length > 1" class="remove-group" type="danger" icon="el-icon-delete" size="mini" @click.stop="removeGroup(index, item)"></el-button>
        </template>
        <component ref="component" :is="entity" :data="data" class="form-item-group" :index="index" :value.sync="item" :isLoading="isLoading"></component>
      </el-collapse-item>
    </el-collapse>
    <el-button class="add-group" type="primary" icon="el-icon-plus" @click="addGroup()"></el-button>
  </div>
</template>

<script>
  import DepartmentalBenefit from './entities/departmental-benefit';

  export default {
    name: 'form-section-group',

    components: { DepartmentalBenefit },

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
      }
    },

    data() {
      return {
        expandAll: true,
        groups: this.value
      };
    },

    methods: {
      removeGroup(index, item) {
        this.groups.splice(index, 1);
      },

      addGroup() {
        // add another group, later based on the defaults
        this.groups.push({});
        this.$nextTick(() => {
          let groupsLength = this.groups.length - 1;
          let defaults = this.$refs.component[groupsLength].defaults;
          if (_.isUndefined(defaults)) {
            this.$log.error(`Entity '${this.entity}' should have a 'defaults' attribute in its data.`);
            return;
          }
          for (const key in defaults) {
            this.$set(this.groups[groupsLength], key, defaults[key]);
          }
        })
      }
    },
    created() {
      // if min is set and there is no groups, addGroup
      if (!_.isUndefined(this.$props.min) && !this.groups.length) {
        this.addGroup();
      }
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
        // margin-right: 10px;
        height: 100%;
        i {
          // margin-left: 5px;
        }
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
