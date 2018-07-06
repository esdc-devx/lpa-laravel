<template>
  <div class="project-process-form content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.form.status') }}</dt>
            <dd>{{ processInstanceForm.state.name }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.form.current_editor') }}</dt>
            <dd>{{ processInstanceForm.current_editor ? processInstanceForm.current_editor.name : trans('entities.general.na') }}</dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.general.organizational_units') }}</dt>
            <dd>Org Unit</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd>{{ processInstanceForm.updated_by ? processInstanceForm.updated_by.name : trans('entities.general.na') }}</dd>
            <dd>{{ processInstanceForm.updated_at }}</dd>
          </dl>
        </info-box>
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <div class="form-header">
            <el-header>{{ trans('entities.form.form_index') }}</el-header>
            <el-header class="form-header-details"><i class="el-icon-lpa-form"></i>{{ viewingForm.process_instance_form.definition.name }}</el-header>
          </div>
          <el-main>
            <el-form label-position="top" @submit.native.prevent>
              <component
                :is="formComponent"
                ref="tabs"
                type="border-card"
                tabPosition="left"
                :value.sync="activeIndex">
              </component>
            </el-form>
          </el-main>
          <div class="form-footer">
            <el-footer height="50px"></el-footer>
            <el-footer height="50px">
              <div class="form-footer-nav">
                <el-button
                  size="mini"
                  icon="el-icon-arrow-left"
                  :disabled="isPrevDisabled"
                  @click="prevTabIndex()">
                    {{ trans('base.pagination.previous') }}
                </el-button>
                <el-button
                  size="mini"
                  :disabled="isNextDisabled"
                  @click="nextTabIndex()">
                    {{ trans('base.pagination.next') }}<i class="el-icon-arrow-right el-icon-right"></i>
                </el-button>
              </div>
              <div class="form-footer-actions">
                <!-- @todo: lpa-5280 -->
                <!-- <el-button size="mini">Cancel</el-button>
                <el-button size="mini">Save</el-button>
                <el-button size="mini">Submit</el-button> -->
              </div>
            </el-footer>
          </div>
        </el-container>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

  import HttpStatusCodes from '../../axios/http-status-codes';

  import InfoBox from '../../components/info-box.vue';

  import BusinessCase from '../../components/forms/entities/business-case';

  let namespace = 'projects';

  export default {
    name: 'project-process-form',

    components: { InfoBox, BusinessCase },

    data() {
      return {
        activeIndex: 0,
        tabsLength: 0,
        currentFormComponent: ''
      }
    },

    computed: {
      ...mapGetters({
        language: 'language',
        viewingProcess: 'processes/viewing',
        viewingForm: 'processes/viewingForm'
      }),

      formComponent() {
        return this.currentFormComponent;
      },

      isPrevDisabled() {
        return parseInt(this.activeIndex, 10) === 0;
      },
      isNextDisabled() {
        return parseInt(this.activeIndex, 10) === this.tabsLength;
      },

      processInstanceForm() {
        return this.viewingForm.process_instance_form;
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: 'projects/loadProject',
        loadProcessInstance: 'processes/loadInstance',
        loadProcessInstanceForm: 'processes/loadInstanceForm'
      }),

      prevTabIndex() {
        let index = parseInt(this.activeIndex, 10);
        index = index !== '0' ? --index : index;
        // cast to string since el-tabs value prop requires a string to work
        this.activeIndex = `${index}`;
      },

      nextTabIndex() {
        let index = parseInt(this.activeIndex, 10);
        index = index !== this.tabsLength ? ++index : index;
        // cast to string since el-tabs value prop requires a string to work
        this.activeIndex = `${index}`;
      },

      setupStage() {
        this.$nextTick(() => {
          // we need to wait until the dom is ready
          // so that we have access to the tabs panes
          if (this.$refs.tabs.$children[0].panes) {
            // tabsLength needs to be zero-based
            this.tabsLength = this.$refs.tabs.$children[0].panes.length - 1;
          }
        });
      },

      async triggerLoadProject() {
        let projectId = this.$route.params.projectId;
        await this.loadProject(projectId);
      },

      async triggerLoadProcessInstance() {
        let processId = this.$route.params.processId;
        await this.loadProcessInstance(processId);
      },

      async triggerLoadProcessInstanceForm() {
        let formId = this.$route.params.formId;
        await this.loadProcessInstanceForm(formId);

      },

      async fetch() {
        try {
          this.showMainLoading();
          await this.triggerLoadProject();
          await this.triggerLoadProcessInstance();
          await this.triggerLoadProcessInstanceForm();
          // @todo: replace component by dynamic name_key
          this.currentFormComponent = 'business-case';
          this.setupStage();
          this.hideMainLoading();
        } catch(e) {
          this.$router.replace(`/${this.language}/${HttpStatusCodes.NOT_FOUND}`);
        }
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.fetch);
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.fetch);
      this.fetch();
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  @import '../../../sass/abstracts/mixins/helpers';
  @import '../../../sass/base/helpers';

  .project-process-form {
    height: calc(100% - 20px);
    display: flex;
    flex-direction: column;

    .el-tabs {
      position: absolute;
      // fixes the height of the tab list when there are less tabs than content
      display: flex;
      width: 100%;
      height: 100%;
      .el-tabs__content {
        overflow: auto;
        flex: 1;
      }
      .el-tabs__item.is-left {
        padding-left: 30px;
        text-align: left;
        &:hover span:after {
          background-color: $--color-primary-light-4;
        }
        &.is-active span:after {
          background-color: $--color-primary;
        }
        span:after {
          content: '';
          position: absolute;
          left: 10px;
          top: 50%;
          transition: $--all-transition;
          transform: translateY(-50%);
          width: 12px;
          height: 12px;
          background-color: #ccc;
          border-radius: 50%;
          display: inline-block;
        }
        span.is-error {
          color: $--color-danger;
          &:after {
            background-color: $--color-danger;
          }
        }
      }
    }

    header, footer {
      background: #737680;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-row {
      flex: 1;
      .el-col, .form-wrap {
        height: 100%;
      }
    }
    .form-wrap {
      header, footer {
        padding: 0 30px;
        &:nth-child(2) {
          flex: 1;
        }
      }
      header {
        height: 40px !important;
        box-shadow: $box-shadow-base-bottom;
        &.form-header-details {
          justify-content: flex-start;
          i {
            margin-right: 5px;
            @include svg(form, $--color-white);
          }
        }
      }
      footer {
        box-shadow: $box-shadow-base-top;
      }
      .el-main {
        overflow: hidden;
        background-color: $--color-white;
        // make sure that when the content is loading, that we display a fake form index so that layout is consistant
        &:before {
          content: '';
          position: absolute;
          left: 0;
          top: 0;
          height: 100%;
          background-color: $--background-color-base;
          border: 1px solid $--border-color-base;
        }
        .has-other .el-form-item__content {
          display: flex;
          align-items: flex-start;
          // make sure childrens are spaced
          > * {
            // make the last child take all the remaining space
            &:last-child {
              flex: 1;
            }
          }
        }
      }
      .form-header, .form-footer {
        z-index: $--index-top;
        display: flex;
      }
      .form-footer {
        margin-top: 0;
      }
      .form-header header:first-of-type, .form-footer footer:first-of-type {
        padding: 0 30px;
        border-right: 1px solid;
      }
      .form-header header:first-of-type, .form-footer footer:first-of-type,
      .el-tabs__header,
      .el-main:before {
        width: 20%;
        min-width: 250px;
        box-sizing: border-box;
      }

      .form-footer {
        &-nav {
          flex: 1;
          text-align: center;
          button {
            // This fixes a bug in IE11 when the pseudo :after is overflowing the button and is not visible
            overflow: visible;
            &:first-child {
              margin-right: 20px;
              position: relative;
              &:after {
                content: '';
                position: absolute;
                top: 0;
                // -12 = 20px margin + 2px border
                right: -22px;
                width: 2px;
                height: 100%;
                background-color: $--color-white;
                // make sure the button is not clickable through the pseudo element
                pointer-events: none;
              }
            }
            &:last-child {
              margin-left: 20px;
            }
          }
        }
      }
    }
  }
</style>
