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
              <q-input v-model="email" type="text" label="Email" />
              <q-input v-model="code" class="col-3" type="text" label="code" />
              <q-input v-model="password" type="password"  label="Mot de passe" />
              <br>

              <q-btn class="bg-secondary" @click="changer()">Valider</q-btn>

            </q-tab-panel>

            <q-tab-panel name="connexion">

              <div class="text-h6">Veuillez rentrer votre email</div>
              <q-input v-model="myemail" aria-autocomplete autocomplete type="text" label="Email" />
              <br>
              <q-btn size="full" block full class="bg-secondary full-width" @click="reset()">Valider</q-btn>
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
            tab: 'connexion',
            email: null,
            code: null,
            myemail: null,
            password: null,
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
        // this.users_type_get();
        // console.log(this.state);
    },
    methods: {
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
