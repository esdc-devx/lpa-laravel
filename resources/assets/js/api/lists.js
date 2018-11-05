import _ from 'lodash';
import axios from '@axios/interceptor';
import store from '@/store';

export default {

  /**
   * Get the content of a single list.
   * @param {string} listName
   * @return {collection} - The requested list content.
   */
  async getList(listName) {
    return await axios.get('lists/' + listName).then(function(response) {
      return response.data.data;
    });
  },

  /**
   * Get the content of multiple lists at once.
   * @param {array} listNames - An array of list names.
   * @return {object} - The requested lists content.
   */
  async getLists(listNames) {
    return await axios.get('lists', { 
      params: { 
        include: listNames 
      } 
    })
    .then(function(response) {
      return response.data.data;
    });
  }

};