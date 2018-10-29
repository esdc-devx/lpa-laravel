<template>
  <div class="project-process-form content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.general.status') }}</dt>
            <dd><span :class="'state ' + viewingFormInfo.state.name_key">{{ viewingFormInfo.state.name }}</span></dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.general.assigned_organizational_units') }}</dt>
            <dd>{{ viewingFormInfo.organizational_unit ? viewingFormInfo.organizational_unit.name : trans('entities.general.none') }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.form.current_editor') }}</dt>
            <dd>
              {{
                viewingFormInfo.current_editor ?
                viewingFormInfo.current_editor.name :
                trans('entities.general.none')
              }}
              <el-button-wrap
                v-if="viewingFormInfo.current_editor && hasRole('admin')"
                :disabled="!rights.canReleaseForm"
                @click.native="onReleaseForm"
                :tooltip="trans('components.tooltip.release_form')"
                class="release-form"
                type="danger"
                size="mini"
                icon="el-icon-close"
                circle />
            </dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd>{{ viewingFormInfo.updated_by ? viewingFormInfo.updated_by.name : trans('entities.general.none') }}</dd>
            <dd>{{ viewingFormInfo.updated_at }}</dd>
          </dl>
        </info-box>
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <div class="form-header">
            <el-header>
              <!--
                Switch disabled based on rights or if we are loading,
                to prevent spam while there is already a request sent
              -->
              <el-switch
                v-if="hasRole('process-contributor') || hasRole('admin')"
                class="claim"
                active-color="#b3b0cc"
                inactive-color="#75bfff"
                v-model="isClaimed"
                :inactive-text="trans('entities.form.claim')"
                >
              </el-switch>
            </el-header>
            <el-header class="form-header-details">
              <div class="form-name">
                <i class="el-icon-lpa-form"></i>
                {{ viewingFormInfo.definition.name }}
              </div>
            </el-header>
          </div>
          <el-main>
            <el-form label-position="top" @submit.native.prevent :disabled="!isClaimed" :class="{'is-disabled': !isClaimed}">
              <component
                :is="formComponent"
                ref="tabs"
                type="border-card"
                tabPosition="left"
                :value.sync="activeIndex"
                :formData="formData"
                :errorTabs="errorTabs">
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
              <div class="form-footer-actions" v-if="hasRole('process-contributor') || hasRole('admin')">
                <el-button :disabled="!isFormDirty" @click="onCancel" size="mini">{{ trans('base.actions.cancel') }}</el-button>
                <el-button :disabled="!isFormDirty" :loading="isSaving" @click="onSave" size="mini">{{ trans('base.actions.save') }}</el-button>
                <el-button :disabled="!rights.canSubmit || (isFormEmpty || !isClaimed)" :loading="isSubmitting" @click="onSubmit" size="mini">{{ trans('base.actions.submit') }}</el-button>
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
  import EventBus from '@/event-bus.js';

  import HttpStatusCodes from '@axios/http-status-codes';

  import Page from '@components/page';
  import InfoBox from '@components/info-box.vue';
  import ElButtonWrap from '@components/el-button-wrap.vue';
  // Forms
  import BusinessCase from '@components/forms/entities/business-case';
  import PlannedProductList from '@components/forms/entities/planned-product-list';
  import GateOneApproval from '@components/forms/entities/gate-one-approval';

  import FormUtils from '@mixins/form/utils';

  import ProcessAPI from '@api/processes';

  let namespace = 'projects';

  export default {
    name: 'project-process-form',

    extends: Page,

    components: { InfoBox, ElButtonWrap, BusinessCase, PlannedProductList, GateOneApproval },

    mixins: [ FormUtils ],

    events: {
      'TopBar:beforeLogout': 'beforeLogout'
    },

    data() {
      return {
        projectId: null,
        processId: null,
        formId: null,
        options: {
          hasTabsToValidate: true
        },
        formData: {},
        activeIndex: '0',
        tabsLength: 0,
        formComponent: '',
        rights: {
          canEdit: false,
          canClaim: false,
          canUnclaim: false,
          canSubmit: false,
          canReleaseForm: false
        },
        isFormSubmitted: false
      }
    },

    computed: {
      ...mapGetters({
        viewingProcess: 'processes/viewing',
        viewingFormInfo: 'processes/viewingFormInfo'
      }),

      isClaimed: {
        get() {
          // @todo: create loaded flags so that we know when the data has been loaded
          return this.isCurrentUser(this.viewingFormInfo.current_editor);
        },
        // update the value server side
        async set(val) {
          // claiming
          if (val) {
            try {
              await this.claimForm(this.$route.params.formId);
              if (this.isCurrentUser(this.viewingFormInfo.current_editor)) {
                this.rights.canSubmit = await this.canSubmitForm(this.$route.params.formId);
              }
            } catch (e) {
              if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
                try {
                  await this.refreshData();
                } catch (e) {
                  // Exception handled by interceptor
                  if (!e.response) {
                    throw e;
                  }
                }
              } else {
                throw e;
              }
            }

          // unclaiming
          } else if (this.shouldConfirmBeforeLeaving) {
            this.confirmLoseChanges()
              .then(() => {
                this.discardChanges();
              }).catch(() => false);
          } else {
            // discard without confirming
            this.discardChanges();
          }
        }
      },

      isPrevDisabled() {
        return parseInt(this.activeIndex, 10) === 0;
      },

      isNextDisabled() {
        return parseInt(this.activeIndex, 10) === this.tabsLength;
      },

      /**
       * This function gets all values from the form,
       * removes the falsy values,
       * put them on the same level of depth
       * and checks for the remaining values to be empty
       */
      isFormEmpty() {
        return !_.chain(this.formData).omit('id').values().compact().flattenDeep().value().filter(item => {
          // returns true if the props from the item are falsy values
          return !_.isEmpty(_.compact(_.toArray(item)));
        }).length;
      }
    },

    watch: {
      isFormDirty: function () {
        this.confirmBeforeLeaving(this.isFormDirty);
      }
    },

    methods: {
      ...mapActions({
        loadProject: `${namespace}/loadProject`,
        loadProcessInstance: 'processes/loadInstance',
        loadProcessInstanceForm: 'processes/loadInstanceForm',
        claimForm: 'processes/claimForm',
        unclaimForm: 'processes/unclaimForm',
        canEditForm: 'processes/canEditForm',
        canClaimForm: 'processes/canClaimForm',
        canUnclaimForm: 'processes/canUnclaimForm',
        canSubmitForm: 'processes/canSubmitForm',
        canReleaseForm: 'processes/canReleaseForm',
        saveForm: 'processes/saveForm',
        submitForm: 'processes/submitForm',
        releaseForm: 'processes/releaseForm'
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

      onCancel() {
        this.confirmLoseChanges()
          .then(() => {
            this.discardChanges(false);
          }).catch(() => false);
      },

      discardChanges(shouldUnclaim = true) {
        this.$helpers.debounceAction(async () => {
          let formWasDirty = false;

          // if form is dirty, reset the form data
          if (this.isFormDirty) {
            formWasDirty = true;
            // deep copy so that we don't alter the store's data
            this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
            EventBus.$emit('FormUtils:fieldsAddedOrRemoved', false);
          }

          this.$nextTick(() => {
            // make childrens react on discarding changes
            EventBus.$emit('FormEntity:discardChanges');
            // make form section groups react and repopulate themselves
            EventBus.$emit('FormEntity:resetFormSectionGroup');
          });

          // remove ownership on form
          try {
            // No need to unclaim the form if it was submitted
            // since the backend will automatically unclaim it.
            if (!this.isFormSubmitted && shouldUnclaim) {
              await this.unclaimForm(this.$route.params.formId);
            }

            if (formWasDirty) {
              this.notifyInfo({
                message: this.trans('components.notice.message.changes_discarded')
              });
            }
          } catch (e) {
            // Exception handled by interceptor
            if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
              // reload rights since we are potentially unsynced
              await this.refreshData();
            } else {
              throw e;
            }
          }
          finally {
            // reset the fields states
            // so that we get a pristine form
            // but wait until dom is refreshed before resetting the fields state
            this.$nextTick(() => {
              this.resetFieldsState();
              this.resetErrors();
            });
          }
        });
      },

      getCurrentEditorUsername() {
        return this.viewingFormInfo.current_editor ? this.viewingFormInfo.current_editor.username : null;
      },

      onReleaseForm() {
        this.confirmReleaseForm().then(async () => {
          try {
            await this.releaseForm({
              formId: this.$route.params.formId,
              username: this.getCurrentEditorUsername()
            });
            this.notifySuccess({
              message: this.trans('components.notice.message.form_released')
            });
            await this.refreshData();
          } catch (e) {
            // Exception handled by interceptor
            if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
              await this.refreshData();
            } else {
              throw e;
            }
          }
        }).catch(() => false);
      },

      async onSave() {
        this.isSaving = true;
        try {
          await this.saveForm({ formId: this.$route.params.formId, form: this.formData });
          EventBus.$emit('FormUtils:fieldsAddedOrRemoved', false);
          // reset the fields states
          // so that we get a pristine form with the new values
          this.resetFieldsState();
          // make sure to not keep the current errors
          // so that validated childrens gets a pristine state as well
          this.resetErrors();
          this.isSaving = false;
          this.notifySuccess({
            message: this.trans('components.notice.message.changes_saved')
          });
        } catch (e) {
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.discardChanges(true);
            this.loadPermissions();
            this.isSaving = false;
          } else {
            throw e;
          }
        }
      },

      onSubmit() {
        this.confirmSubmitForm().then(() => {
          this.submit(this.triggerSubmitForm);
        }).catch(() => false);
      },

      async refreshData() {
        this.loadPermissions();
        let formWasDirty = this.isFormDirty;
        let oldUpdatedDate = this.viewingFormInfo.updated_at;
        await this.loadProcessInstanceForm(this.$route.params.formId);

        // deep copy so that we don't alter the store's data
        this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
        this.$nextTick(() => {
          // make childrens react on discarding changes
          EventBus.$emit('FormEntity:discardChanges');
          // make form section groups react and repopulate themselves
          EventBus.$emit('FormEntity:resetFormSectionGroup');
          // reset the fields states
          // so that we get a pristine form
          // but wait until dom is refreshed before resetting the fields state
          this.resetFieldsState();
          this.resetErrors();
        });

        if (formWasDirty) {
          // when user was kicked out of a form
          this.notifyInfo({
            message: this.trans('components.notice.message.changes_discarded')
          });
        }
        // check if updated data was fetched
        if (oldUpdatedDate !== this.viewingFormInfo.updated_at) {
          this.notifyInfo({
            message: this.trans('components.notice.message.data_refreshed')
          });
        }
      },

      async triggerSubmitForm() {
        try {
          await this.submitForm({ formId: this.$route.params.formId, form: this.formData });
          EventBus.$emit('FormUtils:fieldsAddedOrRemoved', false);
          this.notifySuccess({
            message: this.trans('components.notice.message.form_submitted')
          });
          // sets the flag so that we do not unclaim the form after submitting it
          // since the backend will automatically unclaim it.
          this.isFormSubmitted = true;
          this.goToParentPage();
        } catch (e) {
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.discardChanges(true);
            this.loadPermissions();
          } else {
            throw e;
          }
        }
      },

      async loadData(isInitialLoad = true) {
        if (isInitialLoad) {
          // deep copy so that we don't alter the store's data
          this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
          this.formComponent = this.viewingFormInfo.definition.name_key;
          this.setupStage();
        } else {
          let requests = [];
          requests.push(
            this.loadProject(this.$route.params.projectId),
            this.loadProcessInstance(this.$route.params.processId),
            this.loadProcessInstanceForm(this.$route.params.formId)
          );
          // Exception handled by interceptor
          axios.all(requests);
        }
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

      async loadPermissions() {
        let requests = [];
        requests.push(
          this.canEditForm(this.$route.params.formId),
          this.canClaimForm(this.$route.params.formId),
          this.canUnclaimForm(this.$route.params.formId),
          this.canReleaseForm({
            formId: this.$route.params.formId,
            username: this.getCurrentEditorUsername()
          })
        );
        if (this.isCurrentUser(this.viewingFormInfo.current_editor)) {
          requests.push(this.canSubmitForm(this.$route.params.formId));
        }
        axios.all(requests)
          .then(axios.spread((canEditForm, canClaimForm, canUnclaimForm, canReleaseForm, canSubmitForm) => {
            this.rights.canEdit = canEditForm;
            this.rights.canClaim = canClaimForm;
            this.rights.canUnclaim = canUnclaimForm;
            this.rights.canRelease = canReleaseForm;
            if (!_.isUndefined(canSubmitForm)) {
              this.rights.canSubmit = canSubmitForm;
            }
          }));
      },

      beforeLogout(callback) {
        this.confirmLoseChanges().then(() => {
          this.discardChanges();
          callback();
        }).catch(() => false);
      }
    },

    beforeRouteEnter(to, from, next) {
      // Exception handled by interceptor
      axios.all([
        store.dispatch('projects/loadProject', to.params.projectId),
        store.dispatch('processes/loadInstance', to.params.processId),
        store.dispatch('processes/loadInstanceForm', to.params.formId)
      ]).then(() => {
        next(async vm => {
          // deep copy so that we don't alter the store's data
          vm.formData = _.cloneDeep(vm.viewingFormInfo.form_data);
          vm.formComponent = vm.viewingFormInfo.definition.name_key;
          vm.setupStage();
          await vm.loadPermissions();
        });
      });
    },

    async beforeRouteLeave(to, from, next) {
      if (this.shouldConfirmBeforeLeaving && !this.isFormSubmitted) {
        this.confirmLoseChanges().then(() => {
          this.destroyEvents();
          this.discardChanges();
          next();
        }).catch(() => false);
      } else {
        this.destroyEvents();
        // if user is currently claiming, remove claim
        if (this.isClaimed) {
          // just change the value here and let the reactivity take care
          // of what it should do if user is no longer the editor.
          this.isClaimed = false;
        }
        // this make sure to reset the flag "shouldConfirmBeforeLeaving"
        this.$nextTick(() => {
          this.resetFieldsState();
          this.resetErrors();
        });
        next();
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';
  @import '~@sass/base/helpers';

  .project-process-form {
    height: calc(100% - 20px);
    display: flex;
    flex-direction: column;

    button.release-form {
      padding: 0;
      margin-left: 10px;
    }

    .info-box {
      dl {
        flex-basis: 25%;
        max-width: 25%; // Patch for IE11. See https://github.com/philipwalton/flexbugs/issues/3#issuecomment-69036362
      }
    }

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
        &.is-active span.is-error, &.is-active span.is-error:hover {
          color: $--color-danger;
          &:after {
            background-color: $--color-danger;
          }
        }
        span {
          transition: $--all-transition;
          display: block;
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
          color: mix($--color-white, $--color-danger, 40%);
          &:after {
            background-color: mix($--color-white, $--color-danger, 40%);
          }
          &:hover {
            color: mix($--color-white, $--color-danger, 20%);
            &:after {
              background-color: mix($--color-white, $--color-danger, 20%);
            }
          }
        }
      }
    }

    header, footer {
      background: #737680;
      color: #fff;
      display: flex;
      align-items: center;
    }

    .form-row {
      flex: 1;
      .el-col, .form-wrap {
        height: 100%;
      }
    }
    .form-wrap {
      min-height: 400px;
      header, footer {
        padding: 0 30px;
        &:nth-child(2) {
          flex: 1;
        }
      }
      header {
        height: 40px !important;
        box-shadow: $box-shadow-base-bottom;
        .el-switch__label {
          color: $--color-white;
        }
        &.form-header-details {
          justify-content: flex-start;
          font-size: 16px;
          font-weight: 500;
          i {
            margin-right: 5px;
            @include svg(form, $--color-white);
          }
          .form-name {
            flex: 1;
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

        label {
          color: $--color-primary;
          font-weight: 500;
          line-height: 10px;
        }
        .instruction {
          font-style: italic;
          display: block;
          line-height: normal;
          color: $--color-text-regular;
        }
        .is-required .instruction {
          // align under label, make room under asterisk
          margin-left: 14px;
        }

        .el-tab-pane h2 {
          border-bottom: 1px solid;
          margin-top: 0;
          text-transform: uppercase;
          font-size: 1.3em;
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
