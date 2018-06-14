<template>
  <div class="project-process-form content">
    <el-row>
      <el-col>
        <el-card shadow="never" class="info-box">
          <dl>
            <dt>{{ trans('entities.form.status') }}</dt>
            <dd>Status</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.form.current_editor') }}</dt>
            <dd>Editor</dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.general.organizational_units') }}</dt>
            <dd>Org Unit</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd>Name</dd>
            <dd>Date</dd>
          </dl>
        </el-card>
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <div class="form-header">
            <el-header>Form Index</el-header>
            <el-header class="form-header-details"><i class="el-icon-lpa-form"></i>Course Operational Details Form</el-header>
          </div>
          <el-main>
            <el-tabs ref="tabs" type="border-card" tabPosition="left" :value="'' + activeIndex" @tab-click="onTabClick">
              <el-tab-pane>
                <span slot="label"><span class="index-marker"></span>Business Drivers</span>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
                Route <br>
              </el-tab-pane>
              <el-tab-pane>
                <span slot="label"><span class="index-marker"></span>Proposal</span>
                Config
              </el-tab-pane>

            </el-tabs>
          </el-main>
          <div class="form-footer">
            <el-footer height="50px"></el-footer>
            <el-footer height="50px">
              <div class="form-footer-nav">
                <el-button
                  size="mini"
                  icon="el-icon-arrow-left"
                  :disabled="activeIndex === 0"
                  @click="activeIndex !== 0 ? --activeIndex : activeIndex">
                    Back
                </el-button>
                <el-button
                  size="mini"
                  :disabled="activeIndex === tabsLength"
                  @click="activeIndex !== tabsLength ? ++activeIndex : activeIndex">
                    Next<i class="el-icon-arrow-right el-icon-right"></i>
                </el-button>
              </div>
              <div class="form-footer-actions">
                <el-button size="mini">Cancel</el-button>
                <el-button size="mini">Save</el-button>
                <el-button size="mini">Submit</el-button>
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

  let namespace = 'projects';

  export default {
    name: 'project-process-form',

    data() {
      return {
        activeIndex: 0,
        tabsLength: 0
      }
    },

    computed: {
      ...mapGetters({
        language: 'language',
      })
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: 'projects/loadProject',
        loadProcessInstance: 'processes/loadInstance'
      }),

      setupStage() {
        this.$nextTick(() => {
          // we need to wait until the dom is ready
          // so that we have access to the tabs panes
          if (this.$refs.tabs) {
            // tabsLength needs to be zero-based
            this.tabsLength = this.$refs.tabs.panes.length - 1;
          }
        });
      },

      onTabClick(tab, e) {
        this.activeIndex = parseInt(tab.index, 10);
      },

      async triggerLoadProject() {
        let projectId = this.$route.params.projectId;
        await this.loadProject(projectId);
      },

      async triggerLoadProcessInstance() {
        let processId = this.$route.params.processId;
        await this.loadProcessInstance(processId);
      },

      async fetch() {
        try {
          this.showMainLoading();
          await this.triggerLoadProject();
          await this.triggerLoadProcessInstance();
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
      this.setupStage();
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';
  @import '../../../sass/abstracts/functions';
  @import '../../../sass/abstracts/mixins/helpers';
  @import '../../../sass/base/helpers';

  .project-process-form {
    height: calc(100% - 20px);
    display: flex;
    flex-direction: column;
    span.index-marker {
      width: 12px;
      height: 12px;
      background-color: #ccc;
      border-radius: 50%;
      display: inline-block;
      margin-right: 10px;
    }

    .el-tabs {
      // fixes the height of the tab list when there are less tabs than content
      display: flex;
      height: 100%;
      .el-tabs__content {
        overflow: auto;
        flex: 1;
      }
      .is-active span.index-marker {
        background-color: $--color-primary;
      }
      .el-tabs__item {
        text-align: left;
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
      .el-tabs {
        position: absolute;
        width: 100%;
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
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        &.form-header-details {
          justify-content: flex-start;
          i {
            margin-right: 5px;
            @include svg(form, $--color-white);
          }
        }
      }
      footer {
        box-shadow: 0 -3px 6px rgba(0,0,0,0.16), 0 -3px 6px rgba(0,0,0,0.23);
      }
      .el-main {
        padding: 0;
        overflow: hidden;
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
      .form-header header:first-of-type, .form-footer footer:first-of-type, .el-tabs__header {
        width: 20%;
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
