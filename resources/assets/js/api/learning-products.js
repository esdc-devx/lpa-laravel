import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  getLearningProducts() {
    return axios.get(`learning-products`);
  },

  getLearningProduct(id) {
    return axios.get(`learning-products/${id}`);
  },

  getLearningProductCreateInfo() {
    return axios.get('learning-products/create');
  },

  canCreateLearningProduct() {
    return axios.get('authorization/learning-product/create');
  },

  create(learningProduct) {
    return axios.post('learning-products', learningProduct);
  }
};
