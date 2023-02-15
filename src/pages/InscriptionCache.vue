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

                        <q-input type="text" label="Nom" v-model="name"></q-input>
                        <q-input type="text" label="Prenom" v-model="lastname"></q-input>
                        <q-input type="text" label="Email" v-model="email"></q-input>
                        <q-input class="col-3" type="text" label="indicatif" v-model="indicatif"></q-input>
                        <q-input class="col-9" type="text" label="Telephone" v-model="telephone"></q-input>
                        <q-select filled map-options emit-value :options="users_types"  v-model="user_type" label="Type utilisateur" option-value="id" stack-label input-debounce="0"
                                  option-label="name"></q-select>
                        <q-input type="text" label="ID Magasin" v-model="shop_id" ></q-input>
                        <q-input type="password" label="Mot de passe"  v-model="password"></q-input><br>

                        <q-btn class="bg-blue-grey-14" @click="inscription()">Valider</q-btn>

                    </q-tab-panel>

                    <q-tab-panel name="connexion">

                        <div class="text-h6">Connexion</div>
                        <q-input type="text" label="Email" v-model="myemail"></q-input>
                        <q-input type="password" label="Mot de passe" v-model="mypassword"></q-input><br>

                        <q-btn size="full" block full class="bg-blue-grey-14 full-width" v-on:click="connexion()">Valider</q-btn>
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
            products: [{ p: { sell_price: 0, id: null }, quantity: 1, buy: 0, sell: 0 }]
        }
    },
    mixin: [],
    created () {
        // this.users_get();
        this.users_type_get();
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
