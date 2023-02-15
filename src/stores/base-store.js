import { defineStore, mapStores  } from 'pinia'

// given two stores with the following ids
const useUserStore = defineStore('user', {
  // ...
})
const useCartStore = defineStore('cart', {
  // ...
})

export const useCounterStore = defineStore('basestore', {
  state: () => ({
    counter: 1,
    isLogged: false,
    user: {},
    products: [],
    shops: [],
    shop: {},
  }),
  getters: {
    doubleCount: (state) => state.counter * 2,
  },
  actions: {
    increment() {
      this.counter++;
    },
  },
  persist: {
    enabled: true,
    storage: sessionStorage
  },
});
