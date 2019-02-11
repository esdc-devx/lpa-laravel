<template>
  <div class="infobox-learning-product">
    <el-card-wrap
      icon="el-icon-lpa-learning-product"
      :headerTitle="learningProduct.name"
    >
      <template slot="controls" v-if="hasRole('owner') || hasRole('admin')">
        <el-control-wrap
          componentName="el-button"
          :disabled="!canEdit"
          :tooltip="trans('components.tooltip.edit_learning_product')"
          size="mini"
          icon="el-icon-lpa-edit"
          @click.native="edit()"
        />
        <el-control-wrap
          componentName="el-button"
          :disabled="!canDelete"
          :tooltip="trans('components.tooltip.delete_learning_product')"
          type="danger"
          size="mini"
          icon="el-icon-lpa-delete"
          plain
          @click.native="onDeleteConfirm()"
        />
      </template>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ learningProduct.id | LPANumFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.type') }}</dt>
        <dd>{{ learningProduct | learningProductTypeSubTypeFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.status') }}</dt>
        <dd>{{ learningProduct.state ? learningProduct.state.name : trans('entities.general.na') }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.process.current') }}</dt>
        <dd>{{ learningProduct.current_process ? learningProduct.current_process.definition.name : trans('entities.general.na') }}</dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.general.organizational_units') }}</dt>
        <dd>{{ learningProduct.organizational_unit.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.parent_project') }}</dt>
        <dd><router-link :to="`/${language}/projects/${learningProduct.project_id}`">{{ learningProduct.project_id | LPANumFilter }}</router-link></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.manager') }}</dt>
        <dd>{{ learningProduct.manager.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.primary_contact') }}</dt>
        <dd>{{ learningProduct.primary_contact.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.created') }}</dt>
        <dd v-audit:created="learningProduct"></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd v-audit:updated="learningProduct"></dd>
      </dl>
    </el-card-wrap>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import ElCardWrap from '@components/el-card-wrap';
import ElControlWrap from '@components/el-control-wrap';

export default {
  name: 'infobox-learning-product',

  components: { ElCardWrap, ElControlWrap },

  props: {
    learningProduct: {
      type: Object,
      required: true
    }
  },

  computed: {
    ...mapState([
      'language'
    ]),

    ...mapGetters({
      hasRole: 'entities/users/hasRole'
    }),

    canEdit() {
      return this.learningProduct.permissions.canEdit;
    },

    canDelete() {
      return this.learningProduct.permissions.canDelete;
    }
  },

  methods: {
    edit() {
      this.$router.push(`${this.learningProduct.id}/edit`);
    },

    onDeleteConfirm() {
      this.confirmDelete({
        title: this.trans('components.notice.title.delete_learning_product'),
        message: this.trans('components.notice.message.delete_learning_product')
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

  .infobox-learning-product {
    h2 i {
      // override default icon color
      @include svg(learning-product, $--color-primary);
    }
  }
</style>
