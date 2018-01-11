<template>
  <div>
    <p>Not Found</p>
    <router-link :to="'/'+$store.getters.getLanguage+'/'">Back to Home page</router-link>
  </div>
</template>

<script>
export default {

  name: 'not-found',

  data () {
    return {

    }
  }
};
</script>

<style lang="scss" scoped>
</style>
