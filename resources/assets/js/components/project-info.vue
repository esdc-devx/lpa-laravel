<template>
  <div class="project-info">
    <info-box>
      <div slot="header">
        <h2><i class="el-icon-lpa-projects"></i>{{ projectProp.name }}</h2>
        <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
          <el-button-wrap
            :disabled="!rights.canEdit"
            @click.native="edit()"
            :tooltip="trans('components.tooltip.edit_project')"
            size="mini"
            icon="el-icon-lpa-edit"
            plain />
          <el-button-wrap
            :disabled="!rights.canDelete"
            @click.native="deleteProjectConfirm()"
            :tooltip="trans('components.tooltip.delete_project')"
            type="danger"
            size="mini"
            icon="el-icon-lpa-delete"
            plain />
        </div>
      </div>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ projectProp.id | LPANumFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.status') }}</dt>
        <dd>{{ projectProp.state.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.process.current') }}</dt>
        <dd>{{ projectProp.current_process ? projectProp.current_process.definition.name : trans('entities.general.na') }}</dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.general.organizational_units') }}</dt>
        <dd>{{ projectProp.organizational_unit.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.director') }}</dt>
        <dd>{{ projectProp.organizational_unit.director.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.created') }}</dt>
        <dd>{{ projectProp.created_by.name }}</dd>
        <dd>{{ projectProp.created_at }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd>{{ projectProp.updated_by.name }}</dd>
        <dd>{{ projectProp.updated_at }}</dd>
      </dl>
    </info-box>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  import InfoBox from '@components/info-box.vue';
  import ElButtonWrap from '@components/el-button-wrap.vue';

  let namespace = 'projects';

  export default {
    name: 'project-info',

    components: { InfoBox, ElButtonWrap },

    props: [ 'project' ],

    data() {
      return {
        rights: {
          canEdit: false,
          canDelete: false
        }
      };
    },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole'
      }),

      projectProp() {
        return this.project;
      }
    },

    methods: {
      ...mapActions({
        deleteProject: `${namespace}/delete`,
        canEditProject: `${namespace}/canEditProject`,
        canDeleteProject: `${namespace}/canDeleteProject`
      }),

      edit() {
        this.$router.push(`${this.project.id}/edit`);
      },

      deleteProjectConfirm() {
        this.confirmDelete({
          title: this.trans('components.notice.title.delete_project'),
          message: this.trans('components.notice.message.delete_project')
        }).then(async () => {
          try {
            await this.deleteProject(this.project.id);
            this.notifySuccess({
              message: this.trans('components.notice.message.project_deleted')
            });
            this.goToParentPage();
          } catch (e) {
            // Exception handled by interceptor
            if (!e.response) {
              throw e;
            }
          }
        }).catch(() => false);
      },

      loadPermissions() {
        let projectId = this.$route.params.projectId;
        axios.all([
          this.canEditProject(projectId),
          this.canDeleteProject(projectId)
        ]).then((canEdit, canDelete) => {
          this.rights.canEdit = canEdit;
          this.rights.canDelete = canDelete;
        });
      }
    },

    created() {
      this.loadPermissions();
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project-info {
    .info-box {
      h2 {
        position: relative;
        margin-top: 0;
        margin-bottom: 0;
        display: inline-block;
        padding-left: 34px;
        i {
          @include svg(projects, $--color-primary);
          width: 24px;
          position: absolute;
          left: 0;
          top: 50%;
          transform: translateY(-50%);
        }
      }
      dl {
        flex-basis: 25%;
        max-width: 25%; // Patch for IE11. See https://github.com/philipwalton/flexbugs/issues/3#issuecomment-69036362
      }

      .el-card__header > div {
        display: flex;
        justify-content: space-between;

        .controls {
          display: flex;
          align-items: center;
          float: right;
          margin-bottom: 0;
          button {
            &:hover, &:focus {
              i.el-icon-lpa-delete {
                @include svg(delete, $--color-white);
              }
            }
            &:disabled {
              &.el-button--danger {
                background-color: $--color-danger-lighter;
                i.el-icon-lpa-delete {
                  @include svg(delete, $--color-danger-light);
                }
              }
              i.el-icon-lpa-edit {
                @include svg(edit, $--color-text-placeholder);
              }
            }
            i {
              @include size(12px);
            }
          }
        }
      }
    }
  }
</style>
