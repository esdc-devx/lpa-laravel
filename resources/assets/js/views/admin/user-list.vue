<template>
  <div class="content user-list">
    <div class="controls">
      <el-button @click="goToPage('admin-user-create')">{{ trans('pages.user_list.create_user') }}</el-button>
    </div>
    <entity-data-tables
      entityType="user"
      :data="users"
      :attributes="dataTableAttributes.users"
    />
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import Page from '@components/page';
  import EntityDataTables from '@components/entity-data-tables.vue';

  let namespace = 'users';

  export default {
    name: 'admin-user-list',

    extends: Page,

    components: { EntityDataTables },

    computed: {
      ...mapGetters({
        users: `${namespace}/all`
      }),

      dataTableAttributes: {
        get() {
          return {
            users: {
              name: {
                label: this.trans('entities.general.full_name'),
                minWidth: 20
              },
              email: {
                label: this.trans('entities.user.email'),
                minWidth: 20
              },
              organizational_units: {
                label: this.$tc('entities.general.organizational_units', 2),
                minWidth: 25
              },
              roles: {
                label: this.trans('entities.user.roles'),
                minWidth: 16
              },
              updated_at: {
                label: this.trans('entities.general.updated'),
                minWidth: 14
              }
            }
          }
        }
      }
    },

    methods: {
      ...mapActions({
        loadUsers: `${namespace}/loadUsers`
      }),

      async loadData() {
        this.scrollToTop();
        await this.loadUsers();
      }
    },

    beforeRouteEnter(to, from, next) {
      next(vm => {
        vm.loadData();
      });
    },

    // called when url params change, e.g: language
    beforeRouteUpdate(to, from, next) {
      this.loadData().then(next);
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
