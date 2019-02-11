<template>
  <div class="infobox-project">
    <el-card-wrap
      icon="el-icon-lpa-projects"
      :headerTitle="project.name"
    >
      <template slot="controls" v-if="hasRole('owner') || hasRole('admin')">
        <el-control-wrap
          componentName="el-button"
          :disabled="!canEdit"
          :tooltip="trans('components.tooltip.edit_project')"
          size="mini"
          icon="el-icon-lpa-edit"
          @click.native="edit()"
        />
        <el-control-wrap
          componentName="el-button"
          :disabled="!canDelete"
          :tooltip="trans('components.tooltip.delete_project')"
          type="danger"
          size="mini"
          icon="el-icon-lpa-delete"
          plain
          @click.native="onDeleteConfirm()"
        />
      </template>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ project.id | LPANumFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.status') }}</dt>
        <!--
          check for the state here since when we move to another page
          the project is refrehed and we might not have the property state
        -->
        <dd>{{ project.state ? project.state.name : trans('entities.general.none') }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.process.current') }}</dt>
        <dd>{{ project.current_process ? project.current_process.definition.name : trans('entities.general.none') }}</dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.general.organizational_units') }}</dt>
        <dd>{{ project.organizational_unit.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.organizational_unit.director') }}</dt>
        <dd>{{ project.organizational_unit.director.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.created') }}</dt>
        <dd v-audit:created="project"></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd v-audit:updated="project"></dd>
      </dl>
    </el-card-wrap>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  export default {
    name: 'infobox-project',

    components: { ElCardWrap, ElControlWrap },

    props: {
      project: {
        type: Object,
        required: true
      }
    },

    computed: {
      ...mapGetters({
        hasRole: 'entities/users/hasRole'
      }),

      canEdit() {
        return this.project.permissions.canEdit;
      },

      canDelete() {
        return this.project.permissions.canDelete;
      }
    },

    methods: {
      edit() {
        this.$router.push(`${this.project.id}/edit`);
      },

      onDeleteConfirm() {
        this.confirmDelete({
          title: this.trans('components.notice.title.delete_project'),
          message: this.trans('components.notice.message.delete_project')
        }).then(() => {
          this.$emit('delete');
        }).catch(() => false);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .infobox-project {
    h2 i {
      // override default icon color
      @include svg(projects, $--color-primary);
    }
  }
</style>
