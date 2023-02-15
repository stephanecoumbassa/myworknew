import $httpService from '../boot/httpService'

class StoreController {
    constructor () {
        this.state = {
            role: '',
            current_user: {},
            entreprise: {},
            token: null,
            token2: null
        }
    }
    shopGet() {
        $httpService.postWithParams('/api/get/shop/1')
            .then((response) => {
                this.state.entreprise = response;
            })
    }
}

var storeGlobal = new StoreController()
export default storeGlobal
