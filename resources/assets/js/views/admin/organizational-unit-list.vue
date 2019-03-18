<template>
  <div>
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-organizational-unit"
          :headerTitle="trans('base.navigation.admin_organizational_unit_list')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="all"
          :attributes="dataTableAttributes.organizationalUnits"
          @rowClick="onRowClick"
          @formatData="onFormatData"
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
    name: 'organizational-unit-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap },

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
      loadData,

      onRowClick(organizationalUnit) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/admin/organizational-units/${organizationalUnit.id}/edit`);
      },

      onFormatData(normEntity) {
        normEntity.owner = normEntity.owner ? this.trans('base.actions.yes') : this.trans('base.actions.no');
      }
    },

    async beforeRouteEnter(to, from, next) {
      await loadData();
      next();
    }
  };
</script>

<style lang="scss">

</style>
