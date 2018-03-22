export default Object.freeze({
  DEBUG: process.env.NODE_ENV !== 'production',
  DEFAULT_LANG: document.querySelector('html').lang,

  THROTTLE_WAIT_TIME: 500
});
