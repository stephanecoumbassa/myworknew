import { defineStore  } from 'pinia'

// given two stores with the following ids
// const useUserStore = defineStore('user', {
// })
// const useCartStore = defineStore('cart', {
// })

export const baseStore = defineStore('basestore', {
  state: () => ({
    counter: 1,
    isLogged: false,
    user: {},
    products: [],
    clients: [],
    shops: [],
    shop: {},
  }),
  getters: {
    doubleCount: (state) => state.counter * 2,
    getClients: (state) => state.clients
  },
  actions: {
    increment() {
      this.counter++;
    },
    setClients(__clients) {
      this.clients = __clients
    }
  },
  persist: {
    enabled: true,
    storage: sessionStorage
  },
});
