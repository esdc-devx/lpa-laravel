import _ from 'lodash';

import Config from './config.js';

const helpers = {
  throttleAction: _.throttle(callback => {
    callback();
  }, Config.THROTTLE_WAIT_TIME)
};

const HelpersPlugin = {
  install(Vue, options) {
    Vue.helpers = helpers;
    Vue.prototype.$helpers = helpers;
  }
};

export default HelpersPlugin;
