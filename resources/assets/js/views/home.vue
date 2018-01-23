<template>
  <div class="content">
    <el-row type="flex" justify="center">
      <el-col :span="24"><h2>{{ trans('home.welcome', { name: username }) }}</h2></el-col>
    </el-row>
    <el-row type="flex" justify="center">
      <el-col :span="18"><hr></el-col>
    </el-row>
    <el-row type="flex" justify="center">
      <el-col :span="24">
        <p>
          Please select one of the tasks below to continue or navigate LPA using the menu above.
          <br>
          First time user? Visit our <a href="/getting-started">Getting started with LPA guide</a>.
        </p>
      </el-col>
    </el-row>
    <!-- Actions -->
    <el-row type="flex" class="row-bg actions" justify="center" :gutter="24">
      <el-col :span="3">
        <el-button @click="showCreateProjectModal = true">
          <i class="el-icon-edit-outline"></i>
          <p>Create a New<br>Project</p>
        </el-button>
      </el-col>
      <el-col :span="3" class="disabled">
        <el-button>
          <i class="el-icon-tickets"></i>
          <p>Create a New<br>Learning Product</p>
        </el-button>
      </el-col>
      <el-col :span="3" class="disabled">
        <el-button>
          <i class="el-icon-document"></i>
          <p>Reports</p>
        </el-button>
      </el-col>
    </el-row>

    <project-create :show="showCreateProjectModal" @close="showCreateProjectModal = false"></project-create>
  </div>
</template>

<script>
  import ProjectCreate from '../views/project-create';

  export default {
    name: 'home',

    computed: {
      username() {
        return this.$store.getters.user.name;
      }
    },

    data() {
      return {
        showCreateProjectModal: false
      }
    },

    components: { ProjectCreate }
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/vendors/element-variables';
  h2, p {
    text-align: center;
  }

  i {
    font-size: 3rem;
    text-align: center;
    display: block;
  }

  .actions button {
    white-space: initial;
    width: 100%;
    height: 100%;
    p {
      margin-bottom: 0;
    }
  }
  .dialog-create-project {
    .el-dialog__title {
      font-weight: bold;
      text-transform: uppercase;
    }
  }
</style>
