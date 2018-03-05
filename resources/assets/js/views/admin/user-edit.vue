<template>
  <div class="user-edit content" v-loading="isLoading">
    <h2>{{ trans('navigation.admin_user_edit') }}</h2>

    <el-form ref="form" @submit.native.prevent label-width="30%">
      <el-form-item label="Username" for="username">
        <el-input v-model="form.user.username" disabled></el-input>
      </el-form-item>
      <el-form-item label="Name" for="name">
        <el-input v-model="form.user.name" disabled></el-input>
      </el-form-item>
      <el-form-item label="Email" for="email">
        <el-input v-model="form.user.email" disabled>
        </el-input>
      </el-form-item>
      <el-form-item label="Organizational Unit" for="organizationUnits">
        <el-select
          v-loading="isUserInfoLoading"
          element-loading-spinner="el-icon-loading"
          v-validate="''"
          v-model="form.user.organization_units"
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
      </el-form-item>
      <el-form-item>
        <el-button :disabled="!isFormDirty" :loading="isSaving" type="primary" @click.prevent="submit()">Save</el-button>
        <el-button @click.prevent="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../helpers/event-bus.js';

  let namespace = 'users';

  export default {
    name: 'user-edit',

    computed: {
      ...mapGetters({
        language: 'language',
        viewingUser: `${namespace}/viewing`,
        allOrganizationUnits: 'users/organizationUnits'
      }),

      // @todo: make FormUtils and put it there
      isFormDirty() {
        return Object.keys(this.vfields).some(key => this.vfields[key].dirty);
      }
    },

    data() {
      return {
        isLoading: true,
        isUserInfoLoading: false,
        isSaving: false,
        form: {
          user: {}
        },
        selectedOrganizationUnits: []
      }
    },

    methods: {
      ...mapActions({
        updateUser: 'users/updateUser',
        loadViewingUser: 'users/loadViewingUser',
        loadUserCreateInfo: 'users/loadUserCreateInfo'
        // @todo: loadUserEditInfo: 'users/loadUserEditInfo'
      }),

      search(name) {
        return this.searchUser(name);
      },

      focusOnError() {
        this.$nextTick(() => {
          if (document.querySelectorAll('.is-error input')[0]) {
            document.querySelectorAll('.is-error input')[0].focus();
          }
        });
      },

      // Form handlers
      submit() {
        this.isSaving = true;
        this.$validator.validateAll().then(result => {
          if (result) {
            this.update();
            return;
          }
          this.isSaving = false;
          this.focusOnError();
        });
      },

      async update() {
        let response;
        try {
          response = await this.updateUser({id: this.form.user.id, organization_units: this.form.user.organization_units});
          this.isSaving = false;
          this.notifySuccess(`<b>${this.form.user.name}</b> has been updated.`);
          this.goBack();
        } catch(e) {
          this.errors = e.response.data.errors;
          this.focusOnError();
        }
      },

      loadUser() {
        let id = this.$route.params.id;
        return this.loadViewingUser(id);
      },

      goBack() {
        this.$router.push(`/${this.language}/admin/users`);
      }
    },

    async created() {
      EventBus.$on('Store:languageUpdate', async () => {
        // since on submit the backend returns already translated error messages,
        // we need to reset the validator messages so that on next submit
        // the messages are in the correct language
        this.$validator.reset();
        this.isUserInfoLoading = true;
        await this.loadUserCreateInfo();
        this.isUserInfoLoading = false;
      });

      EventBus.$emit('App:ready');

      await this.loadUser();
      await this.loadUserCreateInfo();
      this.form.user = Object.assign({}, this.viewingUser);
      // replace our internal organization_units with only the ids
      // since ElementUI only need ids to populate the selected options
      this.form.user.organization_units = _.map(this.viewingUser.organization_units, 'id');
      this.isUserInfoLoading = false;
      this.isLoading = false;
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
