<template>
  <div id='project-create' class="content">
    <h2>Create User</h2>
    <el-form :model="form" :rules="rules" ref="form" label-width="20%" @submit.native.prevent>
      <el-form-item label="Username" for="name" prop="name">
        <el-input v-model="form.name" id="name" auto-complete="off"></el-input>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click.prevent="submit('form')">Create</el-button>
        <el-button @click.prevent="goBack()">Cancel</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import EventBus from '../../components/event-bus.js';

  export default {
    name: 'UserCreate',

    data() {
      return {
        form: {
          name: ''
        },
        rules: {
          name: [
            { required: true, message: 'Please input a Username', trigger: 'change' },
          ]
        }
      }
    },

    methods: {
      submit(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.create();
            this.resetForm(formName);
            this.$notify({
              title: 'Success',
              dangerouslyUseHTMLString: true,
              message: `<b>${this.form.name}</b> has been created.`,
              type: 'success',
            });
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },

      resetForm(formName) {
        this.$refs[formName].resetFields();
      },

      create() {
        EventBus.$emit('User:create', this.form.name);
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
