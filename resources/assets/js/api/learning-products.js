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
    //return axios.get('learning-products/create');
    return { 
      "data": {
        "data": { 
          "projects": [{
            "id": 1,
            "name": "Est consequuntur veniam adipisci doloremque assumenda hic",
            "available_learning_product_types": [{
              "type_id": 1,
              "sub_type_id": 3,
              "quantity": 1
            }, {
              "type_id": 1,
              "sub_type_id": 4,
              "quantity": 1
            }]
          }, {
            "id": 3,
            "name": "Est consequatur ut magnam quam id voluptatem ea",
            "available_learning_product_types": [{
              "type_id": 1,
              "sub_type_id": 4,
              "quantity": 1
            }]
          }, {
            "id": 4,
            "name": "Iusto dolores vel ducimus error harum",
            "available_learning_product_types": [{
              "type_id": 1,
              "sub_type_id": 2,
              "quantity": 1
            }]
          }, {
            "id": 5,
            "name": "Amet dolorem nihil maxime ea in",
            "available_learning_product_types": [{
              "type_id": 1,
              "sub_type_id": 3,
              "quantity": 2
            }]
          }],
          "organizational_units": [{
            "id": 1,
            "parent_id": 0,
            "name_key": "owner-1",
            "active": 1,
            "owner": true,
            "email": "zsatterfield@gmail.com",
            "director": 5,
            "created_at": "2018-09-21 09:24:35",
            "updated_at": "2018-09-21 09:24:35",
            "name": "Communaut\u00e9 fonctionelle 1"
          }]
        },
        "meta": []
      }
    }
  },

  canCreateLearningProduct() {
    //return axios.get('authorization/learning-product/create');
    return {
      data: {
        data: { 
          allowed: true
        }
      }
    }
  },

  create(learningProduct) {
    return axios.post('learning-produts', learningProduct);
  }
};
