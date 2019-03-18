<template>
  <div class="process-form">
    <el-row>
      <el-col>
        <infobox-process-form
          :form="viewingFormInfo"
          :isClaimed.sync="isClaimed"
          @releaseForm="onReleaseForm"
        />
      </el-col>
    </el-row>
    <el-row class="form-row">
      <el-col>
        <el-container class="form-wrap" direction="vertical">
          <el-main>
            <el-form label-position="top" @submit.native.prevent :disabled="!isClaimed" :class="{'is-disabled': !isClaimed}">
              <component
                :is="formComponent"
                type="border-card"
                tabPosition="left"
                :value.sync="activeIndex"
                :formData="formData"
                :errorTabs="errorTabs"
                :processEntityType="viewingProcess.entity_type"
                @mounted="onFormMounted"
              />
            </el-form>
          </el-main>
          <div class="process-form-footer">
            <el-footer height="50px"></el-footer>
            <el-footer height="50px">
              <div class="process-form-footer-nav">
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
              <div class="process-form-footer-actions" v-if="hasRole('process-contributor') || hasRole('admin')">
                <el-button :disabled="!isFormDirty" @click="onCancel" :loading="isCancelling" size="mini">{{ trans('base.actions.cancel') }}</el-button>
                <el-button :disabled="!isFormDirty" :loading="isSaving" @click="onSave" size="mini">{{ trans('base.actions.save') }}</el-button>
                <el-button :disabled="!canSubmitForm || (isFormEmpty || !isClaimed)" :loading="isSubmitting" @click="onSubmit" size="mini">{{ trans('base.actions.submit') }}</el-button>
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
  import { mapGetters, mapActions, mapMutations } from 'vuex';

  import HttpStatusCodes from '@axios/http-status-codes';

  import Page from '@components/page';
  import InfoboxProcessForm from '@components/infoboxes/infobox-process-form';

  import FormUtils from '@mixins/form/utils';

  import Process from '@/store/models/Process';
  import ProcessInstanceForm from '@/store/models/Process-Instance-Form';

  const loadData = async function ({ to, hasToLoadEntity = true } = {}) {
    const { entityName, entityId, processId, formId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`entities/${_.camelCase(entityName)}/load`, entityId),
      store.dispatch('entities/processes/loadInstance', processId),
      store.dispatch('authorizations/forms/loadCanEditForm', formId),
      store.dispatch('authorizations/forms/loadCanClaimForm', formId),
      store.dispatch('authorizations/forms/loadCanUnclaimForm', formId),
      store.dispatch('authorizations/forms/loadCanSubmitForm', formId),
      store.dispatch('authorizations/forms/loadCanReleaseForm', formId)
    );
    if (hasToLoadEntity) {
      requests.push(store.dispatch('entities/forms/loadInstanceForm', formId));
    }

    // Exception handled by interceptor
    try {
      await axios.all(requests);
    } catch (e) {
      if (!e.response) {
        throw e;
      }
    }
  };

  export default {
    name: 'process-form',

    extends: Page,

    components: { InfoboxProcessForm },

    mixins: [ FormUtils ],

    props: {
      entityName: {
        type: String,
        required: true
      },
      entityId: {
        type: String,
        required: true
      },
      processId: {
        type: String,
        required: true
      },
      formId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        options: {
          hasTabsToValidate: true
        },
        formData: {},
        activeIndex: '0',
        formWasDirty: false,
        tabsLength: 0,
        isCancelling: false
      }
    },

    computed: {
      formComponent() {
        return () => import(
          /* webpackMode: "eager" */
          `@components/forms/entities/${this.viewingFormInfo.definition.name_key}.vue`
        );
      },

      viewingProcess() {
        return Process.find(this.processId);
      },

      viewingFormInfo() {
        return ProcessInstanceForm.find(this.formId);
      },

      canSubmitForm() {
        return ProcessInstanceForm.find(this.formId).permissions.canSubmitForm;
      },

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
              this.handleClaim();
            } catch (e) {
              // Exception handled by interceptor
              if (!e.response) {
                throw e;
              }
            }
          // unclaiming
          } else if (this.shouldConfirmBeforeLeaving) {
            this.confirmLoseChanges()
              .then(() => {
                this.handleUnclaim();
              }).catch(() => false);
          } else {
            this.handleUnclaim();
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
        return !_.chain(this.formData)
                .omit('id', 'process_instance_id', 'process_instance_form_id').values()
                .compact()
                .flattenDeep()
                .value().filter(item => {
                  if (_.isArray(item) || _.isObject(item)) {
                    // returns true if the props from the item are falsy values
                    return !_.isEmpty(_.compact(_.toArray(item)));
                  } else {
                    return !_.isNull(item);
                  }
                }
        ).length;
      }
    },

    watch: {
      isFormDirty: function () {
        this.setShouldConfirmBeforeLeaving(this.isFormDirty);
      }
    },

    methods: {
      ...mapActions('entities/forms', [
        'claimForm',
        'unclaimForm',
        'saveForm',
        'submitForm',
        'releaseForm'
      ]),

      ...mapActions('authorizations/forms', [
        'loadCanSubmitForm'
      ]),

      ...mapMutations('entities/forms', [
        'setHasFormSectionGroupsRemoved'
      ]),

      loadData,

      onLanguageUpdate() {
        this.loadData.apply(this, {
          hasToLoadEntity: false
        });
      },

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
        this.isCancelling = true;
        this.confirmLoseChanges()
          .then(async () => {
            // we cannot reloadData here
            // since if we lost ownership the form will refresh
            // and we won't be claiming the form anymore
            this.refreshForm();
            this.isCancelling = false;
          }).catch(() => {
            this.isCancelling = false;
          });
      },

      refreshForm() {
        // keep a copy of current state
        // so that we can compare later on if the data has changed
        const formWasDirty = this.isFormDirty;
        // affect the newly fetched info locally
        this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
        this.setHasFormSectionGroupsRemoved(false);
        this.$nextTick(() => {
          // reset the fields states
          // so that we get a pristine form
          // but wait until dom is refreshed before resetting the fields state
          this.resetFieldsState();
          this.resetErrors();
        });

        if (formWasDirty) {
          this.notifyInfo({
            message: this.trans('components.notice.message.changes_discarded')
          });
        }
      },

      async reloadData() {
        const oldUpdatedDate = this.viewingFormInfo.updated_at;
        await loadData.apply(this);

        this.refreshForm();

        // check if there was new data that was fetched
        if (oldUpdatedDate !== this.viewingFormInfo.updated_at) {
          this.notifyInfo({
            message: this.trans('components.notice.message.data_refreshed')
          });
        }
      },

      getCurrentEditorUsername() {
        return !_.isEmpty(this.viewingFormInfo.current_editor) ? this.viewingFormInfo.current_editor.username : null;
      },

      async onReleaseForm() {
        try {
          await this.releaseForm({
            id: this.formId,
            username: this.getCurrentEditorUsername()
          });
          this.notifySuccess({
            message: this.trans('components.notice.message.form_released')
          });
        } catch (e) {
          // Exception handled by interceptor
          if (!e.response) {
            throw e;
          }
        } finally {
          this.reloadData();
        }
      },

      async onSave() {
        this.isSaving = true;
        try {
          await this.saveForm({ id: this.formId, form: this.formData });
          this.setHasFormSectionGroupsRemoved(false);
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
            await this.reloadData();
          } else {
            throw e;
          }
        } finally {
          this.isSaving = false;
        }
      },

      onSubmit() {
        this.confirmSubmitForm().then(() => {
          this.submit(this.handleSubmit);
        }).catch(() => false);
      },

      async handleSubmit() {
        try {
          await this.submitForm({ id: this.formId, form: this.formData });
          this.setHasFormSectionGroupsRemoved(false);
          this.notifySuccess({
            message: this.trans('components.notice.message.form_submitted')
          });
          this.$nextTick(() => {
            // reset the fields states
            // so that we get a pristine form
            // but wait until dom is refreshed before resetting the fields state
            this.resetFieldsState();
            this.goToParentPage();
          });
        } catch (e) {
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.reloadData();
          } else {
            throw e;
          }
        }
      },

      async handleClaim() {
        try {
          await this.claimForm(this.formId);
          this.reloadData();
        } catch (e) {
          // Exception handled by interceptor
          // we tried to claim a form that is already owned by someone else, -> refresh data
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN) {
            this.reloadData();
          } else if (!e.response) {
            throw e;
          }
        }
      },

      /**
       * @param { Boolean } isNavigating - Whether or not to refresh data
       */
      async handleUnclaim(isNavigating = false) {
        try {
          if (this.isCurrentUser(this.viewingFormInfo.current_editor)) {
            await this.unclaimForm(this.formId);
          }
          if (!isNavigating && this.isFormDirty) {
            this.refreshForm();
          } else if (this.isFormDirty) {
            // this make sure to reset the flag "shouldConfirmBeforeLeaving"
            this.$nextTick(() => {
              this.resetFieldsState();
              this.resetErrors();
            });
          }
        } catch (e) {
          // Exception handled by interceptor
          // we tried to unclaim a form that we no longer own, -> refresh data
          // @note: if we are in a case that isNavigating is false, do not discard and refresh the data
          if (e.response && e.response.status === HttpStatusCodes.FORBIDDEN && !isNavigating) {
            this.reloadData();
          } else if (!e.response) {
            throw e;
          }
        }
      },

      setupTabPanes() {
        this.$nextTick(() => {
          // we need to wait until the dom is ready
          // so that we have access to the tabs panes
          if (this.$el.querySelectorAll('.el-tab-pane')) {
            // tabsLength needs to be zero-based
            this.tabsLength = this.$el.querySelectorAll('.el-tab-pane').length - 1;
          }
        });
      },

      onFormMounted() {
        this.setupTabPanes();
      },

      async beforeLogout() {
        if (this.shouldConfirmBeforeLeaving) {
          this.confirmLoseChanges().then(async () => {
            // load only the form info so that we know if we have to unclaim the form or not
            await this.handleUnclaim(true);
            this.setWaitForLogout(false);
          }).catch(() => false);
        } else {
          await this.handleUnclaim(true);
          this.setWaitForLogout(false);
        }
      }
    },

    async beforeRouteEnter(to, from, next) {
      // Exception handled by interceptor
      await loadData({ to });
      next();
    },

    beforeRouteLeave(to, from, next) {
      const leave = async () => {
        await this.handleUnclaim(true);
        next();
      };
      if (this.shouldConfirmBeforeLeaving) {
        this.confirmLoseChanges().then(() => {
          leave();
        }).catch(() => false);
      } else {
        leave();
      }
    },

    created() {
      // deep copy so that we don't alter the store's data
      this.formData = _.cloneDeep(this.viewingFormInfo.form_data);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .process-form {
    height: calc(100% - 20px);
    display: flex;
    flex-direction: column;
    // override: no space at the very end of the page
    padding-bottom: 0;
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
        text-align: left;
        padding: 0;
        &:hover span:before {
          background-color: $--color-primary-light-4;
        }
        &.is-active span:before {
          background-color: $--color-primary;
        }
        &.is-active span.is-error, &.is-active span.is-error:hover {
          color: $--color-danger;
          &:before {
            background-color: $--color-danger;
          }
        }
        span {
          transition: $--all-transition;
          text-overflow: ellipsis;
          white-space: nowrap;
          overflow: hidden;
          display: inline-block;
          vertical-align: middle;
          width: 100%;
          &:before {
            @include size($svg-icons-size-x-small);
            content: '';
            // Fixes IE11 not showing the before element
            display: inline-block;
            margin: 0 10px;
            margin-right: 5px;
            transition: $--all-transition;
            background-color: #ccc;
            border-radius: 50%;
          }
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
      background: #656273;
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
        .el-switch__label {
          color: $--color-white;
        }
      }
      .el-main {
        overflow: hidden;
        background-color: $--color-white;
        border-right: $--border-base;
        border-radius: $--border-radius-base;
        // make sure that when the content is loading, that we display a fake form index so that layout is consistant
        &:before {
          content: '';
          position: absolute;
          left: 0;
          top: 0;
          height: 100%;
          background-color: $--background-color-base;
          border: $--border-base;
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
        .el-tab-pane h3 {
          margin-top: 0;
          text-transform: uppercase;
        }
      }
      .process-form-footer footer:first-of-type,
      .el-tabs__header,
      .el-main:before {
        width: 20%;
        min-width: 250px;
        box-sizing: border-box;
      }

      .process-form-footer {
        z-index: $--index-top;
        display: flex;
        margin-top: 0;
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
        footer:first-of-type {
          padding: 0 30px;
          border-right: 1px solid;
        }
      }
    }
  }
</style>
