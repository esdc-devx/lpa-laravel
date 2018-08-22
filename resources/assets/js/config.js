export default Object.freeze({
  DEBUG: process.env.NODE_ENV !== 'production',
  DEFAULT_LANG: document.querySelector('html').lang,
  // this is to ensure that actions are debounced to avoid user click spam
  DEBOUNCE_WAIT_TIME: 350
});
