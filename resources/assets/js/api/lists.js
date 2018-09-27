import _ from 'lodash';
import axios from '@axios/interceptor';
import store from '@/store';

export default {

  //
  // Returns the content of a single list in an array.
  //
  async getList(listName) {
    return await axios.get('lists/'+listName).then(function(response){
      return response.data.data;
    });
  },

  //
  // Return the content of multiple lists in an indexed JSON
  //
  async getLists(listNamesArray) {
    
    let parameters = '';
    let listNumber = listNamesArray.length;
    let i;
    
    // Build the request parameters list.
    for (i = 0; i < listNumber; i++) {
        if (i > 0){
            parameters += '&';  
        } 
        parameters += 'include[]='+listNamesArray[i];
    }

    return await axios.get('lists?'+parameters).then(function(response){
        return response.data.data;
    });
  }

};