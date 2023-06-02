import {deleteApi, getApi, postApi} from "src/services/apiService";

class BaseApi {

  static async get (table) {
    return new Promise((resolve, reject) => {
      getApi('/my/get/'+ table )
        .then(response => resolve(response) )
        .catch(error => reject(error));
    });
  }

  static async getId (table, id) {
    return new Promise((resolve, reject) => {
      getApi('/my/get/'+ table+'/'+id )
        .then(response => resolve(response) )
        .catch(error => reject(error));
    });
  }

  static async search (table, params) {
    return new Promise((resolve, reject) => {
      postApi('/my/search/'+ table, params )
        .then(response => resolve(response) )
        .catch(error => reject(error));
    });
  }

  static async post(table, params) {
    return new Promise( resolve => {
      postApi('/my/post/' +table, params)
        .then((response) => {
          return resolve(response)
        }).catch((error) => {
        return resolve(error)
      });
    })
  }

  static async update(table, params) {
    return new Promise( resolve => {
      putApi('/my/put/' + table, params)
        .then((response) => {
          return resolve(response)
        }).catch((error) => {
        return resolve(error)
      });
    })
  }

  static async delete(table, id) {
    return new Promise( resolve => {
      deleteApi('/my/delete/'+ table +'/'+id)
        .then((response) => {
          return resolve(response)
        }).catch((error) => {
        return resolve(error)
      });
    })
  }

}

export {BaseApi};


