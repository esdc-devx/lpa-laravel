import _ from 'lodash';
import axios from '@axios/interceptor';

export default {

  async getLearningProducts() {
    let response = await axios.get(`learning-products`);
    return response.data.data.learning_products;
  },

  async getProjectLearningProducts(projectId) {
    let response = await axios.get(`learning-products/?project_id=${projectId}`);
    return response.data.data.learning_products;
  },

  async getLearningProduct(id) {
    let response = await axios.get(`learning-products/${id}`);
    return response.data.data.learning_product;
  },

  async getCreateInfo() {
    let response = await axios.get('learning-products/create');
    return response.data.data;
  },

  async canCreate() {
    let response = await axios.get('authorization/learning-product/create');
    return response.data.data.allowed;
  },

  async create(learningProduct) {
    let response = await axios.post('learning-products', learningProduct);
    return response.data.data;
  },

  async canDelete(id) {
    let response = await axios.get(`authorization/learning-product/delete/${id}`);
    return response.data.data.allowed;
  },

  async delete(id) {
    await axios.delete(`learning-products/${id}`);
  },
};
