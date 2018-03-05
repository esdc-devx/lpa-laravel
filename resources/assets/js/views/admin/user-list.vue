<template>
  <div class="content user-list" v-loading="isLoading">
    <h2>User list</h2>
    <el-button @click="$router.push('users/create')">Create a user</el-button>
    <el-table
      empty-text="Nothing to show here mate"
      :default-sort="{prop: 'id', order: 'ascending'}"
      :data="parsedUsers"
      @cell-click="editUser">
      <el-table-column
        sortable
        prop="id"
        label="Index"
        width="180">
      </el-table-column>
      <el-table-column
        sortable
        prop="username"
        label="Username">
      </el-table-column>
      <el-table-column
        sortable
        prop="name"
        label="Name">
      </el-table-column>
      <el-table-column
        sortable
        prop="email"
        label="Email">
      </el-table-column>
      <el-table-column
        :filters="[{ text: 'Home', value: 'Home' }, { text: 'Office', value: 'Office' }]"
        :filter-method="filterGroup"
        filter-placement="bottom-start"
        prop="organization_units"
        label="Organizational Unit(s)">
        <template slot-scope="scope">
          <el-tag
            v-for="orgUnit in scope.row.organization_units"
            :key="orgUnit.id"
            type="info"
            size="small"
            :title="orgUnit">{{orgUnit}}</el-tag>
        </template>
      </el-table-column>
      <!--
        @todo: Uncomment when backend will handle delete user correctly
                  (receive deleted users when not forced deleted)
      -->
      <!-- <el-table-column
        label="Operations">
        <template slot-scope="scope">
          <el-button
            size="mini"
            type="danger"
            @click="deleteUserModal(scope.row.id)">Delete</el-button>
        </template>
      </el-table-column> -->
    </el-table>

    <el-pagination
      background
      @current-change="handlePageChange"
      :current-page.sync="currentPage"
      :page-size="pagination.per_page"
      layout="total, prev, pager, next"
      :total="pagination.total">
    </el-pagination>

    <el-dialog :visible.sync="showDeleteModal" width="30%">
      <span slot="title" class="el-dialog__title">Delete user {{user.name}} ?</span>
      <span>This action cannot be undone.</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="confirmDelete()">Delete</el-button>
        <el-button @click="showDeleteModal = false">Cancel</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../helpers/event-bus.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-list',

    computed: {
      ...mapGetters({
        users: `${namespace}/all`,
        pagination: `${namespace}/pagination`
      })
    },

    data() {
      return {
        isLoading: true,
        user: {
          id: null,
          name: null
        },
        parsedUsers: [],
        showDeleteModal: false,
        currentPage: 1
      }
    },

    methods: {
      ...mapActions({
        loadUsers: `${namespace}/loadUsers`,
        deleteUser: `${namespace}/deleteUser`
      }),

      parseUsers() {
        // clone the array without reference
        this.parsedUsers = JSON.parse(JSON.stringify(this.users));
        _.map(this.parsedUsers, item => {
          // concat all organization units into a string seperated by commas
          item.organization_units = _.map(item.organization_units, 'name');
        });
      },

      // Pagination
      handlePageChange(newCurrentPage) {
        this.$parent.$el.scrollTop = 0;
        // IE11 scroll to top
        document.documentElement.scrollTop = 0;
        this.triggerLoadUsers(newCurrentPage);
      },

      // Form handlers
      editUser(user, column) {
        // @todo: make this text translatable
        if (column.label === 'Operations') {
          return;
        }
        this.$router.push(`${namespace}/edit/${user.id}`);
      },

      deleteUserModal(id) {
        this.user = this.findUser(id);
        this.showDeleteModal = true;
      },

      async confirmDelete() {
        await this.deleteUser(this.user.id);
        this.showDeleteModal = false;
        this.notifySuccess(`<b>${this.user.name}</b> has been deleted.`);
        this.triggerLoadUsers();
      },

      // Filters
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
      },

      async triggerLoadUsers(page) {
        this.$parent.$el.scrollTop = 0;
        this.isLoading = true;
        page = _.isUndefined(page) ? this.currentPage : page;
        await this.loadUsers(page)
        this.parseUsers();
        this.isLoading = false;
      }
    },

    created() {
      EventBus.$emit('App:ready');
      this.triggerLoadUsers();
    }
  };
</script>

<style lang="scss">
  .user-list {
    h2 {
      text-align: center;
    }
    table {
      width: 100%;
      tbody {
        tr {
          .delete-user {
            position: relative;
            &:hover {
              background-color: #d1143a;
            }
          }
        }
      }
    }
    .el-table__row {
      cursor: pointer;
    }

    .el-tag {
      height: auto;
      white-space: normal;
      margin-right: 4px;
      margin-top: 2px;
      margin-bottom: 2px;
    }
  }
</style>
