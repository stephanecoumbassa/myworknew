import {deleteApi, getApi, postApi, putApi} from "src/services/apiService";
import {Loading, Notify, QSpinnerBall} from 'quasar'

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


  static async getApi (url, params, loading=false, notify=false) {
    if (loading)
      Loading.show({spinner: QSpinnerBall, spinnerColor: 'primary'})
    return new Promise((resolve, reject) => {
      getApi(url, params )
        .then((response) => {
          Loading.hide()
          resolve(response)
        })
        .catch((error) => {
          Loading.hide()
          reject(error)
        });
    });
  }
  static async postApi(url, params, loading=false, notify=false) {
    if (loading) Loading.show({spinner: QSpinnerBall, spinnerColor: 'primary'})
    return new Promise( resolve => {
      postApi(url, params)
        .then((response) => {
          Loading.hide()
          Notify.create({color: 'green', position: 'top', message: response['msg'], icon: 'info'});
          return resolve(response)
        }).catch((error) => {
        Loading.hide()
        return resolve(error)
      });
    })
  }

  static async update(url, params, loading=false, notify=false) {
    if (loading) Loading.show({spinner: QSpinnerBall, spinnerColor: 'primary'})
    return new Promise( resolve => {
      putApi(url, params)
        .then((response) => {
          console.log(response)
          Loading.hide()
          Notify.create({color: 'green', position: 'top', message: response['msg'], icon: 'info'});
          return resolve(response)
        }).catch((error) => {
        Loading.hide()
        return resolve(error)
      });
    })
  }

}

export {BaseApi};


