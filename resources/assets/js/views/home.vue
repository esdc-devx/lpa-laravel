<template>
  <div class="home content">
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
    <el-row v-if="isAdminOrOwner" type="flex" class="actions" justify="center" :gutter="15" align="top">
      <el-col :span="7" >
        <el-button class="action" @click="$router.push(`${language}/projects/create`)" icon="el-icon-lpa-projects">
          <span class="action-instruction" v-html="trans('pages.project_list.create_project')"></span>
          <span class="action-description" v-html="trans('pages.home.description_project')"></span>
        </el-button>
      </el-col>
      <el-col :span="7" :offset="1">
        <el-button class="action" @click="$router.push(`${language}/learning-products/create`)" icon="el-icon-lpa-learning-product">
          <span class="action-instruction" v-html="trans('pages.learning_product_list.create_learning_product')"></span>
          <span class="action-description" v-html="trans('pages.home.description_learning_product')"></span>
        </el-button>
      </el-col>
    </el-row>
    <el-row v-if="isAdminOrOwner" type="flex" justify="center" :gutter="12">
      <el-col :span="12">
        <p v-html="trans('pages.home.tip_owner')"></p>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import EventBus from '@/event-bus.js';

  export default {
    name: 'home',

    computed: {
      ...mapGetters({
        language: 'language',
        user: 'users/current',
        hasRole: 'users/hasRole',
      }),
      isAdminOrOwner() {
        return this.hasRole('admin') || this.hasRole('owner');
      }
    },

    mounted() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';
  
  .home {
    // since there is no breadcrumbs bar on the home page,
    // make sure the content is at the top
    top: 0;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    
    .el-row {
      margin-top: 0px;
    }

    h2, p {
      text-align: center;
    }

    h2 {
      font-size: 2.5rem;
      margin-bottom: 0;
    }

    .actions {
      align-items: stretch;
      .action {
        text-align: center;
        white-space: normal;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
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
          display: block;
          margin-top: 1em;
          line-height: 1.5em;
          width: 100%;
          &.action-instruction {
            text-decoration: underline;
            font-weight: bold;
          }
        }
      }
    }
  }
</style>
