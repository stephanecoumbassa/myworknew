<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <q-card flat>

          <div class="text-center center">
            <img
              class="animate__animated animate__pulse animate__infinite animate__slower"
              src="~assets/fmmi.jpeg" style="height: 150px; width: 150px;">
          </div>
          <br>
          <!--          <div class="text-h6">Connexion</div>-->
          <br>
          <q-input v-model="myemail" aria-autocomplete autocomplete type="text" label="Email" />
          <q-input v-model="mypassword" type="password" label="Mot de passe" /><br><br>
          <q-btn size="full" block full class="bg-secondary text-white full-width" @click="connexion()">Se Connecter</q-btn>
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
      myemail: 'contact@fmmi.ci',
      mypassword: '12345',
      products: [{ p: { sell_price: 0, id: null }, quantity: 1, buy: 0, sell: 0 }],
      state: storeGlobal.state
    }
  },
  mixin: [basemixin],
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.buy * item.quantity), 0);
    }
  },
  created () {
    // this.users_type_get();
    // console.log(this.state);
  },
  methods: {
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
            this.$q.localStorage.set('userid', response.res.id);
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
  }
}
</script>

<style>
</style>
