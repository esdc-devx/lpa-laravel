<template>
  <div>
    <el-row type="flex" justify="center" :gutter="18">
      <el-col :span="18">
        <h2 v-if="isAdminOrOwner" v-html="trans('pages.home.title_owner')"></h2>
        <h2 v-else v-html="trans('pages.home.title_non_owner')"></h2>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="18">
      <el-col class="icon-divider" :span="18">
        <i class="el-icon el-icon-lpa-owl"></i>
      </el-col>
    </el-row>
    <el-row type="flex" justify="center" :gutter="14">
      <el-col :span="14">
        <p v-if="isAdminOrOwner" v-html="trans('pages.home.instruction_owner')"></p>
        <p v-else v-html="trans('pages.home.instruction_non_owner')"></p>
        <p v-html="trans('pages.home.instruction_everyone')"></p>
      </el-col>
    </el-row>
    <!-- Actions -->
    <el-row
      v-if="isAdminOrOwner"
      type="flex"
      class="actions"
      justify="center"
      :gutter="15"
      align="top"
    >
      <el-col :span="7">
        <el-button
          class="action"
          @click="$router.push(`${language}/projects/create`)"
          icon="el-icon-lpa-projects"
        >
          <span v-html="trans('pages.project_list.create_project')"></span>
        </el-button>
        <el-alert
          type="info"
          :title="trans('pages.home.description_project')"
          show-icon
          :closable="false"
        />
      </el-col>
      <el-col :span="7" :offset="1">
        <el-button
          class="action"
          @click="$router.push(`${language}/learning-products/create`)"
          icon="el-icon-lpa-learning-product"
        >
          <span v-html="trans('pages.learning_product_list.create_learning_product')"></span>
        </el-button>
        <el-alert
          type="info"
          :title="trans('pages.home.description_learning_product')"
          show-icon
          :closable="false"
        />
      </el-col>
    </el-row>
    <el-row
      v-if="isAdminOrOwner"
      type="flex"
      class="note"
      justify="center"
      :gutter="12"
    >
      <el-col :span="12">
        <div>
          <i class="el-icon-warning"></i><span v-html="trans('pages.home.tip_owner')"></span>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';

  export default {
    name: 'home',

    extends: Page,

    computed: {
      isAdminOrOwner() {
        return this.hasRole('admin') || this.hasRole('owner');
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .home {
    .el-row {
      margin-top: 0px;
    }

    h2, p {
      text-align: center;
    }

    h2 {
      display: block;
      font-size: 2.5rem;
      // reset to browser default
      margin-block-start: 0.83em;
      margin-inline-start: 0px;
      margin-inline-end: 0px;
      margin-bottom: 0;
    }

    .actions {
      align-items: stretch;
      .el-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        .action {
          @include size(100%, 100px);
          text-align: center;
          white-space: normal;
          display: flex;
          align-items: center;
          justify-content: center;
          &:hover {
            border-color: $--button-default-border;
          }
          i {
            @include size($svg-icons-size-medium);
            &.el-icon-lpa-projects {
              @include svg(projects, $--color-primary);
            }
            &.el-icon-lpa-learning-product {
              @include svg(learning-product, $--color-primary);
            }
          }
          span {
            text-decoration: underline;
            font-weight: bold;
          }
        }

        .action + .el-alert {
          height: calc(100% - 100px);
        }
      }
    }

    .note {
      margin-top: 2em;
      text-align: center;
      i {
        color: #e6a23c;
        margin-right: 5px;
      }
    }
  }
</style>
