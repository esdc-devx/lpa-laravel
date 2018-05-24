<template>
  <div class="project-process content">
    <el-row>
      <el-col>
        <el-card shadow="never" class="process-info">
        <!-- get process info from api call instead on the project -->
          <dl>
            <dt>{{ trans('entities.process.id') }}</dt>
            <dd>{{ process.id | LPANumFilter }}</dd>
          </dl>
          <dl>
            <dt>{{ trans(`entities.process.current`) }}</dt>
            <dd>{{ process.state.name }}</dd>
          </dl>
          <dl>
            <dt>{{ trans(`entities.process.started_by`) }}</dt>
            <dd>TODO</dd>
          </dl>
          <dl>
            <dt>{{ trans(`entities.process.started_on`) }}</dt>
            <dd>{{ process.created_at }}</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated_by') }}</dt>
            <dd>TODO</dd>
          </dl>
          <dl>
            <dt>{{ trans('entities.general.updated_at') }}</dt>
            <dd>{{ process.updated_at }}</dd>
          </dl>
          <div class="controls">
            <!-- @todo: #LPA-4906 -->
            <!-- <el-button size="small" type="danger" plain>Cancel Process<i class="el-icon-close"></i></el-button> -->
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

  let namespace = 'projects';

  export default {
    name: 'project-process',

    computed: {
      ...mapGetters({
        language: 'language',
        viewingProject: `${namespace}/viewing`
      }),

      process() {
        return this.project.current_process;
      }
    },

    data() {
      return {
        project: {
          organizational_unit: {
            director: {}
          },
          current_process: {
            definition: {},
            state: {}
          },
          state: {},
          created_by: {},
          updated_by: {}
        }
      };
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadProject: `${namespace}/loadProject`
      }),

      async triggerLoadProject() {
        this.showMainLoading();
        let projectId = this.$route.params.projectId;
        await this.loadProject(projectId);
        this.project = Object.assign({}, this.viewingProject);
        this.hideMainLoading();
      },

      async onLanguageUpdate() {
        await this.triggerLoadProject();
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
      this.triggerLoadProject();
    }
  };
</script>

<style lang="scss">
  @import '../../../sass/abstracts/vars';

  .project-process {
    margin: 0 auto;

    .process-info {
      h2 {
        margin: 0;
        display: inline-block;
      }
      .el-card__body {
        display: flex;
        flex-flow: wrap;
        > * {
          display: flex;
          flex-direction: column;
          flex: 1;
          justify-content: flex-start;
          margin-bottom: 0;
        }
      }
      dl {
        padding-right: 20px;
        margin-top: 0px;
        box-sizing: border-box;
        dt {
          font-weight: bold;
        }
        dd {
          margin: 0;
          margin-top: 5px;
        }
      }

      .controls {
        margin-bottom: 0;
        align-items: flex-end;
        .el-button {
          font-weight: bold;
          i {
            font-weight: bold;
            margin-left: 10px;
          }
        }
        .el-button + .el-button {
          margin-left: 5px;
        }
      }
    }
  }
</style>
