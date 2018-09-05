<template>
  <div class="project-process-form content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.form.status') }}</dt>
            <dd>{{ viewingFormInfo.state.name }}</dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.general.assigned_organizational_units') }}</dt>
            <dd>{{ viewingFormInfo.organizational_unit ? viewingFormInfo.organizational_unit.name : trans('entities.general.na') }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.form.current_editor') }}</dt>
            <dd>
              {{
                viewingFormInfo.current_editor ?
                viewingFormInfo.current_editor.name :
                trans('entities.general.na')
              }}
              <el-button @click="onReleaseForm" class="release-form" type="danger" size="mini" v-if="viewingFormInfo.current_editor && hasRole('admin')" :disabled="!rights.canReleaseForm" icon="el-icon-close" circle></el-button>
            </dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated') }}</dt>
            <dd>{{ viewingFormInfo.updated_by ? viewingFormInfo.updated_by.name : trans('entities.general.na') }}</dd>
            <dd>{{ viewingFormInfo.updated_at }}</dd>
          </dl>
        </info-box>
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <div class="form-header">
            <el-header></el-header>
            <el-header class="form-header-details">
              <div class="form-name">
                <i class="el-icon-lpa-form"></i>
                {{ viewingFormInfo.definition.name }}
              </div>
              <!--
                Switch disabled based on rights or if we are loading,
                to prevent spam while there is already a request sent
              -->
              <el-switch
                v-if="hasRole('process-contributor') || hasRole('admin')"
                :disabled="!rights.canClaim && !rights.canUnclaim || isMainLoading"
                class="claim"
                active-color="#13ce66"
                v-model="isClaimed"
                :inactive-text="trans('entities.form.claim')">
              </el-switch>
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

  import InfoBox from '@components/info-box.vue';

  import PageUtils from '@mixins/page/utils.js';
  import FormUtils from '@mixins/form/utils';

  // Forms
  import BusinessCase from '@components/forms/entities/business-case';
  import BusinessCaseAssessment from '@components/forms/entities/business-case-assessment';
  import ArchitecturePlan from '@components/forms/entities/architecture-plan';
  import ArchitecturePlanAssessment from '@components/forms/entities/architecture-plan-assessment';

  let namespace = 'projects';

  export default {
    name: 'project-process-form',

    components: { InfoBox, BusinessCase, BusinessCaseAssessment, ArchitecturePlan, ArchitecturePlanAssessment },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    $_veeValidate: {
      validator: 'new'
    },

    mixins: [ PageUtils, FormUtils ],

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
        language: 'language',
        isMainLoading: 'isMainLoading',
        shouldConfirmBeforeLeaving: 'shouldConfirmBeforeLeaving',
        hasRole: 'users/hasRole',
        isCurrentUser: 'users/isCurrentUser',
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
            await this.showMainLoading();
            try {
              await this.claimForm(this.formId);
              if (this.isCurrentUser(this.viewingFormInfo.current_editor)) {
                this.rights.canSubmit = await this.canSubmitForm(this.formId);
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
            finally {
              await this.hideMainLoading();
            }
          // unclaiming
          } else if (this.shouldConfirmBeforeLeaving) {
            this.confirmLoseChanges()
              .then(async () => {
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
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        confirmBeforeLeaving: 'confirmBeforeLeaving',
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
          await this.showMainLoading();
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
              await this.unclaimForm(this.formId);
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
            await this.hideMainLoading();
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
              formId: this.formId,
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
          await this.saveForm({ formId: this.formId, form: this.formData });
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
            await this.refreshData();
            this.$nextTick(() => {
              // make childrens react on discarding changes
              EventBus.$emit('FormEntity:discardChanges');
            });
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
        this.getRights();
        let formWasDirty = this.isFormDirty;
        let oldUpdatedDate = _.cloneDeep(this.viewingFormInfo.updated_at);
        await this.loadProcessInstanceForm(this.formId);

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
        if (oldUpdatedDate !== this.formData.updated_at) {
          this.notifyInfo({
            message: this.trans('components.notice.message.data_refreshed')
          });
        }
      },

      async triggerSubmitForm() {
        try {
          await this.submitForm({ formId: this.formId, form: this.formData });
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
            await this.refreshData();
            this.$nextTick(() => {
              // make childrens react on discarding changes
              EventBus.$emit('FormEntity:discardChanges');
              // make form section groups react and repopulate themselves
              EventBus.$emit('FormEntity:resetFormSectionGroup');
            });
          }
        }
      },

      async fetch(isInitialLoad = true) {
        await this.showMainLoading();
        try {
          if (isInitialLoad) {
            // deep copy so that we don't alter the store's data
            this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
            this.formComponent = this.viewingFormInfo.definition.name_key;
            this.setupStage();
          } else {
            await this.loadProject(this.projectId);
            await this.loadProcessInstance(this.processId);
            await this.loadProcessInstanceForm(this.formId);
          }
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
        finally {
          await this.hideMainLoading();
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

      async getRights() {
        try {
          if (this.isCurrentUser(this.viewingFormInfo.current_editor)) {
            this.rights.canSubmit = await this.canSubmitForm(this.formId);
          }
          this.rights.canEdit = await this.canEditForm(this.formId);
          this.rights.canClaim = await this.canClaimForm(this.formId);
          this.rights.canUnclaim = await this.canUnclaimForm(this.formId);
          this.rights.canReleaseForm = await this.canReleaseForm({
            formId: this.formId,
            username: this.getCurrentEditorUsername()
          });
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        }
        finally {
          await this.hideMainLoading();
        }
      },

      beforeLogout(callback) {
        this.confirmLoseChanges().then(async () => {
          this.discardChanges();
          callback();
        }).catch(() => false);
      },

      onLanguageUpdate() {
        this.fetch(false);
      },

      destroyEvents() {
        // Destroy any events we might be listening
        // so that they do not get called while being on another page
        EventBus.$off('TopBar:beforeLogout', this.beforeLogout);
      }
    },

    async beforeRouteLeave(to, from, next) {
      if (this.shouldConfirmBeforeLeaving && !this.isFormSubmitted) {
        this.confirmLoseChanges().then(async () => {
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
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.onLanguageUpdate();
      next();
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.fetch();
      });
    },

    async created() {
      await this.showMainLoading();
      // store the reference to the current form id
      this.projectId = this.$route.params.projectId;
      this.processId = this.$route.params.processId;
      this.formId = this.$route.params.formId;
      this.getRights();
    },

    mounted() {
      EventBus.$emit('App:ready');
      // @note: hide the loading that was shown
      // in the router's beforeEnter
      this.$nextTick(async () => {
        await this.hideMainLoading();
      });
      EventBus.$on('TopBar:beforeLogout', this.beforeLogout);
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
      justify-content: center;
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
          .claim .el-switch__label {
            color: $--color-white;
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
