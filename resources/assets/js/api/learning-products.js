import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  getLearningProducts() {
    return axios.get(`learning-products`);
  },
};
