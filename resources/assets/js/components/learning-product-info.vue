<template>
  <div class="learning-product-info">
    <info-box>
      <div slot="header">
        <h2><i class="el-icon-lpa-learning-product"></i>{{ learningProduct.name }}</h2>
        <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
          <el-button :disabled="!rights.canEdit" size="mini" @click="edit()"><i class="el-icon-lpa-edit"></i></el-button>
          <el-button :disabled="!rights.canDelete" type="danger" size="mini" @click="deleteWrapper()" plain><i class="el-icon-lpa-delete"></i></el-button>
        </div>
      </div>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ learningProduct.id | LPANumFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.parent_project') }}</dt>
        <dd><router-link :to="'/' + language + '/projects/' + learningProduct.project_id">{{ learningProduct.project_id | LPANumFilter }}</router-link></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.type') }}</dt>
        <dd>{{ learningProduct.type.name, learningProduct.sub_type.name | learningProductTypeSubTypeFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.general.organizational_units') }}</dt>
        <dd>{{ learningProduct.organizational_unit.name }}</dd>
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
        <dd>{{ learningProduct.created_by.name }}</dd>
        <dd>{{ learningProduct.created_at }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd>{{ learningProduct.updated_by.name }}</dd>
        <dd>{{ learningProduct.updated_at }}</dd>
      </dl>
    </info-box>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import HttpStatusCodes from '@axios/http-status-codes';
import InfoBox from '@components/info-box.vue';
import PageUtils from '@mixins/page/utils.js';

let namespace = 'learningProducts';

export default {
  name: 'learning-product-info',

  components: { InfoBox },

  mixins: [ PageUtils ],

  props: {
    learningProduct: {
      type: Object,
      required: true
    }
  },

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
    })
  },

  methods: {
    ...mapActions({
      deleteLearningProduct: `${namespace}/delete`,
      canEditLearningProduct: `${namespace}/canEdit`,
      canDeleteLearningProduct: `${namespace}/canDelete`
    }),

    edit() {
      this.$router.push(`${this.learningProduct.id}/edit`);
    },

    deleteWrapper() {
      this.confirmDelete({
        title: this.trans('components.notice.title.delete_learning_product'),
        message: this.trans('components.notice.message.delete_learning_product')
      }).then(async () => {
        try {
          await this.deleteLearningProduct(this.learningProduct.id);
          this.notifySuccess({
            message: this.trans('components.notice.message.learning_product_deleted')
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

    async getAuthorizations() {
      this.rights.canEdit = await this.canEditLearningProduct(this.learningProduct.id);
      this.rights.canDelete = await this.canDeleteLearningProduct(this.learningProduct.id);
    }
  },

  created() {
    this.getAuthorizations();
  }
};
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .learning-product-info {
    .info-box {
      h2 {
        position: relative;
        margin-top: 0;
        margin-bottom: 0;
        display: inline-block;
        padding-left: 34px;
        i {
          @include svg(learning-product, $--color-primary);
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
