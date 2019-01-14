<template>
  <div class="user-search">
    <el-autocomplete
      :name="name"
      :disabled="disabled"
      v-model="user.name"
      v-validate="'required'"
      :data-vv-as="label"
      popper-class="user-autocomplete"
      :fetch-suggestions="querySearchAsync"
      :trigger-on-focus="false"
      valueKey="name"
      @select="handleSelect"
      @focus="onFocus"
      @blur="onBlur">
      <template slot-scope="props">
        <div class="autocomplete-popper-inner-wrap" :title="props.item.name">
          <div class="value">{{ props.item.name }}</div>
          <span class="link">{{ props.item.email }}</span>
        </div>
      </template>
    </el-autocomplete>
  </div>
</template>

<script>
  import { mapActions } from 'vuex';

  export default {
    name: 'user-search',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      name: {
        type: String,
        required: true
      },
      disabled: {
        type: Boolean,
        default: false
      },
      user: {
        type: Object,
        default: {
          name: ''
        }
      },
      label: {
        type: String,
        default: ''
      }
    },

    data() {
      return {
        userList: [],
        focused: false
      };
    },

    watch: {
      user: function (val) {
        // When user value changes, make sure it's been added to the list of valid users.
        if (!_.isEmpty(val) && this.userList.length == 0) {
          this.userList.push(Object.assign({}, this.user));
        }
      }
    },

    methods: {
      ...mapActions('users', [
        'search'
      ]),

      async searchUser(userName) {
        let response = await this.search(userName);
        this.userList = response.data;
        if (!this.focused) {
          this.validateNameInput();
        }
      },

      async querySearchAsync(queryString, callback) {
        if (queryString) {
          await this.searchUser(queryString);
        }
        callback(this.userList);
      },

      validateNameInput() {
        if (this.user.name) {
          // Check if name entered exists, if so select the matched user.
          let index = _.findIndex(this.userList, user => user.name.toLowerCase() == this.user.name.toLowerCase());
          return this.handleSelect(index !== -1 ? _.cloneDeep(this.userList[index]) : {});
        }
        return this.handleSelect({});
      },

      onFocus(event) {
        this.focused = true;
      },

      onBlur(event) {
        this.focused = false;
        // Search and validate user input when leaving field.
        if (this.user.name) {
          this.searchUser(this.user.name);
        }
      },

      handleSelect(user) {
        this.$emit('update:user', user);
      }
    }
  };
</script>

<style lang="scss">
  .user-search {
    .el-autocomplete {
      width: 35%;
    }
  }

  .el-autocomplete-suggestion.user-autocomplete {
    width: 30%;
    li {
      line-height: 20px;
      padding: 7px 10px;

      .autocomplete-popper-inner-wrap {
        overflow: hidden;
        .value {
          text-overflow: ellipsis;
          overflow: hidden;
        }
        .link {
          font-size: 12px;
          color: #b4b4b4;
        }
      }
    }
  }

</style>
