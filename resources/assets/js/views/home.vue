<template>
  <div class="home content">
    <el-row type="flex" justify="center">
      <el-col :span="24"><h2>{{ trans('home.welcome', { name: user.first_name }) }}</h2></el-col>
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

    <project-create
      v-if="showCreateProjectModal"
      :show.sync="showCreateProjectModal"
      @close="showCreateProjectModal = false"/>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import EventBus from '../components/event-bus.js';

  import ProjectCreate from '../views/project-create';

  export default {
    name: 'home',

    components: { ProjectCreate },

    computed: {
      ...mapGetters({
        user: 'users/current'
      })
    },

    data() {
      return {
        showCreateProjectModal: false
      }
    },

    created() {
      EventBus.$emit('App:ready');
    }
  };
</script>

<style lang="scss">
  .home {
    h2, p {
      text-align: center;
    }

    h2 {
      font-size: 3rem;
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
  }
</style>
