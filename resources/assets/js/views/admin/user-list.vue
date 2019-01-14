<template>
  <div class="content user-list">
    <div class="controls">
      <el-button @click="goToPage('admin-user-create')">{{ trans('pages.user_list.create_user') }}</el-button>
    </div>
    <entity-data-table
      :data="all"
      :attributes="dataTableAttributes.users"
      @rowClick="onUserRowClick"
    />
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('users/loadAll')
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
    name: 'admin-user-list',

    extends: Page,

    components: { EntityDataTable },

    computed: {
      ...mapState('users', [
        'all'
      ]),

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
  .user-list {
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
    }
    .el-table__row {
      cursor: pointer;
    }

    .el-tag {
      height: auto;
      white-space: normal;
      margin-right: 4px;
      margin-top: 2px;
      margin-bottom: 2px;
    }
  }
</style>
