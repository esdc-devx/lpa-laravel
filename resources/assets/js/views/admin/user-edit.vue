<template>
  <div class="user-edit content" v-loading="isLoading">
    <h2>{{ trans('navigation.admin_user_edit') }}</h2>

    <el-form ref="form" @submit.native.prevent label-width="30%">
      <el-form-item label="Name" for="name" :class="{'is-required': nameRules.required, 'is-error': verrors.has('name') }">
        <el-autocomplete
          id="name"
          name="name"
          ref="name"
          popper-class="userAutocomplete"
          v-validate="nameRules"
          v-model="user.name"
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
        <span v-show="verrors.has('name')" class="el-form-item__error">{{ verrors.first('name') }}</span>
      </el-form-item>
      <el-form-item label="Organizational Unit" for="organizationUnits" :class="{'is-error': verrors.has('organizationUnits') }">
        <el-select
          v-model="user.organization_units"
          id="organizationUnits"
          name="organizationUnits"
          valueKey="name"
          multiple>
          <el-option
            v-for="item in allOrganizationUnits"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <span v-show="verrors.has('organizationUnits')" class="el-form-item__error">{{ verrors.first('organizationUnits') }}</span>
      </el-form-item>
      <el-form-item label="Email" for="email" :class="{'is-required': emailRules.required, 'is-error': verrors.has('email') }">
        <el-input
          id="email"
          name="email"
          v-model="user.email"
          v-validate="emailRules">
        </el-input>
        <span v-show="verrors.has('email')" class="el-form-item__error">{{ verrors.first('email') }}</span>
      </el-form-item>
      {{user}}
      <el-form-item>
        <el-button type="primary" @click.prevent="submit()">Save</el-button>
        <el-button @click.prevent="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>

  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../components/event-bus.js';

  import LoadStatus from '../../store/load-status-constants';

  let namespace = 'users';

  export default {
    name: 'UserEdit',

    computed: {
      ...mapGetters({
        language: 'language',
        viewingUser: `${namespace}/viewing`,
        allOrganizationUnits: 'users/organizationUnits'
      }),
      nameRules() {
        return {
          required: true,
          in: this.inUserList
        };
      },
      emailRules() {
        return {
          required: true,
          email: true
        };
      }
    },

    data() {
      return {
        isLoading: true,
        user: {},
        selectedOrganizationUnits: [],
        inUserList: []
      }
    },

    methods: {
      ...mapActions({
        searchUser: 'users/searchUser',
        loadViewingUser: 'users/loadViewingUser',
        loadUserCreateInfo: 'users/loadUserCreateInfo'
        // @todo: loadUserEditInfo: 'users/loadUserEditInfo'
      }),

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      submit() {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.update();
            this.notifySuccess(`<b>${this.name}</b> has been created.`);
            this.resetForm();
            return;
          }
          document.querySelectorAll('.is-error input')[0].focus();
        });
      },

      resetForm() {
        this.$refs.form.resetFields();
        this.$nextTick(() => {
          this.$validator.reset();
        });
      },

      update() {
        // @todo: call backend with form data
        // this.updateUser({username: this.user.username}).then(() => {
        //   this.notifySuccess(`${this.name} has been created.`);
        //   this.resetForm();
        // })
        // .catch(e => {
        //   this.errors = e.response.data.errors;
        //   this.focusOnError();
        // });
      },

      querySearchAsync(queryString, callback) {
        var users = this.search(queryString).then(users => {
          this.inUserList = _.map(users, 'name');
          // var results = queryString ? _.filter(users, this.createFilter(queryString)) : users;

          callback(users);
        });
      },

      // createFilter(queryString) {
      //   return item => {
      //     return (item.fullname.toLowerCase().indexOf(queryString.toLowerCase()) === 0) ||
      //            (item.username.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      //   };
      // },

      handleSelect(item) {
        this.$validator.reset();
      },

      loadUser() {
        let id = this.$route.params.id;
        return this.loadViewingUser(id);
      },

      goBack() {
        this.$router.push(`/${this.language}/admin/users`);
      }
    },

    created() {
      EventBus.$emit('App:ready');
      this.loadUser().then(this.loadUserCreateInfo().then(() => {
        this.user = Object.assign({}, this.viewingUser);
        this.user.organization_units = _.map(this.viewingUser.organization_units, 'id');
        this.isLoading = false;
      }));
    }
  };
</script>

<style lang="scss">
  .user-edit {
    width: 800px;
    margin: 0 auto;
    h2 {
      text-align: center;
    }

    .el-form {
      width: 100%;
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
