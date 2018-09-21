<template>
  <div class="learning-product-info">
    <info-box>
      <div slot="header">
        <h2><i class="el-icon-lpa-learning-product"></i>{{ learningProductProp.name }}</h2>
        <!-- <div class="controls" v-if="hasRole('owner') || hasRole('admin')">
          <el-button :disabled="!rights.canEdit" size="mini" @click="edit()"><i class="el-icon-lpa-edit"></i></el-button>
          <el-button :disabled="!rights.canDelete" type="danger" size="mini" @click="deleteWrapper()" plain><i class="el-icon-lpa-delete"></i></el-button>
        </div> -->
      </div>
      <dl>
        <dt>{{ trans('entities.general.lpa_num') }}</dt>
        <dd>{{ learningProductProp.id | LPANumFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.parent_project') }}</dt>
        <dd><router-link :to="'/' + language + '/projects/' + learningProductProp.project_id">{{ learningProductProp.project_id | LPANumFilter }}</router-link></dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.type') }}</dt>
        <dd>{{ learningProductProp.type.name }} {{ listTokenSeparator }} {{ learningProductProp.sub_type.name }}</dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.general.organizational_units') }}</dt>
        <dd>{{ learningProductProp.organizational_unit.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.manager') }}</dt>
        <dd>{{ learningProductProp.manager.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.learning_product.primary_contact') }}</dt>
        <dd>{{ learningProductProp.primary_contact.name }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.created') }}</dt>
        <dd>{{ learningProductProp.created_by.name }}</dd>
        <dd>{{ learningProductProp.created_at }}</dd>
      </dl>
      <dl>
        <dt>{{ trans('entities.general.updated') }}</dt>
        <dd>{{ learningProductProp.updated_by.name }}</dd>
        <dd>{{ learningProductProp.updated_at }}</dd>
      </dl>
    </info-box>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import InfoBox from '@components/info-box.vue';
  import PageUtils from '@mixins/page/utils.js';
  import Constants from '@/constants';

  let namespace = 'learningProducts';

  export default {
    name: 'learning-product-info',

    components: { InfoBox },

    mixins: [ PageUtils ],

    props: [ 'learningProduct' ],

    data() {
      return {
        listTokenSeparator: Constants.LIST_TOKEN_SEPARATOR,
        // rights: {
        //   canEdit: false,
        //   canDelete: false
        // }
      };
    },

    computed: {
      ...mapGetters({
        language: 'language',
        hasRole: 'users/hasRole'
      }),

      learningProductProp() {
        return this.learningProduct;
      }
    },

    methods: {
      // ...mapActions({
      //   deleteLearningProduct: `${namespace}/deleteLearningProduct`,
      //   canEditLearningProduct: `${namespace}/canEditLearningProduct`,
      //   canDeleteLearningProduct: `${namespace}/canDeleteLearningProduct`
      // }),

      // edit() {
      //   this.$router.push(`${this.learningProduct.id}/edit`);
      // },

      // deleteWrapper() {
      //   this.confirmDelete({
      //     title: this.trans('components.notice.title.delete_learning_product'),
      //     message: this.trans('components.notice.message.delete_learning_product', {
      //       name: this.learningProduct.name,
      //       id: this.$options.filters.LPANumFilter(this.learningProduct.id)
      //     })
      //   }).then(async () => {
      //     try {
      //       await this.deleteLearningProduct(this.learningProduct.id);
      //       this.notifySuccess({
      //         message: this.trans('components.notice.message.learning_product_deleted')
      //       });
      //       this.goToParentPage();
      //     } catch (e) {
      //       // Exception handled by interceptor
      //       if (!e.response) {
      //         throw e;
      //       }
      //     }
      //   }).catch(() => false);
      // }
    },

    async created() {
      let learningProductId = this.$route.params.learningProductId;
      // this.rights.canEdit = await this.canEditLearningProduct(learningProductId);
      // this.rights.canDelete = await this.canDeleteLearningProduct(learningProductId);
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
