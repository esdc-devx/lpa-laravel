export default Object.freeze({
  DEBUG: process.env.NODE_ENV !== 'production',
  DEFAULT_LANG: document.querySelector('html').lang,
  // this is to ensure that actions are throttled to avoid user click spam
  THROTTLE_WAIT_TIME: 1500
});
