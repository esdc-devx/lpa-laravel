<template>
  <div class="user-edit content">
    <h2>{{ trans('navigation.admin_user_edit') }}</h2>

    <el-form label-width="30%" :disabled="isFormDisabled">
      <el-form-item label="Username" for="username">
        <el-input v-model="form.user.username" disabled></el-input>
      </el-form-item>

      <el-form-item label="Name" for="name">
        <el-input v-model="form.user.name" disabled></el-input>
      </el-form-item>

      <el-form-item label="Email" for="email">
        <el-input v-model="form.user.email" disabled></el-input>
      </el-form-item>

      <el-form-item label="Organizational Unit(s)" for="organizationalUnits">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="''"
          v-model="form.user.organizational_units"
          id="organizationalUnits"
          name="organizationalUnits"
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

      <el-form-item label="Role(s)" for="roles">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="''"
          :value="form.user.roles"
          id="roles"
          name="roles"
          valueKey="name"
          multiple
          @change="handleRolesActions">
          <el-option
            v-for="item in roles"
            :key="item.id"
            :label="item.name"
            :value="item.id"
            :disabled="isViewingUserSysAdmin && item.name === 'admin'">
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item>
        <el-button :disabled="!isFormDirty || isFormDisabled" :loading="isSaving" type="primary" @click="onSubmit()">Save</el-button>
        <el-button :disabled="isFormDisabled" @click="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';
  import FormUtils from '../../mixins/form/utils.js';

  let namespace = 'users';

  export default {
    name: 'user-edit',

    mixins: [ FormUtils ],

    computed: {
      ...mapGetters({
        language: 'language',
        viewingUser: `${namespace}/viewing`,
        organizationalUnits: `${namespace}/organizationalUnits`,
        roles: `${namespace}/roles`,
        isViewingUserSysAdmin: `${namespace}/isViewingUserSysAdmin`
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
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        updateUser: `${namespace}/updateUser`,
        loadUserEditInfo: `${namespace}/loadUserEditInfo`
      }),

      handleRolesActions(newVal) {
        // fail safe in case the viewingUser has not yet been loaded
        if (!this.viewingUser || !this.viewingUser.roles) {
          return;
        }
        let adminRole = _.find(this.viewingUser.roles, ['unique_key', 'admin']);
        // disallow removing the admin role on the sys.admin
        if (this.isViewingUserSysAdmin && ( !newVal.length || !newVal.includes(adminRole.id) )) {
          this.$alert('You cannot remove administrator role on the system administrator.', {
            confirmButtonText: 'OK'
          });
          return;
        }
        this.form.user.roles = newVal;
      },

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
          this.notifySuccess(`<b>${this.form.user.name}</b> has been updated.`);
          this.goBack();
        } catch({ response }) {
          this.isSaving = false;
          this.errors = response.data.errors;
          this.focusOnError();
        }
      },

      goBack() {
        this.$helpers.throttleAction(() => {
          this.$router.push(`/${this.language}/admin/users`);
        });
      },

      async triggerLoadUserInfo() {
        this.showMainLoading();
        this.isUserInfoLoading = true;
        let id = this.$route.params.id;
        await this.loadUserEditInfo(id);
        this.form.user = Object.assign({}, this.viewingUser);
        // replace our internal organizational_units with only the ids
        // since ElementUI only need ids to populate the selected options
        this.form.user.organizational_units = _.map(this.viewingUser.organizational_units, 'id');
        this.form.user.roles = _.map(this.viewingUser.roles, 'id');
        this.isUserInfoLoading = false;
        this.hideMainLoading();
      }
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', async () => {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.$validator.reset();
        // only reload the dropdowns, not the user
        this.isUserInfoLoading = true;
        let id = this.$route.params.id;
        await this.loadUserEditInfo(id);
        this.isUserInfoLoading = false;
      });
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
