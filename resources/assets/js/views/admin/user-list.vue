<template>
  <div>
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-user"
          :headerTitle="trans('base.navigation.admin_user_list')"
        >
          <template slot="controls" v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="trans('pages.user_list.create_user')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage('user-create')"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="all"
          :attributes="dataTableAttributes.users"
          @rowClick="onUserRowClick"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import User from '@/store/models/User';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/users/loadAll')
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
    name: 'user-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    computed: {
      all() {
        return User.all();
      },

      dataTableAttributes: {
        get() {
          return {
            users: {
              id: {
                isColumn: false
              },
              name: {
                label: this.trans('entities.general.full_name'),
                minWidth: 20
              },
              email: {
                label: this.trans('entities.general.email'),
                minWidth: 20
              },
              organizational_units: {
                label: this.$tc('entities.general.organizational_units', 2),
                minWidth: 25,
                areFiltersSorted: true,
                isFilterable: true
              },
              roles: {
                label: this.trans('entities.user.roles'),
                minWidth: 16,
                areFiltersSorted: true,
                isFilterable: true
              },
              updated_at: {
                label: this.trans('entities.general.updated'),
                minWidth: 14
              },
              updated_by: {
                isColumn: false
              }
            }
          }
        }
      }
    },

    methods: {
      onUserRowClick(user) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/admin/users/${user.id}/edit`);
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    },

    // called when url params change, e.g: language
    async beforeRouteUpdate(to, from, next) {
      await loadData();
      next();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .user-list {
    h2 i {
      @include svg(user, $--color-primary);
    }
  }
</style>
