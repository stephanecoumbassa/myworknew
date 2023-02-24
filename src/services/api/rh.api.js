import {getApi, postApi} from "src/services/apiService";

export async function p_task_get (id=null) {
  return new Promise(resolve => {
    getApi('/api/get/p_task')
      .then(response => resolve(response) )
      .catch(error => error);
  });
}

export async function p_task_id_get (id) {
  return new Promise(resolve => {
    getApi('/api/get/p_task/'+id)
      .then(response => resolve(response) )
      .catch(error => error);
  });
}

export async function p_task_post () {
  await postApi('/api/post/p_task', this.p_task)
    .then(response => response).catch(error => error)
}

export async function employeGetService (id=null) {
  return new Promise(resolve => {
    getApi('/api/get/p_employe')
      .then(response => resolve(response) )
      .catch(error => error);
  });
}
