import _ from 'lodash';
import axios from '@axios/interceptor';

export default {

  async getLearningProducts() {
    let response = await axios.get(`learning-products`);
    return response.data.data.learning_products;
  },

  async getLearningProduct(id) {
    let response = await axios.get(`learning-products/${id}`);
    return response.data.data.learning_product;
  },

  async canDelete(id) {
    let response = await axios.get(`authorization/learning-product/delete/${id}`);
    return response.data.data.allowed;
  },

  async delete(id) {
    await axios.delete(`learning-products/${id}`);
  }
};
