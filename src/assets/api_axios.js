import axios from 'axios';
import {Loading, LocalStorage, Notify} from 'quasar';
import { BASEURL } from "../constante";

export let baseurl = BASEURL;
export const baseurl2 = 'http://localhost:8000';
const headers = {
  headers: { Authorization: 'Bearer ' + LocalStorage.getItem('token') }
};

export function getWithParams(url, params = null) {
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  return http.get(baseurl + url, { params: params, headers }).then(response => {
    return response.data;
  });
}
export function getApi(url, params = null) {
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  return http.get(baseurl + url, { params: params, headers }).then(response => {
    return response.data;
  });
}

export function postWithParams(url, params) {
  Loading.show()
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  let myInterceptorPost = http.interceptors.request.use(function(config) {
    return config;
  });
  let myResponseInterceptor = http.interceptors.response.use(function(response) {
    return response;
  });

  return axios.post(baseurl + url, params, headers)
    .then(response => {
      http.interceptors.request.eject(myInterceptorPost);
      http.interceptors.response.eject(myResponseInterceptor);
      Loading.hide()
      Notify.create({color: 'green', position: 'top', message: response.data['msg'], icon: 'info'});
      return response.data;
    }).catch((error) => {
      Notify.create({color: 'red', position: 'top', message: error.message, icon: 'info'});
      Loading.hide()
    });
}

export function postWithSimple(url, params) {
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  var promise1 = http.post(baseurl + url, params, headers).then(response => {
    return response.data;
  });

  return Promise.all([promise1]).then(function(values) {
    return values;
  });
}

export function postWithGlobal(url, params) {
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  return http.post(url, params, headers).then(response => {
    return response.data;
  });
}

export function putWithParams(url, params) {
  Loading.show()
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  return http
    .put(baseurl + url, params, headers)
    .then(response => {
      Loading.hide()
      Notify.create({color: 'green', position: 'top', message: response.data['msg'], icon: 'info'});
      return response.data;
    })
    .catch((error) => {
      Loading.hide()
      Notify.create({color: 'red', position: 'top', message: error.message, icon: 'info'});
      return 'Connexion Impossible';
    });
}

export function deleteWithParams(url, params) {
  Loading.show()
  const headers = {
    headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
  };
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  });
  return http.delete(baseurl + url, { params: params, headers })
    .then(response => {
      Loading.hide()
      Notify.create({color: 'green', position: 'top', message: response.data['msg'], icon: 'info'});
      return response.data;
    }).catch((error) => {
      Loading.hide()
      Notify.create({color: 'red', position: 'top', message: error.message, icon: 'info'});
      return 'Connexion Impossible';
    });
}

export function postWithLogin(url, params) {
  // if (!navigator.onLine) {
  //   baseurl = baseurl2;
  // }
  let token = LocalStorage.getItem('token');
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token }
  });
  let myInterceptorPost = http.interceptors.request.use(function(config) {
    return config;
  });
  let myResponseInterceptor = http.interceptors.response.use(function(response) {
    return response;
  });
  return axios.post(baseurl + url, params, headers).then(response => {
    http.interceptors.request.eject(myInterceptorPost);
    http.interceptors.response.eject(myResponseInterceptor);
    return response.data;
  });
}
