<template>
  <div>
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-process-notification"
          :headerTitle="trans('base.navigation.admin_process_notification_list')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="all"
          :attributes="dataTableAttributes.processNotifications"
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

  import ProcessNotification from '@/store/models/Process-Notification';

  const loadData = async () => {
    // we need to access the store directly
    // because at this point we may have entered the beforeRouteEnter hook
    // in which we don't have access to the this context yet

    let requests = [];
    requests.push(
      store.dispatch('entities/processNotifications/loadAll')
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
    name: 'process-notification-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap },

    computed: {
      all() {
        return ProcessNotification.all();
      },

      dataTableAttributes: {
        get() {
          return {
            processNotifications: {
              id: {
                isColumn: false
              },
              name_key: {
                label: this.trans('entities.general.name_key'),
                minWidth: 20
              },
              process_definition: {
                label: this.trans('entities.process.label'),
                areFiltersSorted: true,
                isFilterable: true,
                minWidth: 20
              },
              name: {
                label: this.trans('entities.general.name'),
                minWidth: 20
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
      onRowClick(processNotification) {
        this.scrollToTop();
        this.$router.push(`/${this.language}/admin/process-notifications/${processNotification.id}/edit`);
      },

      onFormatData(normEntity) {
        normEntity.name_key = normEntity.process_definition.name_key + '.' + normEntity.name_key;
        normEntity.process_definition = normEntity.process_definition.name;
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

</style>
