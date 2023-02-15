<template>
  <q-page>
    <!-- content -->
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-12">
        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1000px; max-width: 100%;" id="facture" :flat="true" padding>
            <q-form  @submit="onSubmit" class="q-gutter-md">

              <q-card-section>
                <div class="row">
                  <h6>Ajouter une perte</h6>
                </div>

              </q-card-section>

              <q-card-section class="margin paading">
                <div class="row no-margin no-padding" style="height: 47px">
                  <div class="col-5 q-pa-sm">Nom</div>
                  <div class="col-1 q-pa-sm">Qte</div>
                  <div class="col-2 q-pa-sm">Prix Uni</div>
                  <div class="col-3 q-pa-sm">Total</div>
                </div>
                <div class="row q-mt-lg" v-for="(product, index) in products" :key="index" style="height: 47px">
                  <q-select class="col-5 q-pa-sm truncate" v-model="product.p" :options="appro_list" option-value="id" use-input @filter="filterFn"
                            input-debounce="0" option-label="prodcat" @focusout="assign(index)" @input="assign(index)" :dense="true" />
                  <q-input class="col-1 q-pa-sm" :dense="true" type="number" v-model="product.quantity" @focusout="getVal(index, product.quantity)" />
                  <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.p.sales_price" readonly />
                  <q-input class="col-3 q-pa-sm" :dense="true" type="number" :model-value="product.p.sales_price * product.quantity" />
                  <div class="col-1"><br>
                    <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-on:click="delete_product(index)" />
                  </div>
                </div>
                <div class="row no-padding q-mt-xs q-mb-lg" style="height: 70px">
                  <div class="col-3  q-pa-lg">
                    <!--                    <q-checkbox v-model="credit" label="Credit" color="red" />-->
                  </div>
                  <div class="offset-6 col-3 q-pa-sm">
                    <!--                    <q-input v-if="credit" :dense="true" type="number" v-model="avance" label="Avance"/><br>-->
                    <h6 class="no-margin no-padding q-mb-lg">{{ numerique(Math.round(total)) }} FCFA</h6>
                  </div>
                </div>
                <div class="row" v-if="validate_status">
                  <q-btn class="print-hide" round color="positive" size="xs" icon="add" v-on:click="specialities_add" />&nbsp;&nbsp;
                  <q-btn class="print-hide" label="Valider" size="xs" icon="save" type="submit" color="secondary" />
                </div>
              </q-card-section>

            </q-form>
            <q-card-actions align="right" class="bg-white text-teal print-hide">
              <q-btn flat label="Fermer" v-close-popup />
              <q-btn flat label="Telecharger" v-on:click="download()" />
              <q-btn flat label="Imprimer" v-on:click="imprimer()" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="medium2">
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6">Modifier une perte</div>
            </q-card-section>

            <q-card-section>

              <q-form  @submit="onSubmit" class="q-gutter-md">
<!--                <q-select filled map-options emit-value v-model="product.a_id" :options="users"-->
<!--                          label="Agent" option-value="id" stack-label input-debounce="0" option-label="name" />-->
                <q-select map-options emit-value class="col-6 q-pa-sm" v-model="product.p_id" :options="appro_list" label="Produits"
                          option-value="id" use-input stack-label input-debounce="0" option-label="name" />
                <q-input padding class="col-2 q-pa-sm" autocomplete type="number" v-model="product.quantite_vendu" label="Quantité" />
<!--                <q-input padding class="col-2 q-pa-sm" autocomplete type="number" v-model="product.prix_unitaire" label="Prix d'achat" />-->
<!--                <q-input padding class="col-3 q-pa-sm" autocomplete type="number" v-model="product.montant_vendu" label="Prix de vente" />-->
              </q-form>

            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn label="Modifier" icon="edit" type="submit" color="secondary" v-on:click="sales_put()" />
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>


        <q-btn class="q-mb-sm" size="sm" label="Ajouter" icon="add" color="secondary" @click="fullWidth = true; validate_status = true" /><br>

        <div class="row q-pa-sm print-hide">
          <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col q-pa-sm"><br><q-btn color="primary" label="filtrer" v-on:click="sales_stats_get()" /></div>
        </div>

        <q-table title="Listes de pertes" :grid="grid" :rows="sales_list" :columns="columns"
                 :pagination="pagination" :filter="filter">
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Listes de pertes</div>&nbsp;&nbsp;&nbsp;
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <download-excel name="vente.xls" :json-data="sales_list">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn flat round dense icon="grid_on" @click="grid = !grid" class="q-ml-md" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">{{ props.row.id }}</q-td>
              <q-td key="p_name" :props="props">{{ props.row.p_name }}</q-td>
              <q-td key="dateposted" :props="props"> {{ dateformat(props.row.dateposted, 3) }}</q-td>
              <q-td key="quantite_vendu" :props="props"> {{ numerique(parseInt(props.row.quantite_vendu)) }}</q-td>
              <q-td key="prix_unitaire" :props="props"> {{ numerique(props.row.prix_unitaire) }}</q-td>
              <q-td key="tva" :props="props"> {{ numerique(props.row.tva) }}</q-td>
              <q-td key="remise_totale" :props="props"> {{ numerique(props.row.remise_totale) }}</q-td>
              <q-td key="a_name" :props="props"> {{ props.row.a_name }} {{ props.row.a_last_name }}</q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-ma-xs" size="xs" color="secondary" v-on:click="update_show(props.row)" icon="edit" />
                <q-btn class="q-ma-xs" size="xs" color="red" icon="delete" />
              </q-td>
            </q-tr>
          </template>
          <template v-slot:item="props">
            <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
              <q-card :class="props.selected ? 'bg-grey-2' : ''">
                <q-list dense v-for="col in props.cols.filter(col => col.name !== 'desc')" :key="col.name">
                  <q-item v-if="col.status !== false">
                    <q-item-section>
                      <q-item-label caption>{{ col.label }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                      <q-item-label >{{ col.value }}</q-item-label>
                      <q-item-label v-if="col.label == 'Actions'">
                        <q-btn class="q-ma-xs" size="xs" color="secondary" v-on:click="update_show(props)" icon="edit" />
                        <q-btn class="q-ma-xs" size="xs" color="red" icon="delete" />
                      </q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card>
            </div>
          </template>
        </q-table>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>


import $httpService from '../boot/httpService';
// import VueQr from 'vue-qr';
// import htmlToImage from 'html-to-image';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import * as _ from 'lodash';
export default {
  name: 'PertePage',
  data () {
    return {
      filter: '',
      fitst: 1,
      last: 30,
      name: null,
      grid: false,
      validate_status: true,
      fullWidth: false,
      medium: false,
      medium2: false,
      credit: false,
      solde: true,
      avance: 0,
      agent: null,
      fournisseur: null,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      date: '',
      client: 1,
      client2: { id: null },
      image: '',
      users: [],
      clients: [],
      products: [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }],
      sales_list: [],
      products_list: [],
      appro_list: [{ p: { sell_price: 0 } }],
      appro_list2: [{ p: { sell_price: 0 } }],
      product: { description: '' },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true, status: false },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true, status: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'quantite_vendu', align: 'left', label: 'Qte', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', align: 'left', label: 'Prix', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'tva', align: 'left', label: 'TVA', field: 'tva', sortable: true },
        { name: 'remise_totale', align: 'left', label: 'Remise.', field: 'remise_totale', sortable: true, format: val => `${this.numerique(val)}` },
        // { name: 'benefice_vente', align: 'left', label: 'Benefice', field: 'benefice_vente', status: false, sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'a_name', align: 'left', label: 'Agent', field: 'a_name', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
      entreprise: {},
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      }
    }
  },
  components: {
    // VueQr,
    'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.shop_get();
    this.products_get();
    this.users_get();
    this.sales_get();
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity)), 0);
    }
  },
  watch: {
    // currentCity: function(newCity, oldCity) {
    //   this.getWeather();
    // }
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign (index) {
      this.products[index].p.tva = 0;
      // this.products[index].p.quantity = 1;
      // this.products[index].quantity = 1;
    },
    assign_client (client) {
      this.client2.id = client.id;
      this.client2.fullname = client.fullname;
      this.client2.email = client.email;
      this.client2.telephone = client.telephone;
      this.client2.telephone_code = client.telephone_code;
    },
    qr_get(dataUrl) {
      this.image = dataUrl;
    },
    clients_get () {
      $httpService.getWithParams('/my/get/client')
        .then((response) => {
          this.clients = response;
          this.client = this.clients[0];
          this.client2 = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    update_show(item) {
      this.medium2 = true;
      this.product = item;
    },
    sales_post() {
      let params = { agent: this.agent, products: this.products, id_vente: this.facture_number, clientid: this.client2.id, credit: this.credit, avance: this.avance, total: this.total };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/pertes', params)
          .then((response) => {
            var status = response['status'];
            if (status == !0) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid']; this.validate_status = false; this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    sales_put() {
      if (confirm('Voulez vous modifier')) {
        $httpService.putWithParams('/my/put/sales', this.product).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.sales_get();
        })
      }
    },
    users_get () {
      $httpService.getWithParams('/api/s_users')
        .then((response) => {
          this.users = response;
        })
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
      $httpService.getWithParams('/my/get/pertes')
        .then((response) => {
          this.sales_list = response;
        })
    },
    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/pertes_stats', params)
        .then((response) => {
          this.sales_list = response;
          this.nbre_vendus = _.sumBy(this.sales_list, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_list, 'montant_vendu');
        })
    },
    specialities_add () {
      this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
    },
    specialities_delete () {
      this.products.pop();
    },
    // download() {
    //     htmlToImage.toJpeg(document.getElementById('facture'), { quality: 0.95 })
    //         .then(function (dataUrl) {
    //             var link = document.createElement('a');
    //             link.download = 'factureVente.jpeg';
    //             link.target = '_blank';
    //             link.href = dataUrl;
    //             link.click();
    //         });
    // },
    imprimer() {
      window.print();
    },
    getVal(index, val) {
      this.products[index].quantity = parseInt(val);
      this.products[index].p.quantity = parseInt(val);

      this.$nextTick(() => {
        this.products[index].quantity = parseInt(val);
        this.products[index].p.quantity = parseInt(val);
      })
    },
    startDownload() {
      confirm('Voulez-vous generer');
      return false;
    },
    delete_product(i) {
      this.products = this.products.filter((x) => {
        return x.p.id !== this.products[i].p.id;
      });
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.appro_list = this.appro_list2.filter(
          (v) => {
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1
          }
        );
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
