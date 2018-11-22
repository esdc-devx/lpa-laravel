import axios from '@axios/interceptor';

export default {
  /**
   * Get the content of a single list.
   * @param {string} listName
   * @return {collection} - The requested list content.
   */
  getList(listName) {
    return axios.get('lists/' + listName);
  },

  /**
   * Get the content of multiple lists at once.
   * @param {array} listNames - An array of list names.
   * @return {object} - The requested lists content.
   */
  getLists(listNames) {
    return axios.get('lists', {
      params: {
        include: listNames
      }
    });
  }
};
