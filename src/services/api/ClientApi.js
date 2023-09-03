import {BaseApi} from "src/services/api/BaseApi";
const TABLE = 'client';
class ClientApi extends BaseApi{

  constructor() {
    super();
  }

  static async get() {
    return await super.get(TABLE)
  }

  static async post(params) {
    return await super.post(params, TABLE)
  }

  static async modify(params) {
    return await super.update('/my/put/client', params, true, true)
  }

  static async delete(params) {
    return await super.delete(params, TABLE)
  }

  static async stats(datemin, datemax) {
    return await super.postApi(`/my/get/client_stats`,
      {datemin, datemax}, true, false)
  }

  static async create (params) {
    return await super.postApi(`/api/register/client`,
      params, true, true)
  }



}

export {ClientApi};
