const base_url = 'http://127.0.0.1:8000';
var results;

async function getWithParams (url, params = null) {
    return await axios.get(base_url + url, { params: params })
        .then((response) => {
            return response.data;
        })
}

function postWithParams (url, params) {
    console.log(params);
    return axios.post(base_url + url, params)
        .then((response) => {
            return response;
        });
}

function putWithParams (url, params) {
    console.log(params);
    return axios.put(base_url + url, params)
        .then((response) => {
            return response;
        });
}

function deleteWithParams (url, params) {
    confirm('Etes vous sur de vouloir supprimer ?');
    return axios.delete(base_url + url, params)
        .then((response) => {
            return response;
        });
}
