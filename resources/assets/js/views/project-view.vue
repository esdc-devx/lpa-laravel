<template>
  <div id='project-view' class="content">
    <h2>Project</h2>
    <form>
      <div class="field">
        <label for="name" class="label">Project Name</label>
        <div class="control">
          <input v-model="project.name" name="name" type="text" class="input" placeholder="Enter a project name">
        </div>
        <!-- <small class="help is-danger" v-if="verrors.has('name')">{{ verrors.first('name') }}</small> -->
      </div>

      <div class="field">
        <label for="description" class="label">Project Organizational Group</label>
        <div class="control">

        </div>
        <!-- <small class="help is-danger" v-if="verrors.has('description')">{{ verrors.first('description') }}</small> -->
      </div>

      <div class="field">
        <div class="control">
          <button class="button is-link" :class="{'is-loading': isLoading}">Save</button>
          <button class="button" @click.prevent="goBack()">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
  import { EventBus } from '../components/event-bus.js';

  export default {
    name: 'ProjectView',

    computed: {
      project() {
        return this.$store.getters.project
      }
    },
    data() {
      return {
        isLoading: false
      }
    },

    methods: {
      save() {
        let that = this;
        this.isLoading = true;
        setTimeout(() => {
          that.isLoading = false;
          EventBus.$emit('ProjectList:save', this.project);
          that.goBack();
        }, 500);
      },

      goBack() {
        this.$router.go(-1);
      }
    }
  };
</script>

<style lang="scss" scoped>
  #project-view {
    width: 600px;
    margin: 0 auto;
  }
</style>
