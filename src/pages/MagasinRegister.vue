<template>
  <q-page class="flex content-center justify-center">

    <div class="q-pa-md" style="width: 500px">
      <div class="q-gutter-y-md">
        <q-card>
          <q-tabs v-model="tab" dense class="text-grey" active-color="primary" align="justify" narrow-indicator>
            <q-tab name="connexion" label="Creation de la boutique" class="bg-blue-grey-14 text-white" />
            <!--            <q-tab name="inscription" label="Inscription" class="bg-blue-grey-14 text-white" />-->
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
            myname: null,
            myphone: null,
            mycode: 225,
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
    computed: {
        total() {
            return this.products.reduce((product, item) => product + (item.buy * item.quantity), 0);
        }
    },
    created () {
        this.users_type_get();
        // console.log(this.state);
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
                'name': this.myname,
                'email': this.myemail,
                'telephone_code': this.mycode,
                'telephone': this.mytelephone,
                'password': this.mypassword
            };
            $httpService.postWithLogin('/api/post/shop', params)
                .then((response) => {
                    if (parseInt(response['status']) === 1) {
                        // this.$q.localStorage.set('token', response.token);
                        // this.$q.localStorage.set('token2', response.token2);
                        // this.$q.localStorage.set('current_user', response.res);
                        // localStorage.setItem('current_user2', response.res);
                        this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });

                        // this.state.token = response['token'];
                        // this.state.token2 = response.token2;
                        // this.state.current_user = response;
                        //
                        // this.$q.cookies.set('current_user', response);
                        // this.$q.cookies.set('token', response['token']);
                        // this.$q.cookies.set('token2', response['token2']);
                        this.$router.push({ path: '/login' });
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
