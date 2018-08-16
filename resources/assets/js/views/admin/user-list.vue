<template>
  <div class="content user-list">
    <div class="controls">
      <el-button @click="goToPage('admin-user-create')">{{ trans('pages.user_list.create_user') }}</el-button>
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
        :label="trans('entities.user.username')">
      </el-table-column>
      <el-table-column
        sortable
        prop="name"
        :label="trans('entities.general.full_name')">
      </el-table-column>
      <el-table-column
        sortable
        prop="email"
        :label="trans('entities.user.email')">
      </el-table-column>
      <el-table-column
        :filters="[{ text: 'Home', value: 'Home' }, { text: 'Office', value: 'Office' }]"
        :filter-method="filterGroup"
        filter-placement="bottom-start"
        prop="organizational_units"
        :label="$tc('entities.general.organizational_units', 2)">
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
        :label="trans('entities.user.roles')">
        <template slot-scope="scope">
          <el-tag
            v-for="item in scope.row.roles"
            :key="item.id"
            type="info"
            size="small"
            :title="item">{{item}}</el-tag>
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      background
      @current-change="handlePageChange"
      :current-page.sync="currentPage"
      :page-size="pagination.per_page"
      layout="total, prev, pager, next"
      :total="pagination.total">
    </el-pagination>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import EventBus from '@/event-bus.js';

  import PageUtils from '@mixins/page/utils.js';

  let namespace = 'users';

  export default {
    name: 'admin-user-list',

    mixins: [ PageUtils ],

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
        currentPage: 1
      }
    },

    methods: {
      ...mapActions({
        showMainLoading: 'showMainLoading',
        hideMainLoading: 'hideMainLoading',
        loadUsers: `${namespace}/loadUsers`
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
        this.scrollToTop();
        this.$router.push(`${namespace}/edit/${user.id}`);
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
        await this.showMainLoading();
        this.$parent.$el.scrollTop = 0;
        page = _.isUndefined(page) ? this.currentPage : page;
        try {
          await this.loadUsers(page);
          this.parseUsers();
        } catch ({ response }) {}
        await this.hideMainLoading();
      },

      async onLanguageUpdate() {
        await this.triggerLoadUsers();
      }
    },

    beforeRouteLeave(to, from, next) {
      // Destroy any events we might be listening
      // so that they do not get called while being on another page
      EventBus.$off('Store:languageUpdate', this.onLanguageUpdate);
      next();
    },

    mounted() {
      EventBus.$emit('App:ready');
      EventBus.$on('Store:languageUpdate', this.onLanguageUpdate);
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
