<template>
  <div class="user-create content">
    <h2>{{ trans('navigation.admin_user_create') }}</h2>
    <el-form ref="form" @submit.native.prevent>
      <el-form-item label="Name" for="name" :class="{'is-required': nameRules.required, 'is-error': verrors.collect('name').length }">
        <el-autocomplete
          id="name"
          name="name"
          ref="name"
          popper-class="userAutocomplete"
          v-validate="nameRules"
          v-model="name"
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
        <span v-for="error in verrors.collect('name')" :key="error.id" class="el-form-item__error">{{ error }}</span>
      </el-form-item>

      <el-form-item label="Organizational Unit" for="organizationUnits" :class="{'is-error': verrors.has('organizationUnits') }">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          :disabled="organizationUnits.length <= 1"
          v-model="organization_units"
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
        <span v-for="error in verrors.collect('organizationUnits')" :key="error.id" class="el-form-item__error">{{ error }}</span>
      </el-form-item>

      <el-form-item class="form-footer">
        <el-button type="primary" @click="submit()">Create</el-button>
        <el-button @click.prevent="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '../../components/event-bus.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-create',

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

    data() {
      return {
        isUserInfoLoading: true,
        name: '',
        username: '',
        organization_units: [],
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
        this.$validator.validateAll().then(result => {
          if (result) {
            this.create();
            return;
          }
          this.focusOnError();
        });
      },

      resetForm() {
        this.$refs.form.resetFields();
        this.$nextTick(() => {
          this.$validator.reset();
        });
      },

      focusOnError() {
        this.$nextTick(() => {
          if (document.querySelectorAll('.is-error input')[0]) {
            document.querySelectorAll('.is-error input')[0].focus();
          }
        })
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

      querySearchAsync(queryString, callback) {
        var users = this.search(queryString).then(users => {
          this.inUserList = _.map(users, 'name');

          callback(users);
        });
      },

      handleSelect(item) {
        this.username = item.username;
        this.$validator.reset();
      },

      // Navigation
      create() {
        this.createUser({ username: this.username, organization_units: this.organization_units })
          .then(() => {
            this.notifySuccess(`${this.name} has been created.`);
            this.resetForm();
          })
          .catch(e => {
            this.manageBackendErrors(e.response.data.errors);
          });
      },

      goBack() {
        this.$router.push(`/${this.language}/admin/users`);
      }
    },

    created() {
      this.loadUserCreateInfo().then(() => {
        EventBus.$emit('App:ready');
        this.isUserInfoLoading = false;
      })
    }
  };
</script>

<style lang="scss">
  .user-create {
    width: 800px;
    margin: 0 auto;
    h2 {
      text-align: center;
    }

    .el-select {
      width: 75%;
    }
  }
  .el-autocomplete-suggestion.userAutocomplete {
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
