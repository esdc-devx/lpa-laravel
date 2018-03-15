<template>
  <div class="user-create content">
    <h2>{{ trans('navigation.admin_user_create') }}</h2>
    <el-form :model="form" ref="form" label-width="30%" @submit.native.prevent  :disabled="isFormDisabled">
      <el-form-item label="Name" for="name" :class="['is-required', {'is-error': verrors.collect('name').length }]" prop="name">
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

      <el-form-item label="Organizational Unit" for="organizationUnits" prop="organization_units">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          :disabled="organizationUnits.length <= 1"
          v-model="form.organization_units"
          v-validate="''"
          id="organizationUnits"
          name="organizationUnits"
          valueKey="name"
          multiple>
          <el-option
            v-for="item in organizationUnits"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item class="form-footer">
        <el-button :disabled="isFormPristine || isFormDisabled" :loading="isSubmitting" type="primary" @click="submit()">Create</el-button>
        <el-button :disabled="isFormDisabled" @click="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '../../helpers/event-bus.js';
  import FormError from '../../components/forms/error.vue';
  import FormUtils from '../../mixins/form/utils.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-create',

    mixins: [ FormUtils ],

    components: { FormError },

    computed: {
      ...mapGetters({
        language: 'language',
        organizationUnits: `${namespace}/organizationUnits`
      }),

      nameRules() {
        return {
          required: true,
          in: this.inUserList
        };
      }
    },

    beforeRouteLeave (to, from, next) {
      this.isFormDisabled = true;
      next();
    },

    data() {
      return {
        isUserInfoLoading: true,
        isSubmitting: false,
        isFormDisabled: false,
        form: {
          name: '',
          username: '',
          organization_units: []
        },
        inUserList: []
      }
    },

    methods: {
      ...mapActions({
        searchUser: `${namespace}/searchUser`,
        createUser: `${namespace}/createUser`,
        loadUserCreateInfo: `${namespace}/loadUserCreateInfo`
      }),

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      submit() {
        this.isSubmitting = true;
        this.$validator.validateAll().then(result => {
          if (result) {
            this.create();
            return;
          }
          this.isSubmitting = false;
          this.focusOnError();
        });
      },

      manageBackendErrors(errors) {
        let fieldBag;
        for (let fieldName in errors) {
          fieldBag = errors[fieldName];
          for (let j = 0; j < fieldBag.length; j++) {
            this.verrors.add({field: fieldName === 'username' ? 'name' : fieldName, msg: fieldBag[j]})
          }
        }
        this.focusOnError();
      },

      async querySearchAsync(queryString, callback) {
        let users;
        try {
          users = await this.search(queryString);
          this.inUserList = _.map(users, 'name');
          callback(users);
        } catch(e) {
          this.$notify(`[user-create][querySearchAsync] ${e}`);
        }
      },

      handleSelect(item) {
        this.form.username = item.username;
      },

      // Navigation
      async create() {
        let response;
        try {
          response = await this.createUser({
            username: this.form.username,
            organization_units: this.form.organization_units
          });
          this.isSubmitting = false;
          this.notifySuccess(`${this.form.name} has been created.`);
          this.resetForm();
        } catch(e) {
          this.isSubmitting = false;
          this.manageBackendErrors(e.response.data.errors);
        }
      },

      goBack: _.throttle(function() {
        this.$router.push(`/${this.language}/admin/users`);
      }, 500)
    },

    async created() {
      EventBus.$on('Store:languageUpdate', async () => {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.resetErrors();

        this.isUserInfoLoading = true;
        await this.loadUserCreateInfo();
        this.isUserInfoLoading = false;
      });

      await this.loadUserCreateInfo();
      EventBus.$emit('App:ready');
      this.isUserInfoLoading = false;
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
