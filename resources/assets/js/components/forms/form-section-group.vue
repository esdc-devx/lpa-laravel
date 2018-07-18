<template>
  <div class="form-section-group">
    <h2>
      {{ trans('forms.business_case.tabs.departemental_benefit') }}
      <div class="header-controls">
        <el-button type="text" size="mini" @click="expandAll = true">Expand All</el-button>
        <el-button type="text" size="mini" @click="expandAll = false">Collapse All</el-button>
      </div>
    </h2>
    <el-collapse :value="activePanels">
      <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="index">
        <template slot="title">
          {{ trans('forms.business_case.tabs.departemental_benefit') }} {{ index + 1 }}
          <el-button type="text" class="remove-group" @click.stop="removeGroup(index, item)">Remove Instructor<i class="el-icon-error"></i></el-button>
        </template>
        <component :is="entity" class="form-item-group"></component>
      </el-collapse-item>
    </el-collapse>
    <el-button class="add-group" @click="addGroup()">Add</el-button>
  </div>
</template>

<script>
  import DepartementalBenefit from './entities/departemental-benefit';

  export default {
    name: 'form-section-group',

    inheritAttrs: false,

    components: { DepartementalBenefit },

    props: {
      entity: String,
      data: {
        type: Array,
        required: true
      }
    },

    computed: {
      activePanels() {
        return this.expandAll ? _.map(this.groups, 'id') : [];
      }
    },

    data() {
      return {
        expandAll: false,
        groups: this.data
      };
    },

    methods: {
      removeGroup(index, item) {
        this.groups.splice(index, 1);
      },

      addGroup() {
        this.groups.push({
          id: null
        });
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
      i.el-collapse-item__arrow, button.remove-group {
        line-height: 30px;
      }
      button.remove-group {
        padding: 0;
        border: 0;
        margin-right: 10px;
        font-size: 100%;
        i {
          margin-left: 5px;
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
