<template>
  <div id='project-create' class="content">
    <h2>Edit</h2>
    <form>
      <div class="field">
        <label for="name" class="label">Project name</label>
        <div class="control">
          <input v-validate="'required'" v-model="product.name" name="name" type="text" class="input" placeholder="Enter a project name">
        </div>
        <small class="help is-danger" v-if="verrors.has('name')">{{ verrors.first('name') }}</small>
      </div>

      <div class="field">
        <label for="description" class="label">Project description</label>
        <div class="control">
          <input v-validate="'required'" v-model="product.description" name="description" type="text" class="input" placeholder="Enter a project description">
        </div>
        <small class="help is-danger" v-if="verrors.has('description')">{{ verrors.first('description') }}</small>
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
    name: 'ProjectEdit',

    data() {
      return {
        isLoading: false,
        product: {
          id: this.$route.params.id,
          name: this.$route.params.name,
          description: this.$route.params.description
        }
      }
    },

    methods: {
      save() {
        let that = this;
        this.isLoading = true;
        setTimeout(() => {
          that.isLoading = false;
          EventBus.$emit('ProjectList:save', this.product);
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
  #project-create {
    width: 600px;
    margin: 0 auto;
  }
</style>