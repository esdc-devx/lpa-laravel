import axios from '@axios/interceptor';

export default {
  /**
   * Get the content of a single list.
   * @param {string} listName
   * @return {collection} - The requested list content.
   */
  async getList(listName) {
    let response = await axios.get('lists/' + listName);
    return response.data;
  },

  /**
   * Get the content of multiple lists at once.
   * @param {array} listNames - An array of list names.
   * @return {object} - The requested lists content.
   */
  async getLists(listNames) {
    let response = await axios.get('lists', {
      params: {
        include: listNames
      }
    });
    return response.data;
  }
};
