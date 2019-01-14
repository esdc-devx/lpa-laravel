<template>
  <div class="content organizational-list">
    <entity-data-table
      :data="all"
      :attributes="dataTableAttributes.organizationalUnits"
      @rowClick="onRowClick"
      @formatData="onFormatData"
    />
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';

  import OrganizationalUnit from '@/store/models/Organizational-Unit';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/organizationalUnits/loadAll')
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
    name: 'admin-organizational-unit-list',

    extends: Page,

    components: { EntityDataTable },

    computed: {
      all() {
        return OrganizationalUnit.all();
      },

      dataTableAttributes: {
        get() {
          return {
            organizationalUnits: {
              id: {
                isColumn: false
              },
              name_key: {
                label: this.trans('entities.general.name_key'),
                minWidth: 20
              },
              name: {
                label: this.trans('entities.general.name'),
                minWidth: 20
              },
              director: {
                label: this.trans('entities.organizational_unit.director'),
                minWidth: 20
              },
              email: {
                label: this.trans('entities.general.email'),
                minWidth: 20
              },
              owner: {
                label: this.trans('entities.organizational_unit.owner'),
                minWidth: 20,
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
      onRowClick(organizationalUnit) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/admin/organizational-units/${organizationalUnit.id}/edit`);
      },

      onFormatData(normEntity) {
        console.log(normEntity);
        normEntity.owner = normEntity.owner ? this.trans('base.actions.yes') : this.trans('base.actions.no');
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
  // @refactor: Move to its own file.
  .organizational-list {
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
