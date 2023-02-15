import axios from 'axios';
import { LocalStorage } from 'quasar';

// export let baseurl = 'https://affairez.com/apistock';
// export let baseurl = 'https://babiprints.com/apistock';
export let baseurl = 'https://fmmi.ci/apistock';
export const baseurl2 = 'http://localhost:8000';
// export let baseurl = 'https://batison.com/apistock';
const headers = {
	headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
};

export function getWithParams(url, params = null) {
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
		return response.data;
	});
}

export function postWithSimple(url, params) {
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
}

export function deleteWithParams(url, params) {
	// if (!navigator.onLine) {
	//   baseurl = baseurl2;
	// }
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
