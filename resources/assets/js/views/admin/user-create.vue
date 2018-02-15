<template>
  <div id='project-create' class="content">
    <h2>Create User</h2>
    <el-form :model="form" :rules="rules()" ref="form" @submit.native.prevent>
      <el-form-item label="Username" for="name" prop="name">
        <el-autocomplete
          id="name"
          name="name"
          v-model="form.name"
          :fetch-suggestions="querySearchAsync"
          :trigger-on-focus="false"
          valueKey="fullname"
        ></el-autocomplete>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click.prevent="submit()">Create</el-button>
        <el-button @click.prevent="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import EventBus from '../../components/event-bus.js';

  export default {
    name: 'UserCreate',

    computed: {
      ...mapGetters([
        'language'
      ])
    },

    data() {
      return {
        // @todo: add dynamic attribute values from backend
        nameRequired: this.trans('validation.required', { attribute: 'name' }),
        form: {
          name: ''
        }
      }
    },

    methods: {
      // Form
      rules() {
        return {
          name: [
            { required: true, message: this.nameRequired, trigger: 'change' },
          ]
        }
      },

      submit() {
        this.$refs.form.validate(valid => {
          if (valid) {
            this.create();
            this.resetForm();
            this.notifySuccess(`{something} has been created.`);
          } else {
            // form has errors
            // @todo: might want to focus on the error fields
            return false;
          }
        });
      },

      resetForm(formName) {
        this.$refs.form.resetFields();
      },

      // @todo: fetch data from backend
      loadAll() {
        return [
          { "fullname": "Francis Mawn", "username": "fmawn" },
          { "fullname": "Patrick Messier", "username": "pmessier" },
          { "fullname": "Mathieu Peterson", "username": "mpeterson" },
          { "fullname": "Cedric Guindon", "username": "cguindon" }
         ];
      },

      querySearchAsync(queryString, callback) {
        var users = this.users;
        var results = queryString ? users.filter(this.createFilter(queryString)) : users;

        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
          callback(results);
        }, 1000);
      },

      createFilter(queryString) {
        return item => {
          return (item.fullname.toLowerCase().indexOf(queryString.toLowerCase()) === 0) ||
                 (item.username.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },

      // Navigation
      create() {
        EventBus.$emit('User:create', this.form.name);
      },

      goBack() {
        this.$router.push('/users');
      }
    },

    mounted() {
      this.users = this.loadAll();
    }
  };
</script>

<style lang="scss" scoped>
  #project-create {
    width: 600px;
    margin: 0 auto;
  }
</style>
