<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <q-card>
          <q-tabs v-model="tab" dense class="text-grey" active-color="secondary" align="justify">
            <q-tab name="connexion" label="Mot de passe oublié" class="bg-dark text-white" />
            <q-tab name="inscription" label="Changer de mot de passe" class="bg-dark-separator text-secondary" />
          </q-tabs>

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="inscription">

              <div class="text-h6">Veuillez rentrer le code renitialisation</div>
              <q-input type="text" label="Email" v-model="email" />
              <q-input class="col-3" type="text" label="code" v-model="code" />
              <q-input type="password" label="Mot de passe"  v-model="password" />
              <br>

              <q-btn class="bg-secondary" @click="changer()">Valider</q-btn>

            </q-tab-panel>

            <q-tab-panel name="connexion">

              <div class="text-h6">Veuillez rentrer votre email</div>
              <q-input aria-autocomplete autocomplete type="text" label="Email" v-model="myemail" />
              <br>
              <q-btn size="full" block full class="bg-secondary full-width" v-on:click="reset()">Valider</q-btn>
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
import storeGlobal from '../stores/storeController.js'
export default {
    name: 'LoginPage',
    data () {
        return {
            name: null,
            tab: 'connexion',
            email: null,
            code: null,
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
        reset() {
            let params = { 'email': this.myemail };
            $httpService.postWithLogin('/api/password_reset', params)
                .then((response) => {
                    this.$q.notify({
                        color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
                    });
                })
        },
        changer() {
            let params = {
                'email': this.email,
                'password': this.password,
                'code': this.code
            };
            $httpService.postWithParams('/api/password_renew', params)
                .then((response) => {
                    if (parseInt(response['status']) === 1) {
                        this.$q.notify({
                            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
                        });
                        this.$router.push({ path: '/login' });
                    }
                })
        }
    }
}
</script>

<style>
</style>
