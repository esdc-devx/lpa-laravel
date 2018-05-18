<template>
  <div class="project-view content">
    <el-row>
      <el-col>
        <entity-bar-cta :data="project" type="project"/>
      </el-col>
    </el-row>
    <el-row :gutter="20" class="equal-height">
      <el-col :span="canBeVisible ? 18 : 24">
        <project-info :project="project"/>
      </el-col>
      <el-col :span="6" v-if="canBeVisible">
        <el-card shadow="never" class="project-actions">
          <div slot="header" class="clearfix">
            <h3>{{ trans('entities.process.start') }}</h3>
          </div>
          <ul class="project-actions-list">
            <li>
              <el-button v-for="(process, index) in processes" :key="index" :disabled="!canStartProcess({projectId: $route.params.id, processId: process.name_key})" @click="startProcess(process.name_key)" plain>{{ process.name }}</el-button>
            </li>
          </ul>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

  import EntityBarCta from '../../components/entity-bar-cta.vue';
  import ProjectInfo from '../../components/project-info.vue';

  let namespace = 'projects';

  export default {
    name: 'project-view',

    components: { EntityBarCta, ProjectInfo },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole',
        viewingProject: `${namespace}/viewing`,
        processes: `${namespace}/processes`
      }),

      canBeVisible() {
        return this.hasRole('owner') || this.hasRole('admin');
      }
    },

    data() {
      return {
        project: {
          organizational_unit: {
            director: {}
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
        loadProject: `${namespace}/loadProject`,
        canStartProcess: `${namespace}/canStartProcess`
      }),

      startProcess(processNameKey) {
        // confirm the intention to start a process first
        this.confirmStart(
          this.trans('components.notice.start_process', {
            process_name: this.project.current_process.definition.name
          }),
          () => {
            // await this.startProcess(this.project.id);
            this.notifySuccess(this.trans('components.notice.started', { name: this.project.current_process.definition.name }));
            let id = this.$route.params.id;
            this.$router.push(`${id}/${processNameKey}`);
          }
        );
      },

      async triggerLoadProject() {
        this.showMainLoading();
        let id = this.$route.params.id;
        await this.loadProject(id);
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
  @import '../../../sass/abstracts/mixins/helpers';

  .project-view {
    margin: 0 auto;

    .project-actions {
      user-select: none;
      .el-card__header {
        background-color: #8cc63f;
        h3 {
          margin: 0;
          padding: 3px 0px;
          text-align: center;
          display: block;
          color: $--color-white;
        }
      }
      &-list {
        list-style: none;
        margin: 0;
        padding: 0;
        li {
          text-align: center;
          &:first-child button {
            // reset all before applying to specific
            border-radius: 0;
            border-top-left-radius: $--border-radius-base;
            border-top-right-radius: $--border-radius-base;
          }
          &:last-child button {
            // reset all before applying to specific
            border-radius: 0;
            border-bottom-left-radius: $--border-radius-base;
            border-bottom-right-radius: $--border-radius-base;
          }
          button {
            width: 100%;
            border-radius: 0;
          }
          @include quantity-query(to 1) {
            button {
              border-radius: $--border-radius-base;
            }
          }
        }
      }
    }
  }
</style>
