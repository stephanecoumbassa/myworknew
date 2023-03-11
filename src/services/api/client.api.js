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

  static async update(params) {
    return await super.update(params, TABLE)
  }

  static async delete(params) {
    return await super.delete(params, TABLE)
  }

}

export {ClientApi};


