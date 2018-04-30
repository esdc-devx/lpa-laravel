<template>
  <div class="user-create content">
    <h2>{{ trans('base.navigation.admin_user_create') }}</h2>
    <el-form :model="form" ref="form" label-width="30%" @submit.native.prevent :disabled="isFormDisabled">
      <el-form-item :label="trans('entities.general.name')" for="name" :class="['is-required', {'is-error': verrors.collect('name').length }]" prop="name">
        <el-autocomplete
          id="name"
          name="name"
          ref="name"
          popper-class="name-autocomplete"
          v-validate="nameRules"
          v-model="form.name"
          :fetch-suggestions="querySearchAsync"
          :trigger-on-focus="false"
          valueKey="name"
          @select="handleSelect">
          <template slot-scope="props">
            <div class="autocomplete-popper-inner-wrap" :title="props.item.name">
              <div class="value">{{ props.item.name }}</div>
              <span class="link">{{ props.item.email }}</span>
            </div>
          </template>
        </el-autocomplete>
        <form-error v-for="error in verrors.collect('name')" :key="error.id">{{ error }}</form-error>
      </el-form-item>

      <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits" prop="organizational_units">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-model="form.organizational_units"
          v-validate="''"
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

      <el-form-item :label="trans('entities.general.roles')" for="roles" prop="roles">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          :disabled="roles.length <= 1"
          v-model="form.roles"
          v-validate="''"
          id="roles"
          name="roles"
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

      <el-form-item class="form-footer">
        <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSaving" type="primary" @click="onSubmit()">{{ trans('base.actions.create') }}</el-button>
        <el-button :disabled="isFormDisabled" @click="go(`/${language}/admin/users`)">{{ trans('base.actions.cancel') }}</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '../../event-bus.js';
  import FormError from '../../components/forms/error.vue';
  import FormUtils from '../../mixins/form/utils.js';
  import PageUtils from '../../mixins/page/utils.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-create',

    mixins: [ FormUtils, PageUtils ],

    components: { FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        organizationalUnits: `${namespace}/organizationalUnits`,
        roles: `${namespace}/roles`
      }),

      nameRules() {
        return {
          required: true,
          in: this.inUserList
        };
      }
    },

    data() {
      return {
        isUserInfoLoading: false,
        form: {
          name: '',
          username: '',
          organizational_units: [],
          roles: []
        },
        inUserList: []
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        searchUser: `${namespace}/searchUser`,
        createUser: `${namespace}/createUser`,
        loadUserCreateInfo: `${namespace}/loadUserCreateInfo`
      }),

      search(name) {
        return this.searchUser(name);
      },

      async querySearchAsync(queryString, callback) {
        let users;
        try {
          users = await this.search(queryString);
          this.inUserList = _.map(users, 'name');
          callback(users);
        } catch(e) {
          this.$notifyError('Unable to retrieve users.');
          this.$log.error(`[user-create][querySearchAsync] ${e}`);
        }
      },

      handleSelect(item) {
        this.form.username = item.username;
      },

      // Form handlers
      onSubmit() {
        this.submit(this.create);
      },

      async create() {
        try {
          await this.createUser(_.omit(this.form, 'name'));
          this.isSaving = false;
          this.notifySuccess(this.trans('components.notice.created', { name: this.form.name }));
          this.go(`/${this.language}/admin/users`);
        } catch({ response }) {
          this.isSaving = false;
          this.manageBackendErrors(response.data.errors);
        }
      },

      manageBackendErrors(errors) {
        let fieldBag;
        for (let fieldName in errors) {
          fieldBag = errors[fieldName];
          for (let j = 0; j < fieldBag.length; j++) {
            // since we are dealing with a username while sending to the backend,
            // we need to map the username to the name so that the name field has the error
            // and not the username field which doesn't exist
            this.verrors.add({field: fieldName === 'username' ? 'name' : fieldName, msg: fieldBag[j]});
          }
        }
        this.focusOnError();
      },

      async triggerLoadUserCreateInfo() {
        this.isUserInfoLoading = true;
        await this.loadUserCreateInfo();
        this.isUserInfoLoading = false;
      },

      async onLanguageUpdate() {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        await this.triggerLoadUserCreateInfo();
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

      this.showMainLoading();
      this.triggerLoadUserCreateInfo();
      this.hideMainLoading();
      this.autofocus('name');
    }
  };
</script>

<style lang="scss">
  .user-create {
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
  .el-autocomplete-suggestion.name-autocomplete {
    li {
      line-height: 20px;
      padding: 7px 10px;

      .autocomplete-popper-inner-wrap {
        overflow: hidden;
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
