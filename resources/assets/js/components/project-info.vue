<template>
  <el-card shadow="never" class="project-info">
    <div slot="header">
      <h2>{{ projectProp.name }}</h2>
      <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
        <el-button class="el-icon-edit" :disabled="!rights.canEdit" size="mini" @click="edit"></el-button>
        <el-button class="el-icon-delete" :disabled="!rights.canDelete" type="danger" size="mini" @click="deleteProjectConfirm" plain></el-button>
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
  </el-card>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';

  let namespace = 'projects';

  export default {
    name: 'project-info',

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
        hasRole: 'users/hasRole'
      }),

      projectProp() {
        return this.project;
      }
    },

    methods: {
      ...mapActions({
        deleteProject: `${namespace}/deleteProject`,
        canEditProject: `${namespace}/canEditProject`,
        canDeleteProject: `${namespace}/canDeleteProject`
      }),

      edit() {
        this.$router.push(`${this.project.id}/edit`);
      },

      deleteProjectConfirm() {
        this.confirmDelete(
          this.trans('components.notice.delete_project', {
            name: this.project.name,
            id: this.$options.filters.LPANumFilter(this.project.id)
          }),
          async () => {
            await this.deleteProject(this.project.id);
            this.notifySuccess(this.trans('components.notice.deleted', { name: this.project.name }));
            this.$router.push(`/${this.language}/projects`);
          }
        );
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
  .project-info {
    h2 {
      margin: 0;
      display: inline-block;
    }
    .el-card__body {
      display: flex;
      flex-wrap: wrap;
    }
    dl {
      width: 25%;
      padding-right: 20px;
      margin-top: 0px;
      box-sizing: border-box;
      &:first-of-type {
        width: 100%;
      }
      dt {
        font-weight: bold;
      }
      dd {
        margin: 0;
        margin-top: 5px;
      }
      dd + dd {
        margin-top: 0;
      }
    }

    .el-card__header div {
      display: flex;
      justify-content: space-between;

      .controls {
        display: flex;
        align-items: center;
        float: right;
        margin-bottom: 0;
      }
    }
  }

</style>
