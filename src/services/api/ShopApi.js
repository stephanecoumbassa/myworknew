
import {BaseApi} from "src/services/api/BaseApi";
const TABLE = 's_sales';
class ShopApi extends BaseApi{

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

  static async shopStats(first, last, shopid = 1) {
    return await super.getApi(`/my/get/shop_stats`,
      {}, true, true)
  }

}

export {ShopApi};


