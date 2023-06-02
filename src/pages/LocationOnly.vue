<template>

  <q-page>
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-12 col-12">

        <q-tabs
          v-model="tab" dense class="text-dark"
          active-color="secondary" indicator-color="secondary" align="justify" narrow-indicator>
          <q-tab name="alarms" label="Nouvelle location" @click="products = [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }]" />
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
                          <div class="text-subtitle">( {{item.reste}} )</div>
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
                      <div class="row q-pa-lg">
                        <div class="col-12 text-h6">{{entreprise.name}}</div>

                        <div class="col-5 offset-1 q-ma-sm print-hide">
                          <q-input v-model="dateposted" stack-label type="datetime-local" label="Date" :dense="true"></q-input>
                          <br>
                          <q-select
v-if="status_download" v-model="client" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                                    :dense="true" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                                    input-debounce="0" :option-label="'fullname'" :rules="[val => !!val || 'Ce champs est requis']" @input="assign_client(client)" />
                        </div>

                        <div class="col-5 q-ma-sm print-hide">
                          <q-input v-model="date_start" filled stack-label type="date" label="Date de début" :dense="true" /><br>
                          <q-input v-model="date_end" filled stack-label type="date" label="Date de fin" :dense="true" />
                        </div>
                      </div>
                    </q-card>
                  </div>

                  <q-card v-if="total" class="my-card q-mb-md">
                    <q-item>
                      <q-card-section>
                        <div v-for="(product, index) in products" :key="index" class="row q-mb-xs">
                          <q-input v-model="product.name" borderless readonly class="col-12 bold" :dense="true" type="text" />
                          <q-input
v-model="product.quantity" class="col-3 q-pr-sm" :dense="true" type="number"
                                   @input="getVal(index, product.quantity)" />
                          <q-input class="col-1 q-pr-sm" :dense="true" label="X"></q-input>
                          <q-input v-model="product.price" class="col-3 q-pr-sm" :dense="true" type="number" />
                          <q-input borderless readonly class="col-4 q-pr-sm" :dense="true" type="number" :value="product.sales_price * product.quantity" />
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
      filter: '',
      filter2: '',
      fitst: 1,
      last: 30,
      name: null,
      grid: false,
      facture_status2: false,
      status_download: true,
      validate_status: true,
      fullWidth: false,
      medium: false,
      medium2: false,
      credit: false,
      solde: true,
      caution: 0,
      avance: 0,
      agent: null,
      fournisseur: null,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      versements: [],
      date: '',
      dateposted: '',
      date_start: null,
      date_end: null,
      first: '',
      client: 1,
      client2: {},
      myclient: {},
      image: '',
      users: [],
      clients: [],
      products: [],
      sales_list: [],
      products_list: [],
      appro_list: [],
      appro_list2: [],
      product: { description: '' },
      data: [],
      entreprise: {}
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.price * item.quantity), 0);
    }
  },
  watch: {
    // currentCity: function(newCity, oldCity) {
    //   this.getWeather();
    // }
  },
  created () {
    let date = new Date();

    // this.dateposted = this.dateposted();

    this.date = this.dateformat(date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate(), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.shop_get();
    this.clients_get();
    this.products_get();
    this.sales_get();
    // this.factures_number_get();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    this.dateposted = date.toISOString().slice(0, 16);
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.location_post();
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
    qr_get(dataUrl) {
      this.image = dataUrl;
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
    update_show(item) {
      this.medium2 = true;
      this.product = item;
    },
    location_post() {
      let params = {
        agent: this.agent,
        products: this.products,
        date_start: this.date_start,
        date_end: this.date_end,
        caution: this.caution,
        id_vente: this.facture_number,
        clientid: this.myclient.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total
      };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/location', params)
          .then((response) => {
            var status = response['status'];
            if (status == !0) {
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
    location_update(product) {
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/put/location', product)
          .then((response) => {
            var status = response['status'];
            if (status == !0) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    location_put() {
      if (confirm('Voulez vous modifier')) {
        $httpService.putWithParams('/my/put/location', this.product).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.sales_get();
        })
      }
    },
    products_get () {
      $httpService.getWithParams('/my/get/products_location')
        .then((response) => {
          this.appro_list = response;
          this.appro_list2 = response;
        })
    },
    sales_get () {
      $httpService.getWithParams('/my/get/location')
        .then((response) => {
          this.sales_list = response;
        })
    },
    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/sales_stats', params)
        .then((response) => {
          this.sales_list = response;
          this.nbre_vendus = _.sumBy(this.sales_list, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_list, 'montant_vendu');
        })
    },
    assign_product (item) {
      if (item.reste <= 0) {
        this.$q.notify({ color: 'dark', position: 'top', message: 'Le produit est en rupture de stock' });
        return false;
      }
      item.quantity = 1;
      item.product_id = item.id;
      this.products.push(item);
      let summedAges = this.products.reduce((a, c) => {
        let filtered = a.filter(el => el.id === c.id);
        if (filtered.length > 0) {
          // a[a.indexOf(filtered[0])].quantity += +c.quantity;
        } else {
          a.push(c);
        }
        return a;
      }, []);
      this.products = summedAges;
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
    specialities_add () {
      this.products_list.push({ id: 0, name: 'Selectionner un produit', tva: 0, price: 0, quantity: 1 });
    },
    specialities_delete () {
      this.products_list.pop();
    },
    // select_index(productid, index) {
    //     // this.products_list[index] = { product_id: productid, tva: 0, price: 0, quantity: 1 };
    // },
    // delete_product(i) {
    //     this.products_list = this.sales_list.filter((x) => {
    //         return x.product_id !== this.products_list[i].product_id;
    //     });
    // },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.products = this.products2.filter(
          (v) => {
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1;
          }
        );
      })
    },
    factures_get_credit (factureid) {
      $httpService.getWithParams('/my/get/sales_by_credit?id_vente=' + factureid)
        .then((response) => {
          this.versements = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.factures_get_id(_id);
    },
    get_facture_update(_id) {
      this.listes_status = false;
      this.update_status = true;
      this.factures_get_id(_id);
    },
    factures_get_id (factureid) {
      $httpService.getWithParams('/my/get/location_by_facture?id_location=' + factureid)
        .then((response) => {
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            // response[i].p.id = response[i].product_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].price;
            response[i].p.quantity = response[i].quantity;
            response[i].p.name = response[i].name;
            response[i].total = response[i].quantity * response[i].price;
          }
          this.date_start = response[0].date_start;
          this.date_end = response[0].date_end;
          this.caution = response[0].caution;
          this.products_list = response;
          this.facture_number = this.products[0].id_location;
          this.client = response[0]['client'] == null ? {} : JSON.parse(response[0]['client']);
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
