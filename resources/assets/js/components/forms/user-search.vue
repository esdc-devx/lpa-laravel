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
      default: { name: '' }
    },
    label: {
      type: String,
      default: ''
    }
  },

  watch: {
    userList: function(value) {
      let clone = _.cloneDeep(this.userList);
      this.userListNames = _.map(clone, (user) => user.name.toLowerCase());
    }
  },

  data() {
    return {
      userList: [],
      userListNames: [],
      focused: false
    };
  },

  methods: {
    ...mapActions({
      searchUser: 'users/search',
    }),

    async querySearchAsync(queryString, callback) {
      if (queryString) {
        this.userList = await this.searchUser(queryString);
      }
      callback(_.cloneDeep(this.userList));
    },

    validateNameInput() {
      if (this.user.name) {
        // Check if name entered exists, if so select the matched user.
        let index = this.userListNames.indexOf(this.user.name.toLowerCase());
        return this.handleSelect(index !== -1 ? this.userList[index] : {});
      }
      return this.handleSelect({});
    },

    onFocus(event) {
      this.focused = true;
    },

    onBlur(event) {
      this.focused = false;
      _.delay(() => {
        if (this.focused) return;
        this.validateNameInput();
      }, 1000);
    },

    handleSelect(user) {
      this.$emit('update:user', user);
    }
  },

  created() {
    // If component is created with an initial value, add it the
    // the list of valid users.
    if (this.user.name) {
      this.userList.push(Object.assign({}, this.user));
    }
  }
}
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
