
import {BaseApi} from "src/services/api/BaseApi";
const TABLE = 's_product';
class ProductApi extends BaseApi{

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

  static async disabledProduct(id) {
    return await super.postApi('/my/disabled/products/'+id, {}, true, true)
  }

}

export {ProductApi};


