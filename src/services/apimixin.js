import {LocalStorage} from "quasar";
import axios from "axios";
import { BASEURL } from "../constante";

const baseurl = BASEURL;

const apimixin = {
  data () {
    return {
      alerte: { titre: 'testing' },
      classes: []
    }
  },
  methods: {

    getApi(url, params = null) {
      this.$q.loading.show()
      const headers = {
        headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
      };
      let token = LocalStorage.getItem('token');
      const http = axios.create({
        baseURL: baseurl,
        headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
      });
      return http.get(baseurl + url, { params: params, headers }).then(response => {
        this.$q.loading.hide()
        return response.data;
      });
    },

    postApi(url, params) {
      this.$q.loading.show()
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

      return axios.post(baseurl + url, params, headers).then(response => {
        http.interceptors.request.eject(myInterceptorPost);
        http.interceptors.response.eject(myResponseInterceptor);
        this.$q.loading.hide()
        return response.data;
      });
    },

    putApi(url, params) {
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
          return response.data;
        })
        .catch(() => {
          return 'Connexion Impossible';
        });
    },

    deleteApi(url, params) {
      const headers = {
        headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
      };
      let token = LocalStorage.getItem('token');
      const http = axios.create({
        baseURL: baseurl,
        headers: { Authorization: 'Bearer ' + token, Shopid: 1 }
      });
      return http.delete(baseurl + url, { params: params, headers }).then(response => {
        return response.data;
      });
    },

  }
}

export default apimixin
