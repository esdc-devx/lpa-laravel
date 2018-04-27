<template>
  <div class="project-view content">
    <el-row>
      <el-col :span="18">
        <el-card shadow="never" class="project-info">
          <div slot="header" class="clearfix">
            <h2>{{ project.name }}</h2>
            <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
              <el-button class="el-icon-edit" size="mini" @click="edit"></el-button>
              <el-button class="el-icon-delete" type="warning" size="mini" @click="deleteProjectConfirm"></el-button>
            </div>
          </div>
          <dl>
            <dt>{{ trans('entities.general.lpa_num') }}</dt>
            <dd>{{ project.id | LPANumFilter }}</dd>
          </dl>
          <dl>
            <dt>{{ $tc('entities.general.organizational_units') }}</dt>
            <dd>{{ project.organizational_unit.name }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.director') }}</dt>
            <dd>{{ project.organizational_unit.director.name }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.created_at') }}</dt>
            <dd>{{ project.created_at }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.created_by') }}</dt>
            <dd>{{ project.created_by.name }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated_at') }}</dt>
            <dd>{{ project.updated_at }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated_by') }}</dt>
            <dd>{{ project.updated_by.name }}</dd>
          </dl>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../event-bus.js';

  let namespace = 'projects';

  export default {
    name: 'project-view',

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        viewingProject: `${namespace}/viewing`
      })
    },

    data() {
      return {
        project: {
          organizational_unit: {
            director: {}
          },
          created_by: {},
          updated_by: {}
        }
      };
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: `${namespace}/loadProject`,
        deleteProject: `${namespace}/deleteProject`
      }),

      async triggerLoadProject() {
        this.showMainLoading();
        let id = this.$route.params.id;
        await this.loadProject(id);
        this.project = Object.assign({}, this.viewingProject);
        this.hideMainLoading();
      },

      edit() {
        this.$router.push(`${this.project.id}/edit`);
      },

      deleteProjectConfirm() {
        this.confirmDelete(this.trans('components.confirm.delete_project', {
          name: this.project.name,
          id: this.$options.filters.LPANumFilter(this.project.id)
        })).then(async () => {
          await this.deleteProject(this.project.id);
          this.notifySuccess(this.trans('components.notify.deleted', { name: this.project.name }));
          this.$router.push(`/${this.language}/projects`);
        });
      }
    },

    mounted() {
      EventBus.$emit('App:ready');
      this.triggerLoadProject();
    }
  };
</script>

<style lang="scss">
  @import '../../sass/abstracts/vars';
  .project-view {
    margin: 0 auto;

    .project-info {
      h2 {
        margin: 0;
        display: inline-block;
      }
      .el-card__body {
        display: inline-table;
        width: 100%;
        box-sizing: border-box;
      }
      dl {
        width: 25%;
        box-sizing: border-box;
        float: left;
        padding-right: 20px;
        margin-top: 0px;
        dt {
          font-weight: bold;
        }
        dd {
          margin: 0;
        }
      }

      .controls {
        float: right;
        .el-button + .el-button {
          margin-left: 5px;
        }
      }
    }

    .project-actions {
      margin-left: 20px;
      .el-card__header {
        padding: 0;
        button {
          padding: 18px 20px;
          width: 100%;
        }
      }
    }
  }
</style>
