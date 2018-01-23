<template>
  <div>
    <p>Not Found</p>
    <router-link :to="'/' + lang">Back to Home page</router-link>
  </div>
</template>

<script>
export default {
  name: 'not-found',
  computed: {
    lang() {
      return this.$store.getters.language;
    }
  },
  data () {
    return {

    }
  }
};
</script>

<style lang="scss" scoped>
</style>
