<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <!--        <q-card>-->
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="inscription" label="Inscription" class="bg-blue-grey-14 text-white" />
          <q-tab name="connexion" label="Authentification" class="bg-blue-grey-14 text-white" />
        </q-tabs>

        <!--          <q-separator />-->

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="inscription">

            <div class="text-h6">Inscription</div>

            <q-input v-model="name" type="text" label="Nom"></q-input>
            <q-input v-model="lastname" type="text" label="Prenom"></q-input>
            <q-input v-model="email" type="text" label="Email"></q-input>
            <q-input v-model="indicatif" class="col-3" type="text" label="indicatif"></q-input>
            <q-input v-model="telephone" class="col-9" type="text" label="Telephone"></q-input>
            <q-select
              v-model="user_type" filled map-options emit-value  :options="users_types" label="Type utilisateur" option-value="id" stack-label input-debounce="0"
              option-label="name"></q-select>
            <q-input v-model="shop_id" type="text" label="ID Magasin" ></q-input>
            <q-input v-model="password" type="password"  label="Mot de passe"></q-input><br>

            <q-btn class="bg-blue-grey-14" @click="inscription()">Valider</q-btn>

          </q-tab-panel>

          <q-tab-panel name="connexion">

            <div class="text-h6">Connexion</div>
            <q-input v-model="myemail" type="text" label="Email"></q-input>
            <q-input v-model="mypassword" type="password" label="Mot de passe"></q-input><br>

            <q-btn size="full" block full class="bg-blue-grey-14 full-width" @click="connexion()">Valider</q-btn>
          </q-tab-panel>

        </q-tab-panels>
        <!--        </q-card>-->


      </div>
    </div>

  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
export default {
  name: 'InscriptionPage',
  data () {
    return {
      name: null,
      tab: 'connexion',
      email: null,
      myemail: null,
      indicatif: null,
      telephone: null,
      lastname: null,
      shop_id: null,
      password: null,
      mypassword: null,
      user_type: null,
      users_types: [],
      products: [{ p: { sell_price: 0, id: null }, quantity: 1, buy: 0, sell: 0 }]
    }
  },
  mixin: [],
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.buy * item.quantity), 0);
    }
  },
  created () {
    this.users_type_get();
  },
  methods: {
    connexion() {
      let params = {
        'username': this.myemail,
        'email': this.myemail,
        'password': this.mypassword
      };
      $httpService.postWithParams('/api/connexion', params)
        // $httpService.postWithParams('/api/login', params)
        .then((response) => {
          if (response['status'] === 1) {
            this.$q.localStorage.set('token', response.token);
            this.$q.localStorage.set('token2', response.token2);
            this.$q.localStorage.set('current_user', response.res);
            this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
            window.location.replace('/');
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
          // console.log(response);
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
          // console.log(response);
        })
    }
  }
}
</script>

<style>
</style>
