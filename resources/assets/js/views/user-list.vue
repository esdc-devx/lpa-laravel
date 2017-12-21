<template>
  <div class="content">
    <h2>User list</h2>
    <el-button type="primary" @click="showCreateModal = true"><b>+</b></el-button>
    <el-table
      empty-text="Nothing to show here mate"
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="users">
      <el-table-column
        sortable
        prop="id"
        label="Index"
        width="180">
      </el-table-column>
      <el-table-column
        sortable
        prop="name"
        label="Name">
      </el-table-column>
      <el-table-column
        :filters="[{ text: 'Home', value: 'Home' }, { text: 'Office', value: 'Office' }]"
        :filter-method="filterGroup"
        filter-placement="bottom-start"
        prop="group"
        label="Organizational group">
      </el-table-column>
      <el-table-column
        label="Operations">
        <template slot-scope="scope">
          <el-button
            size="mini"
            @click="editUser(scope.row.id)">Edit</el-button>
          <el-button
            size="mini"
            type="danger"
            @click="deleteUser(scope.row.id)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      :visible.sync="showDeleteModal"
      width="30%">
      <span slot="title" class="el-dialog__title">Delete user {{user.name}} ?</span>
      <span>This action cannot be undone.</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="deleteProject()">Delete</el-button>
        <el-button @click="showDeleteModal = false">Cancel</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { EventBus } from '../components/event-bus.js';

  export default {
    name: 'UserList',

    data() {
      return {
        user: {
          name: null
        },
        users: [],
        showCreateModal: false,
        showDeleteModal: false
      }
    },

    methods: {
      create(name) {
        this.users.push({name});
      },

      editUser(index) {
        alert('edit user popup?')
      },

      deleteUserModal(id) {
        this.user = this.findUser(id);
        this.showDeleteModal = true;
      },

      deleteUser(index) {
        this.users.splice(index, 1);
      },

      findUser(id) {
        return this.users[this.findUserIndex(id)];
      },

      findUserIndex(id) {
        for (var i = 0; i < this.users.length; i++) {
          if (this.users[i].id == id) {
            return i;
          }
        }
      },
      filterGroup(value, row) {
        return row.group === value;
      }
    },

    mounted() {
      // TODO: When mounted, it should do a Promise to the server and get the users
    },

    created() {
      EventBus.$on('User:create', name => {
        this.create(name);
      });
    }
  };
</script>

<style scoped lang="scss">
  table {
    width: 100%;
    tbody {
      tr {
        background-color: #fafafa;
        .delete-user {
          position: relative;
          &:hover {
            background-color: #d1143a;
          }
        }
      }
    }
  }
</style>