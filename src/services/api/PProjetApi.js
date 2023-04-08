
import {BaseApi} from "src/services/api/BaseApi";
const TABLE = 'p_projet';
class PProjetApi extends BaseApi{

  constructor() {
    super();
  }

  static async get() {
    return await super.get(TABLE)
  }
  
  static async getId(id) {
    return await super.getId(TABLE, id)
  }

  static async search(params) {
    return await super.search(TABLE, params)
  }
  
  static async post(params) {
    return await super.post(TABLE, params)
  }

  static async update(params) {
    return await super.update(TABLE, params)
  }

  static async delete(id) {
    return await super.delete(TABLE, id)
  }

}

export {PProjetApi};


