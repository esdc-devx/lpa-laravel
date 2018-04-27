export default {
  methods: {
    go(location) {
      this.$helpers.throttleAction(() => {
        this.$router.push(location);
      });
    }
  }
};
