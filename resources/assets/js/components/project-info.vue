<template>
  <div class="project-info">
    <info-box>
      <div slot="header">
        <h2>{{ projectProp.name }}</h2>
        <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
          <el-button :disabled="!rights.canEdit" size="mini" @click="edit()"><i class="el-icon-lpa-edit"></i></el-button>
          <el-button :disabled="!rights.canDelete" type="danger" size="mini" @click="deleteProjectConfirm()" plain><i class="el-icon-lpa-delete"></i></el-button>
        </div>
      </div>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ projectProp.id | LPANumFilter }}</dd>
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

  let namespace = 'projects';

  export default {
    name: 'project-info',

    components: { InfoBox },

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
          message: this.trans('components.notice.message.delete_project', {
            name: this.project.name,
            id: this.$options.filters.LPANumFilter(this.project.id)
          })
        }).then(async () => {
          try {
            await this.deleteProject(this.project.id);
            this.notifySuccess({
              message: this.trans('components.notice.message.project_deleted')
            });
            this.$router.push(`/${this.language}/projects`);
          } catch(e) {
            this.$alert(
              this.trans('components.notice.message.already_deleted_project'),
              this.trans('components.notice.type.error'),
              {
                type: 'error',
                showClose: false,
                confirmButtonText: this.trans('base.actions.ok'),
                callback: action => {
                  this.$router.push(`/${this.language}/projects`);
                }
              }
            );
          }
        }).catch(() => false);
      }
    },

    async created() {
      let projectId = this.$route.params.projectId;
      this.rights.canEdit = await this.canEditProject(projectId);
      this.rights.canDelete = await this.canDeleteProject(projectId);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project-info {
    .info-box {
      h2 {
        margin-top: 0;
        margin-bottom: 0;
        display: inline-block;
      }
      dl {
        width: 25%;
        &:first-of-type {
          flex-basis: 100%;
        }
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
