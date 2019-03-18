<template>
  <div>
    <el-card shadow="never">
      <span slot="header">
        <h2><i class="el-icon-lpa-edit"></i>{{ trans('base.navigation.admin_user_edit') }}</h2>
      </span>

      <el-form label-position="top" :disabled="isFormDisabled">
        <el-form-item :label="$tc('entities.general.organizational_units', 2)" for="organizationalUnits">
          <el-select-wrap
            v-model="form.organizational_units"
            name="organizational_units"
            :data-vv-as="$tc('entities.general.organizational_units', 2)"
            v-validate="''"
            :options="organizationalUnits"
            multiple
          />
        </el-form-item>

        <el-form-item :label="trans('entities.user.roles')" for="roles">
          <el-select-wrap
            v-model="form.roles"
            name="roles"
            :data-vv-as="trans('entities.user.roles')"
            v-validate="''"
            :options="roles"
            multiple
            sorted
          />
        </el-form-item>

        <el-form-item class="form-footer">
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

  import User from '@/store/models/User';

  const loadData = async ({ to }) => {
    const { userId } = to ? to.params : this;
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/users/loadEditInfo', userId)
    );

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
    name: 'user-edit',

    extends: Page,

    inject: ['$validator'],

    mixins: [ FormUtils ],

    components: { ElSelectWrap },

    computed: {
      ...mapState('entities/users', [
        'organizationalUnits',
        'roles'
      ]),

      viewing() {
        return User.find(this.userId);
      },
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
          organizational_units: [],
          roles: []
        }
      }
    },

    methods: {
      ...mapActions('entities/users', [
        '$$update'
      ]),

      loadData,

      search(name) {
        return this.searchUser(name);
      },

      // Form handlers
      onSubmit() {
        this.submit(this.handleUpdate);
      },

      async handleUpdate() {
        await this.$$update(this.form);
        this.isSubmitting = false;
        this.notifySuccess({
          message: this.trans('components.notice.message.user_updated')
        });
        this.goToParentPage();
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData({ to });
      next();
    },

    created() {
      this.form.id = this.viewing.id;
      // replace our internal organizational_units with only the ids
      // since ElementUI only need ids to populate the selected options
      this.form.organizational_units = _.map(this.viewing.organizational_units, 'id');
      this.form.roles = _.map(this.viewing.roles, 'id');
    }
  };
</script>

<style lang="scss">

</style>
