<template>
  <div class="content">
    <el-row type="flex"  justify="center">
      <el-col :span="24"><h2>What would you like to do today?</h2></el-col>
    </el-row>
    <el-row type="flex"  justify="center">
      <el-col :span="18"><hr></el-col>
    </el-row>
    <el-row type="flex"  justify="center">
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
        <el-button @click="showCreateLearningProjectModal = true">
          <i class="el-icon-edit-outline"></i>
          <p>Create a New<br>Learning Project</p>
        </el-button>
      </el-col>
      <el-col :span="3" class="disabled">
        <el-button>
          <i class="el-icon-tickets"></i>
          <p>Create a New<br>Stand-Alone Project</p>
        </el-button>
      </el-col>
      <el-col :span="3">
        <el-button>
          <i class="el-icon-refresh"></i>
          <p>Update a Project</p>
        </el-button>
      </el-col>
      <el-col :span="3" class="disabled">
        <el-button>
          <i class="el-icon-document"></i>
          <p>Access My Reports</p>
        </el-button>
      </el-col>
      <el-col :span="3" class="disabled">
        <el-button>
          <i class="el-icon-download"></i>
          Archive a Project
        </el-button>
      </el-col>
    </el-row>

    <el-date-picker
      v-model="value1"
      type="date">
    </el-date-picker>

    <learning-project-create :show="showCreateLearningProjectModal" @close="showCreateLearningProjectModal = false"></learning-project-create>
  </div>
</template>

<script>
  import LearningProjectCreate from '../views/learning-project-create';

  export default {
    name: 'home',

    data () {
      return {
        showCreateLearningProjectModal: false
      }
    },

    components: { LearningProjectCreate },

    methods: {

    }
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
