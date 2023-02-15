<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <q-card flat>

          <div class="text-center center">
            <img class="animate__animated animate__pulse animate__infinite animate__slower"
                 src="~assets/fmmi.jpeg" style="height: 150px; width: 150px;">
          </div>
          <br>
<!--          <div class="text-h6">Connexion</div>-->
          <br>
          <q-input aria-autocomplete autocomplete type="text" label="Email" v-model="myemail" />
          <q-input type="password" label="Mot de passe" v-model="mypassword" /><br><br>
          <q-btn size="full" block full class="bg-secondary text-white full-width" v-on:click="connexion()">Se Connecter</q-btn>
          <br>
          <br>
          <br>

          <q-btn to="/mot-de-passe-oublie" class="text-secondary text-center" flat>Mot de passe oublié</q-btn>

        </q-card>

      </div>
    </div>

  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin';
import storeGlobal from '../stores/storeController'
export default {
  name: 'LoginPage',
  data () {
    return {
      name: null,
      tab: 'connexion',
      email: null,
      myemail: null,
      indicatif: null,
      telephone: null,
      mytelephone: null,
      lastname: null,
      shop_id: null,
      password: null,
      mypassword: null,
      user_type: null,
      fullWidth: false,
      medium: false,
      medium2: false,
      agent: null,
      fournisseur: null,
      product_id: null,
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      users: [],
      users_types: [],
      products: [{ p: { sell_price: 0, id: null }, quantity: 1, buy: 0, sell: 0 }],
      state: storeGlobal.state
    }
  },
  mixin: [basemixin],
  created () {
    // this.users_type_get();
    // console.log(this.state);
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.buy * item.quantity), 0);
    }
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.command_post();
      } else {
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'fas fa-check-circle',
          message: 'Submitted'
        })
      }
    },
    connexion() {
      let params = {
        'username': this.myemail,
        'email': this.myemail,
        'password': this.mypassword
      };
      $httpService.postWithLogin('/api/connexion', params)
        .then((response) => {
          if (parseInt(response['status']) === 1) {
            this.$q.localStorage.set('token', response.token);
            this.$q.localStorage.set('token2', response.token2);
            localStorage.setItem('token3', response.token);
            this.$q.localStorage.set('current_user', response.res);
            localStorage.setItem('current_user2', response.res);
            this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });

            this.state.token = response['token'];
            this.state.token2 = response.token2;
            this.state.current_user = response;

            this.$q.cookies.set('current_user', response);
            this.$q.cookies.set('token', response['token']);
            this.$q.cookies.set('token2', response['token2']);
            this.$router.push({ path: '/qstock' });
            // window.location.replace('/qstock');
          } else {
            this.$q.notify({ color: 'red', position: 'top', message: response.msg, icon: 'report_problem' });
          }
        })
    },
    inscription() {
      let params = {
        'name': this.name,
        'lastname': this.lastname,
        'email': this.email,
        'telephone_code': this.indicatif,
        'telephone': this.telephone,
        'shop_id': this.shop_id,
        'magasin_id': this.shop_id,
        'type': this.user_type,
        'password': this.password
      };
      $httpService.postWithParams('/api/inscription', params)
        .then((response) => {
          this.products_get();
          this.$q.notify({
            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
          });
        })
    },
    users_type_get () {
      $httpService.getWithParams('/api/s_type_users')
        .then((response) => {
          this.users_types = response;
        })
    }
  }
}
</script>

<style>
</style>
