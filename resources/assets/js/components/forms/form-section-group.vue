<template>
  <div class="form-section-group">
    <h4>
      {{ $tc(`forms.${entityFormKey}.form_section_groups.${entitySectionKey}`, 2) }}
      <div class="header-controls">
        <button :disabled="!groups.length" @click.prevent="expandAll()">
          {{ trans('base.actions.expand_all') }}
        </button>
        <button :disabled="!groups.length" @click.prevent="collapseAll()">
          {{ trans('base.actions.collapse_all') }}
        </button>
      </div>
    </h4>
    <el-collapse :value="activePanels" @change="onActivePanelsChange">
      <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="index">
        <template slot="title">
          {{ $tc(`forms.${entityFormKey}.form_section_groups.${entitySectionKey}`) }} {{ index + 1 }}
          <!--
            only show the delete button:
            if the min value is not specified (0) always show it
            else show it only when there are more groups than the min value
          -->
          <el-button
            v-if="min ? groups.length > min : true"
            class="remove-group"
            type="danger"
            icon="el-icon-lpa-delete"
            size="mini"
            @click.stop="removeGroup(index, item)"
            plain
          />
        </template>
        <component
          ref="component"
          :is="entitySection"
          :data="data"
          class="form-item-group"
          :index="index"
          :value="item"
        />
      </el-collapse-item>
    </el-collapse>
    <el-button class="add-group" type="primary" icon="el-icon-plus" @click="addGroup()"></el-button>
  </div>
</template>

<script>
  import { mapMutations } from 'vuex';

  // Form sections
  import Spending from './entities/spending';
  import Risk from './entities/risk';
  import PlannedProduct from './entities/planned-product';
  import ApplicablePolicy from './entities/applicable-policy';
  import AdditionalCost from './entities/additional-cost';
  import Instructor from './entities/instructor';
  import GuestSpeaker from './entities/guest-speaker';
  import Room from './entities/room';
  import Material from './entities/material';
  import Document from './entities/document';
  import Region from './entities/region';

  export default {
    name: 'form-section-group',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: {
      Spending,
      Risk,
      PlannedProduct,
      ApplicablePolicy,
      AdditionalCost,
      Instructor,
      GuestSpeaker,
      Room,
      Material,
      Document,
      Region
    },

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
        type: Object
      },
      min: {
        type: Number,
        default: 0
      },
      value: {
        type: Array
      }
    },

    data() {
      return {
        activePanels: [ 1 ]
      };
    },

    computed: {
      entityFormKey() {
        return _.snakeCase(this.entityForm);
      },

      entitySectionKey() {
        return _.snakeCase(this.entitySection);
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

    watch: {
      '$store.state.language'(to, from) {
        this.prepareGroups();
      },
      value() {
        this.prepareGroups();
      }
    },

    methods: {
      ...mapMutations('entities/forms', [
        'setHasFormSectionGroupsRemoved'
      ]),

      onActivePanelsChange(activeNames) {
        this.activePanels = activeNames;
      },

      expandAll() {
        this.activePanels = _.range(1, this.groups.length + 1);
      },

      collapseAll() {
        this.activePanels = [];
      },

      removeGroup(index, item) {
        this.groups.splice(index, 1);
        this.setHasFormSectionGroupsRemoved(true);
      },

      addGroup(isPreparing = false) {
        // push a new group now so that the component can render it
        // and we can access to its defaults
        this.groups.push({});
        this.$nextTick(() => {
          const groupsLength = this.groups.length - 1;
          const defaults = this.$refs.component[groupsLength].defaults;
          const fieldNamePrefix = this.$refs.component[groupsLength].fieldNamePrefix;
          if (_.isUndefined(defaults)) {
            this.$log.error(`Entity '${this.entitySection}' should have a 'defaults' attribute in its data.`);
            return;
          }
          for (const key in defaults) {
            this.$set(this.groups[groupsLength], key, defaults[key]);
            // put the newly added fields dirty when a group is added manually
            // so that vee-validate recognize that new fields were added
            this.$validator.flag(`${fieldNamePrefix}.${key}`, { dirty: !isPreparing });
          }
          this.activePanels.push(this.groups.length);
        });
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

    created() {
      this.prepareGroups();
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

    h4 {
      border-bottom: 1px solid;
      display: flex;
      .header-controls {
        display: flex;
        flex: 1;
        justify-content: flex-end;
        button {
          text-decoration: underline;
          background-color: transparent;
          border: none;
          outline: none;
          &:not(:disabled) {
            color: $--link-color;
            cursor: pointer;
            &:hover {
              color: $--link-hover-color;
            }
          }
          &:disabled {
            cursor: not-allowed;
          }
        }
      }
    }

    // make sure that the el-collapse following a h4
    // goes on top of the h4's margin-bottom
    h4 + .el-collapse {
      margin-top: -1.1em;
      border-top: 0;
      border-bottom: 0;
      // and remove its border-top
      .el-collapse {
        border-top: none;
      }
    }

    $tab-height: 34px;
    .el-collapse-item__header {
      padding-left: 10px;
      line-height: $tab-height;
      height: $tab-height;
      background-color: $--background-color-base;
      transition: $bg-color-transition-base;
      &:hover {
        background-color: mix($--color-primary, $--background-color-base, 5%);
      }
      i.el-collapse-item__arrow {
        line-height: $tab-height;
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
      margin: 20px 0;
    }
  }
</style>
