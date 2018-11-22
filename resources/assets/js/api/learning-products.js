import _ from 'lodash';
import axios from '@axios/interceptor';

export default {
  getLearningProducts() {
    return axios.get(`learning-products`);
  },

  getProjectLearningProducts(projectId) {
    return axios.get(`learning-products/?project_id=${projectId}`);
  },

  getLearningProduct(id) {
    return axios.get(`learning-products/${id}`);
  },

  getCreateInfo() {
    return axios.get('learning-products/create');
  },

  getEditInfo(id) {
    return axios.get(`learning-products/${id}/edit`);
  },

  create(learningProduct) {
    return axios.post('learning-products', learningProduct);
  },

  update(id, data) {
    return axios.put(`learning-products/${id}`, data);
  },

  delete(id) {
    axios.delete(`learning-products/${id}`);
  },

  canCreate() {
    return axios.get('authorization/learning-product/create');
  },

  canDelete(id) {
    return axios.get(`authorization/learning-product/delete/${id}`);
  },

  canEdit(id) {
    return axios.get(`authorization/learning-product/edit/${id}`);
  }
};
