<template>

  <q-page>
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-12 col-12">

        <q-tabs
          v-model="tab" dense class="text-dark"
          active-color="secondary" indicator-color="secondary" align="justify" narrow-indicator>
          <q-tab name="alarms" label="Nouvelle vente" @click="products = [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }]" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>

          <q-tab-panel name="alarms">

            <div class="row">

              <div class="col-md-6 col-sm-12 q-pa-md">
                <div class="row">

                  <div class="col-12 q-pb-md">
                    <q-input v-model="filter2" label="Rechercher" @input="filterFn"></q-input>
                  </div>

                  <div v-for="(item, index) in appro_list" :key="index" class="col-lg-4 col-md-6 col-sm-6 col-xs-12 q-pa-sm" clickable>
                    <q-card class="my-card" clickable @click="assign_product(item)">
                      <q-item clickable class="no-padding no-margin">
                        <q-item-section avatar>
                          <img
                            v-if="item.photos" style="width: 70px; height: 70px; object-fit: cover" loading="lazy"
                            :src="uploadurl+'/'+entreprise.id+'/product/'+JSON.parse(item.photos)[0]['name']" />
                        </q-item-section>
                        <q-card-section>
                          <div class="text-subtitle2">{{item.name}}</div>
                        </q-card-section>
                      </q-item>
                    </q-card>
                  </div>

                </div>
              </div>

              <div class="col-md-6 col-sm-12 q-pa-sm">

                <q-form @submit="onSubmit">

                  <div class="col-lg-12 q-mb-md">
                    <q-card class="my-card">
                      <q-item>
                        <q-card-section>
                          <div class="text-h6">{{entreprise.name}}</div>
                          Comptoir N° 5 <br>

                          <div class="float-right q-mb-sm print-hide">
                            <q-input v-model="dateposted" stack-label type="datetime-local" label="Date" :dense="true"></q-input>
                            <br>
                            <q-select
                              v-if="status_download" v-model="client" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                              :dense="true" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                              input-debounce="0" :option-label="'fullname'" :rules="[val => !!val || 'Ce champs est requis']" @input="assign_client(client)" />
                          </div>

                        </q-card-section>
                      </q-item>
                    </q-card>
                  </div>

                  <q-card v-if="total" class="my-card q-mb-md">
                    <q-item>
                      <q-card-section>
                        <div v-for="(prod, index) in products" :key="index" class="row q-mb-xs">
                          <q-input v-model="prod.name" borderless readonly class="col-12 bold" :dense="true" type="text" />
                          <q-input
                            v-model="prod.quantity" class="col-3 q-pr-sm" :dense="true" type="number"
                            @input="getVal(index, prod.quantity)" />
                          <q-input class="col-1 q-pr-sm" :dense="true" label="X"></q-input>
                          <q-input v-model="prod.sales_price" class="col-3 q-pr-sm" :dense="true" type="number" />
                          <q-input borderless readonly class="col-4 q-pr-sm" :dense="true" type="number" :value="prod.sales_price * prod.quantity" />
                          <div class="col-1"><br>
                            <q-btn v-if="status_download" round color="negative" size="xs" icon="remove" class="print-hide" @click="delete_product(index)" />
                          </div>
                          <hr>
                        </div>
                      </q-card-section>
                    </q-item>
                  </q-card>

                  <q-card v-if="total" class="my-card">
                    <q-item>
                      <q-card-section style="width: 100%">
                        <div class="row">
                          <q-input v-if="credit" v-model="avance" class="col-6 q-ma-md" :dense="true" type="number" label="Avance"/><br>
                          <div class="text-h6 col-6">Total</div>
                          <div class="col-6 text-h6">{{ numerique(Math.round(total)) }} FCFA</div>
                          <br>
                          <div class="col-12 q-mt-md">
                            <q-btn color="secondary" icon="money" type="submit">&nbsp;Encaisser</q-btn> &nbsp;
                            <!--                                                        <q-btn v-on:click="credit = !credit">Credit</q-btn> &nbsp;-->
                            <q-btn>Notes</q-btn> &nbsp;
                            <q-btn icon="receipt" label="Facture" @click="get_facture_id(facture_number); facture_status2 = true" /> &nbsp;
                            <q-btn>Annuler</q-btn> &nbsp;
                            <q-checkbox v-model="credit" label="Vente à Credit" color="red" />
                          </div>
                        </div>
                      </q-card-section>
                    </q-item>
                  </q-card>

                </q-form>

              </div>

            </div>

          </q-tab-panel>
        </q-tab-panels>

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture
              name="Facture de vente" :myentreprise="entreprise"
              :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>
        <!--        <q-btn class="q-mb-sm" size="sm" label="Ajouter" icon="add" color="secondary" @click="fullWidth = true; validate_status = true" /><br>-->
      </div>
    </div>
    <br>

  </q-page>

</template>

<script>


import $httpService from '../boot/httpService';
// import VueQr from 'vue-qr';
// import htmlToImage from 'html-to-image';
// import JsonExcel from 'vue-json-excel';
import basemixin from './basemixin';
import * as _ from 'lodash';
import FactureComponent from '../components/facture_component.vue';
export default {
  components: {
    // 'downloadExcel': JsonExcel,
    'facture': FactureComponent
  },
  mixins: [basemixin],
  data () {
    return {
      tab: 'alarms',
      filter2: '',
      facture_status2: false,
      status_download: true,
      validate_status: true,
      fullWidth: false,
      credit: false,
      avance: 0,
      agent: null,
      facture_number: null,
      dateposted: '',
      client: 1,
      myclient: {},
      image: '',
      clients: [],
      products: [],
      sales_list: [],
      appro_list: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      appro_list2: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      entreprise: {}
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.sales_price * item.quantity + (item.tva * item.sales_price * item.quantity)), 0);
    }
  },
  watch: {
    // currentCity: function(newCity, oldCity) {
    //   this.getWeather();
    // }
  },
  created () {
    let date = new Date();
    this.shop_get();
    this.clients_get();
    this.products_get();
    this.sales_get();
    this.factures_number_get();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    this.dateposted = date.toISOString().slice(0, 16);
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'fas fa-check-circle',
          message: 'Submitted'
        })
      }
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign_product (item) {
      if (item.reste <= 0) {
        this.$q.notify({ color: 'dark', position: 'top', message: 'Le produit est en rupture de stock' });
        return false;
      }
      item.quantity = 1;
      this.products.push(item);
      let summedAges = this.products.reduce((a, c) => {
        let filtered = a.filter(el => el.id === c.id);
        if (filtered.length > 0) {
          a[a.indexOf(filtered[0])].quantity += +c.quantity;
        } else {
          a.push(c);
        }
        return a;
      }, []);
      this.products = summedAges;
    },
    assign_client (client) {
      this.myclient.id = client.id;
      this.myclient.fullname = client.fullname;
      this.myclient.email = client.email;
      this.myclient.telephone = client.telephone;
      this.myclient.telephone_code = client.telephone_code;
    },
    clients_get () {
      $httpService.getWithParams('/my/get/client')
        .then((response) => {
          this.clients = response;
          this.client = this.clients[0];
          this.myclient = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    sales_post() {
      let params = { agent: this.agent,
        products: this.products,
        id_vente: this.facture_number,
        clientid: this.myclient.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total,
        dateposted: this.dateposted
      };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/sales', params)
          .then((response) => {
            var status = response['statut'];
            if (status == !0) {
              $httpService.postWithParams('/my/post/qr_code', {
                id: response['id'], typerubrique: 3, file: this.image
              });
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.validate_status = false;
              this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.appro_list = response;
          this.appro_list2 = response;
        })
    },
    factures_number_get () {
      $httpService.getWithParams('/my/get/facture_number')
        .then((response) => {
          this.facture_number = response['nb'];
        })
    },
    sales_get () {
      $httpService.getWithParams('/my/get/sales')
        .then((response) => {
          this.sales_list = response;
        })
    },
    getVal(index, val) {
      this.products[index].quantity = parseInt(val);
      this.products.push({});
      this.products.pop();

      if (this.products[index].reste <= 0) {
        this.$q.notify({ color: 'dark', position: 'top', message: 'Le produit est en rupture de stock' });
        return false;
      } else if (this.products[index].reste < this.products[index].quantity) {
        this.products[index].quantity = this.products[index].reste;
        this.$q.notify({ color: 'dark', position: 'top', message: 'il reste ' + this.products[index].reste + ' en stock, Veuillez ne pas depasser la quantité en stock' });
        return false;
      } else if (this.products[index].quantity <= 0) {
        this.products[index].quantity = 1;
        this.$q.notify({ color: 'dark', position: 'top', message: 'Veuillez rentrer un nombre positif' });
        return false;
      }
    },
    delete_product(i) {
      this.products = this.products.filter((x) => {
        return x.id !== this.products[i].id;
      });
    },
    filterFn () {
      const needle = this.filter2.toLocaleLowerCase();
      this.appro_list = this.appro_list2.filter((v) => {
        return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1;
      });
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      // this.medium2 = true;
      this.factures_get_id(_id);
    },
    factures_get_id (factureid) {
      $httpService.getWithParams('/my/get/sales_by_idvente?id_vente=' + factureid, { })
        .then((response) => {
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            response[i].p.id = response[i].p_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].prix_unitaire;
            response[i].price = response[i].prix_unitaire;
            response[i].p.quantity = response[i].quantite_vendu;
            response[i].quantity = response[i].quantite_vendu;
            response[i].p.name = response[i].p_name;
            response[i].name = response[i].p_name;
            response[i].total = response[i].montant_vendu;
          }
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = response[0]['client'] == null ? {} : JSON.parse(response[0]['client']);
          this.status_download = true;
        })
    }
  }
}
</script>

<style>
::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
</style>
