<template>
  <div class="user-edit content">
    <h2>{{ trans('base.navigation.admin_user_edit') }}</h2>

    <el-form label-width="30%" :disabled="isFormDisabled">
      <el-form-item :label="trans('entities.user.username')" for="username">
        <el-input v-model="form.user.username" disabled></el-input>
      </el-form-item>

      <el-form-item :label="trans('entities.general.name')" for="name">
        <el-input v-model="form.user.name" disabled></el-input>
      </el-form-item>

      <el-form-item :label="trans('entities.user.email')" for="email">
        <el-input v-model="form.user.email" disabled></el-input>
      </el-form-item>

      <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits">
        <el-select
          id="organizationalUnits"
          name="organizationalUnits"
          :data-vv-as="$tc('entities.general.organizational_units', 2)"
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="''"
          v-model="form.user.organizational_units"
          valueKey="name"
          multiple>
          <el-option
            v-for="item in organizationalUnits"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item :label="trans('entities.user.roles')" for="roles">
        <el-select
          id="roles"
          name="roles"
          :data-vv-as="trans('entities.user.roles')"
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="''"
          v-model="form.user.roles"
          valueKey="name"
          multiple>
          <el-option
            v-for="item in roles"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item>
        <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSaving" type="primary" @click="onSubmit()">{{ trans('base.actions.save') }}</el-button>
        <el-button :disabled="isFormDisabled" @click="go(`/${language}/admin/users`)">{{ trans('base.actions.cancel') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';
  import FormUtils from '../../mixins/form/utils.js';
  import PageUtils from '../../mixins/page/utils.js';
  import HttpStatusCodes from '../../axios/http-status-codes';

  let namespace = 'users';

  export default {
    name: 'user-edit',

    mixins: [ FormUtils, PageUtils ],

    computed: {
      ...mapGetters({
        language: 'language',
        viewingUser: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`,
        roles: `${namespace}/roles`
      })
    },

    data() {
      return {
        isUserInfoLoading: false,
        form: {
          user: {}
        }
      }
    },

    methods: {
      ...mapActions({
        updateUser: `${namespace}/updateUser`,
        loadUserEditInfo: `${namespace}/loadUserEditInfo`
      }),

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.update);
      },

      async update() {
        try {
          await this.updateUser({
            id: this.form.user.id,
            organizational_units: this.form.user.organizational_units,
            roles: this.form.user.roles
          });
          this.isSaving = false;
          this.notifySuccess(this.trans('components.notice.updated', { name: this.form.user.name }));
          this.go(`/${this.language}/admin/users`);
        } catch({ response }) {
          if (response.status === HttpStatusCodes.FORBIDDEN) {
            this.notifyWarning(response.data.errors);
            this.isSaving = false;
            return;
          }
          this.errors = response.data.errors;
          this.focusOnError();
        }
      },

      async triggerLoadUserInfo() {
        this.isUserInfoLoading = true;
        let userId = this.$route.params.userId;
        await this.loadUserEditInfo(userId);
        this.form.user = Object.assign({}, this.viewingUser);
        // replace our internal organizational_units with only the ids
        // since ElementUI only need ids to populate the selected options
        this.form.user.organizational_units = _.map(this.viewingUser.organizational_units, 'id');
        this.form.user.roles = _.map(this.viewingUser.roles, 'id');
        this.isUserInfoLoading = false;
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();
        // only reload the dropdowns, not the user
        this.isUserInfoLoading = true;
        let userId = this.$route.params.userId;
        await this.loadUserEditInfo(userId);
        this.isUserInfoLoading = false;
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
      this.triggerLoadUserInfo();
    }
  };
</script>

<style lang="scss">
  .user-edit {
    margin: 0 auto;
    h2 {
      text-align: center;
    }

    .el-form {
      width: 800px;
      margin: 0 auto;
      .el-select {
        width: 75%;
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
