import axios from '@axios/interceptor';

export default {
  create(learningProduct) {
    return axios.post('learning-products', learningProduct);
  },

  update(id, data) {
    return axios.put(`learning-products/${id}`, data);
  },

  delete(id) {
    axios.delete(`learning-products/${id}`);
  },

  get(id) {
    return axios.get(`learning-products/${id}`);
  },

  getAll() {
    return axios.get('learning-products');
  },

  getProjectLearningProducts(projectId) {
    return axios.get(`learning-products?project_id=${projectId}`);
  },

  getCreateInfo() {
    return axios.get('learning-products/create');
  },

  getEditInfo(id) {
    return axios.get(`learning-products/${id}/edit`);
  },

  getCodes(code) {
    return axios.get(`learning-product-codes`);
  },

  search(code) {
    return axios.get('search/learning-product-code', {
      showMainLoading: false,
      params: { code: code }
    });
  },

  canCreate() {
    return axios.get('authorization/learning-product/create');
  },

  canDelete(id) {
    return axios.get(`authorization/learning-product/delete/${id}`);
  },

  canEdit(id) {
    return axios.get(`authorization/learning-product/edit/${id}`);
  },

  canStartProcess(learningProductId, processDefinitionNameKey) {
    return axios.get(`authorization/learning-product/${learningProductId}/start-process/${processDefinitionNameKey}`);
  }
};
