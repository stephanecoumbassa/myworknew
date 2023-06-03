<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <q-card>
          <q-tabs v-model="tab" dense class="text-grey" active-color="primary" align="justify" narrow-indicator>
            <q-tab name="connexion" label="Creation de la boutique" class="bg-blue-grey-14 text-white" />
          </q-tabs>

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="connexion">
              <div class="row">
                <div class="text-h6">Creation du magasin</div>
                <q-input v-model="myname" class="col-12" type="text" label="Nom du magasin" />
                <q-input v-model="myemail" class="col-12" type="text" label="Email" />
                <q-input v-model="mycode" class="col-4 q-pa-xs" type="text" label="Indicatif" />
                <q-input v-model="myphone" class="col-8 q-pa-xs" type="number" label="Telephone" />
                <q-input v-model="mypassword" class="col-12" type="password" label="Mot de passe" /><br>
              </div>
              <q-btn size="full" block full class="bg-blue-grey-14 full-width q-mt-lg" @click="connexion()">Valider</q-btn>
            </q-tab-panel>

          </q-tab-panels>
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
            tab: 'connexion',
            myemail: null,
            mytelephone: null,
            mypassword: null,
            myname: null,
            myphone: null,
            mycode: 225,
            users_types: [],
            products: [{ p: { sell_price: 0, id: null }, quantity: 1, buy: 0, sell: 0 }],
        }
    },
    mixin: [basemixin],
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
                'name': this.myname,
                'email': this.myemail,
                'telephone_code': this.mycode,
                'telephone': this.mytelephone,
                'password': this.mypassword
            };
            $httpService.postWithLogin('/api/post/shop', params)
                .then((response) => {
                    if (parseInt(response['status']) === 1) {
                        this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
                    } else {
                        this.$q.notify({ color: 'red', position: 'top', message: response.msg, icon: 'report_problem' });
                    }
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
