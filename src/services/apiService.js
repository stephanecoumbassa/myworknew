import axios from 'axios'
import {LocalStorage} from "quasar";
import { BASEURL } from "../constante";

// const baseurl = 'https://fmmi.ci/apistock';
// const baseurl = 'https://127.0.0.1:8000';
const baseurl = BASEURL;

// const headers = { headers: { 'Authorization': 'Bearer ' + LocalStorage.getItem('token') } }

export async function getApi (url, params = {}) {
  const headers = { headers: { 'Authorization': 'bearer ' + LocalStorage.getItem('token') } }
  let token = LocalStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { 'Authorization': 'Bearer ' + token, 'Shopid': 1 }
  })
  return await http.get(baseurl + url, { params })
    .then((response) => {
      return response.data
    })
}

export function postApi (url, params) {
  let token = LocalStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { 'Authorization': 'Bearer ' + token }
  })
  const formData = new FormData()
  Object.keys(params).forEach(key => {
    if (params[key] == null) {
      console.log(key)
    } else {
      formData.append(key, params[key])
    }
  })

  return http.post(baseurl + url, formData)
    .then((response) => {
      return response.data
    })
}

export function postSimple (url, params) {
  const headers = { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') } }
  const token = localStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token }
  })
  const promise1 = http.post(baseurl + url, params, headers)
    .then((response) => {
      return response.data
    })

  return Promise.all([promise1]).then((values) => {
    return values[0]
  })
}

export function postGlobal (url, params) {
  const token = localStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  })
  return http.post(url, params)
    .then((response) => {
      return response.data
    })
}

export function putApi (url, params) {
  const headers = { headers: { Authorization: 'Bearer ' + localStorage.getItem('token') } }
  const token = localStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
  })
  return http.put(baseurl + url, params, headers)
    .then((response) => {
      return response.data
    }).catch(() => {
      return 'Connexion Impossible'
    })
}

export function deleteApi (url, params) {
  const token = localStorage.getItem('token')
  const http = axios.create({
    baseURL: baseurl,
    headers: { Authorization: 'Bearer ' + token }
  })
  // return http.delete(baseurl + url, { params: params, headers })
  return http.delete(baseurl + url, { params })
    .then((response) => {
      return response.data
    })
}


export default {
  getApi,
  postApi,
  postSimple,
  postGlobal,
  putApi,
  deleteApi,
  // postApiLogin
}
