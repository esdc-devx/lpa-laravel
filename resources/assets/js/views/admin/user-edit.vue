<template>
  <div class="user-edit content">
    <el-card shadow="never">
      <span slot="header">
        <h2>{{ trans('base.navigation.admin_user_edit') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item :label="trans('entities.user.username')" for="username">
          <el-input name="username" v-model="form.user.username" disabled></el-input>
        </el-form-item>

        <el-form-item :label="trans('entities.general.name')" for="name">
          <el-input name="name" v-model="form.user.name" disabled></el-input>
        </el-form-item>

        <el-form-item :label="trans('entities.user.email')" for="email">
          <el-input name="email" v-model="form.user.email" disabled></el-input>
        </el-form-item>

        <el-form-item :label="trans('entities.general.created')" for="created_at">
          <el-input name="created_at" v-model="form.user.created_at" disabled></el-input>
        </el-form-item>

        <el-form-item :label="trans('entities.general.updated')" for="updated_at">
          <el-input name="updated_at" v-model="form.user.updated_at" disabled></el-input>
        </el-form-item>

        <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits">
          <el-select-wrap
            v-model="form.user.organizational_units"
            :isLoading="isUserInfoLoading"
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
            :isLoading="isUserInfoLoading"
            name="roles"
            :data-vv-as="trans('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
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
  import { mapGetters, mapActions } from 'vuex';

  import EventBus from '@/event-bus.js';

  import ElSelectWrap from '@components/forms/el-select-wrap';

  import FormUtils from '@mixins/form/utils.js';
  import PageUtils from '@mixins/page/utils.js';
  import HttpStatusCodes from '@axios/http-status-codes';

  let namespace = 'users';

  export default {
    name: 'user-edit',

    inject: ['$validator'],

    mixins: [ FormUtils, PageUtils ],

    components: { ElSelectWrap },

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
        updateUser: `${namespace}/update`,
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
        await this.updateUser({
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

      async triggerLoadUserInfo() {
        this.isUserInfoLoading = true;
        let userId = this.$route.params.userId;
        try {
          await this.loadUserEditInfo(userId);
          EventBus.$emit('App:ready');
          this.form.user = Object.assign({}, this.viewingUser);
          // replace our internal organizational_units with only the ids
          // since ElementUI only need ids to populate the selected options
          this.form.user.organizational_units = _.map(this.viewingUser.organizational_units, 'id');
          this.form.user.roles = _.map(this.viewingUser.roles, 'id');
          this.isUserInfoLoading = false;
        } catch(e) {
          this.$router.replace(`/${this.language}/${HttpStatusCodes.NOT_FOUND}`);
        }
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

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.triggerLoadUserInfo();
      });
    },

    mounted() {
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .user-edit {
    margin: 0 auto;
    h2 {
      margin: 0;
      display: inline-block;
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
