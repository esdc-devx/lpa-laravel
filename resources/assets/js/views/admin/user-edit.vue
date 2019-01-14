<template>
  <div class="user-edit content">
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('base.navigation.admin_user_edit') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits">
          <el-select-wrap
            v-model="form.user.organizational_units"
            name="organizational_units"
            :data-vv-as="$tc('entities.general.organizational_units', 2)"
            v-validate="''"
            :options="organizationalUnits"
            multiple
          />
        </el-form-item>

        <el-form-item :label="trans('entities.user.roles')" for="roles">
          <el-select-wrap
            v-model="form.user.roles"
            name="roles"
            :data-vv-as="trans('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
            sorted
          />
        </el-form-item>

        <el-form-item>
          <el-button :disabled="isFormDisabled" @click="goToParentPage()">{{ trans('base.actions.cancel') }}</el-button>
          <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSubmitting" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex';

  import Page from '@components/page';
  import ElSelectWrap from '@components/forms/el-select-wrap';

  import FormUtils from '@mixins/form/utils.js';

  export default {
    name: 'user-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElSelectWrap },

    computed: {
      ...mapState('users', [
        'viewing',
        'organizationalUnits',
        'roles'
      ])
    },

    props: {
      userId: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        form: {
          user: {}
        }
      }
    },

    methods: {
      ...mapActions('users', [
        'update',
        'loadEditInfo'
      ]),

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        await this.update({
          id: this.form.user.id,
          organizational_units: this.form.user.organizational_units,
          roles: this.form.user.roles
        });
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.user_updated')
        });
        this.goToParentPage();
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();
        // only reload the dropdowns, not the user
        await this.loadEditInfo(this.userId);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await store.dispatch('users/loadEditInfo', to.params.userId)
      next();
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await this.onLanguageUpdate();
      next();
    },

    created() {
      this.form.user = _.cloneDeep(this.viewing);
      // replace our internal organizational_units with only the ids
      // since ElementUI only need ids to populate the selected options
      this.form.user.organizational_units = _.map(this.viewing.organizational_units, 'id');
      this.form.user.roles = _.map(this.viewing.roles, 'id');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .user-edit {
    margin: 0 auto;
    h2 {
      position: relative;
      margin: 0;
      padding-left: 34px;
      display: inline-block;
      i {
        width: 24px;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
      }
    }

    .el-form {
      .el-form-item:last-of-type {
        float: right;
      }
      label {
        padding: 0;
        font-weight: bold;
        color: $--color-primary;
      }
      .el-autocomplete, .el-select {
        width: 100%;
      }
    }
  }

  .el-autocomplete-suggestion.userAutocomplete {
    .autocomplete-popper-inner-wrap {
      li {
        line-height: 20px;
        padding: 7px 10px;

        .value {
          text-overflow: ellipsis;
          overflow: hidden;
        }
        .link {
          font-size: 12px;
          color: #b4b4b4;
        }
      }
    }
  }
</style>
