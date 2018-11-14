import _ from 'lodash';
import axios from '@axios/interceptor';

export default {

  async getLearningProducts() {
    return await axios.get(`learning-products`);
  },

  async getProjectLearningProducts(projectId) {
    return await axios.get(`learning-products/?project_id=${projectId}`);
  },

  async getLearningProduct(id) {
    return await axios.get(`learning-products/${id}`);
  },

  async getCreateInfo() {
    return await axios.get('learning-products/create');
  },

  async getEditInfo(id) {
    return await axios.get(`learning-products/${id}/edit`);
  },

  async create(learningProduct) {
    return await axios.post('learning-products', learningProduct);
  },

  async update(id, data) {
    return await axios.put(`learning-products/${id}`, data);
  },

  async delete(id) {
    await axios.delete(`learning-products/${id}`);
  },

  async canCreate() {
    return await axios.get('authorization/learning-product/create');
  },

  async canDelete(id) {
    return await axios.get(`authorization/learning-product/delete/${id}`);
  },

  async canEdit(id) {
    return await axios.get(`authorization/learning-product/edit/${id}`);
  }
};
