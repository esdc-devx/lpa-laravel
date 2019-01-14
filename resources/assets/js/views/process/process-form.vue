<template>
  <div class="process-form content">
    <el-row>
      <el-col>
        <info-box>
          <dl>
            <dt>{{ trans('entities.general.status') }}</dt>
            <dd><span :class="'state ' + viewingFormInfo.state.name_key">{{ viewingFormInfo.state.name }}</span></dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.form.assigned_organizational_units') }}</dt>
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
                :disabled="!canReleaseForm"
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
            <dd v-audit:updated="viewingFormInfo"></dd>
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

  import Process from '@/store/models/Process';
  import ProcessInstanceForm from '@/store/models/Process-Instance-Form';

  const loadData = async function ({ to, hasToLoadEntity = true } = {}) {
    const { entityName, entityId, processId, formId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch(`entities/${entityName}/load`, entityId),
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

    components: { InfoBox, ElButtonWrap, BusinessCase, PlannedProductList, GateOneApproval },

    mixins: [ FormUtils ],

    events: {
      'TopBar:beforeLogout': 'beforeLogout'
    },

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
        formComponent: '',
        isCancelling: false
      }
    },

    computed: {
      viewingFormInfo() {
        return ProcessInstanceForm.find(this.formId);
      },

      canSubmitForm() {
        return ProcessInstanceForm.find(this.formId).permissions.canSubmitForm;
      },

      canReleaseForm() {
        return ProcessInstanceForm.find(this.formId).permissions.canReleaseForm;
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
        return !_.chain(this.formData).omit('id').values().compact().flattenDeep().value().filter(item => {
          // returns true if the props from the item are falsy values
          return !_.isEmpty(_.compact(_.toArray(item)));
        }).length;
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
        EventBus.$emit('FormUtils:fieldsAddedOrRemoved', false);
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

      onReleaseForm() {
        this.confirmReleaseForm().then(async () => {
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
        }).catch(() => false);
      },

      async onSave() {
        this.isSaving = true;
        try {
          await this.saveForm({ id: this.formId, form: this.formData });
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
          EventBus.$emit('FormUtils:fieldsAddedOrRemoved', false);
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
          if (this.$refs.tabs.$children[0].panes) {
            // tabsLength needs to be zero-based
            this.tabsLength = this.$refs.tabs.$children[0].panes.length - 1;
          }
        });
      },

      beforeLogout(callback) {
        this.confirmLoseChanges().then(async () => {
          // load only the form info so that we know if we have to unclaim the form or not
          await this.handleUnclaim(true);
          callback();
        }).catch(() => false);
      }
    },

    async beforeRouteEnter(to, from, next) {
      // Exception handled by interceptor
      await loadData({ to });
      next();
    },

    async beforeRouteUpdate(to, from, next) {
      await loadData.apply(this, {
        hasToLoadEntity: false
      });
      next();
    },

    beforeRouteLeave(to, from, next) {
      const leave = async () => {
        this.destroyEvents();
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
      this.formComponent = this.viewingFormInfo.definition.name_key;
      this.setupTabPanes();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';
  @import '~@sass/base/helpers';

  .process-form {
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
