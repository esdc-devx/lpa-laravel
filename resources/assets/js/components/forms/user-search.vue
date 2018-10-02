<template>
  <div class="user-search">
    <el-autocomplete
      :name="name"
      v-model="value.name"
      v-validate="'required'"
      :data-vv-as="label"
      popper-class="user-autocomplete"
      :fetch-suggestions="querySearchAsync"
      :trigger-on-focus="false"
      valueKey="name"
      @select="handleSelect"
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
    isLoading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    value: {
      type: Object,
      default: { name: ''}
    },
    label:{
      type: String,
      default: '[Unknown]'
    }
  },

  computed: {
    userListNames() {
      return _.map(this.userList, (user) => user.name.toLowerCase());
    }
  },

  data() {
    return {
      userList: []
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
      callback(this.userList);
    },

    onBlur(event) {
      if (this.value.name) {
        // Check if value entered exists when focusing out of the field, if so
        // select the matched user.
        let index = this.userListNames.indexOf(this.value.name.toLowerCase());
        if (index !== -1) {
          return this.handleSelect(this.userList[index]);
        }
        return this.handleSelect({});
      }
    },

    handleSelect(item) {
      this.$emit('update:value', item);
    }
  },

  created() {
    // If component is created with an initial value, add it the
    // the list of valid users.
    if (this.value) {
      this.userList.push(this.value);
    }
  }
}
</script>

<style lang="scss">
  .user-search {
    .el-autocomplete {
      width: 35%;
      min-width: 400px;
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
