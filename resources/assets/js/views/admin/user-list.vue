<template>
  <div class="content user-list">
    <div class="controls">
      <el-button @click="$router.push('users/create')">Create a user</el-button>
    </div>
    <el-table
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
        prop="organizational_units"
        label="Organizational Unit(s)">
        <template slot-scope="scope">
          <el-tag
            v-for="item in scope.row.organizational_units"
            :key="item.id"
            type="info"
            size="small"
            :title="item">{{item}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        :filters="[{ text: 'Home', value: 'Home' }, { text: 'Office', value: 'Office' }]"
        :filter-method="filterGroup"
        filter-placement="bottom-start"
        prop="roles"
        label="Role(s)">
        <template slot-scope="scope">
          <el-tag
            v-for="item in scope.row.roles"
            :key="item.id"
            type="info"
            size="small"
            :title="item">{{item}}</el-tag>
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

    <!-- @todo: Uncomment when backend will handle delete user correctly
               (receive deleted users when not forced deleted)
    <el-dialog :visible.sync="showDeleteModal" width="30%">
      <span slot="title" class="el-dialog__title">Delete user {{user.name}} ?</span>
      <span>This action cannot be undone.</span>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="confirmDelete()">Delete</el-button>
        <el-button @click="showDeleteModal = false">Cancel</el-button>
      </span>
    </el-dialog> -->
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '../../event-bus.js';

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
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadUsers: `${namespace}/loadUsers`,
        deleteUser: `${namespace}/deleteUser`
      }),

      parseUsers() {
        // clone the array without reference
        this.parsedUsers = JSON.parse(JSON.stringify(this.users));
        _.map(this.parsedUsers, item => {
          // concat all organizational units into a string seperated by commas
          item.organizational_units = _.map(item.organizational_units, 'name');
          item.roles = _.map(item.roles, 'name');
        });
      },

      // Pagination
      handlePageChange(newCurrentPage) {
        this.scrollToTop();
        this.triggerLoadUsers(newCurrentPage);
      },

      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      // Form handlers
      editUser(user, column) {
        // @todo: make this text translatable
        if (column.label === 'Operations') {
          return;
        }
        this.scrollToTop();
        this.$router.push(`${namespace}/edit/${user.id}`);
      },

      // @todo: Uncomment when backend will handle delete user correctly
      //           (receive deleted users when not forced deleted)
      // deleteUserModal(id) {
      //   this.user = this.findUser(id);
      //   this.showDeleteModal = true;
      // },

      // async confirmDelete() {
      //   await this.deleteUser(this.user.id);
      //   this.showDeleteModal = false;
      //   this.notifySuccess(`<b>${this.user.name}</b> has been deleted.`);
      //   this.triggerLoadUsers();
      // },

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
        this.showMainLoading();
        page = _.isUndefined(page) ? this.currentPage : page;
        await this.loadUsers(page)
        this.parseUsers();
        this.hideMainLoading();
      }
    },

    mounted() {
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
