<template>

  <q-page padding>
    <!-- content -->
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-10 col-12">
        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1000px; max-width: 100%;" id="facture" :flat="true">
            <q-form  @submit="onSubmit" class="q-gutter-md">

              <q-card-section >
                <div class="row mobile-hide">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 alignleft">
                    <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>
                    <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>
                    <div>{{entreprise.name}}</div>
                    <div>{{entreprise.telephone}}</div>
                    <div>{{entreprise.email}}</div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 content-center text-center print-only" print-only style="height: 150px">
                    <vue-qr class="hidden" :size="100" :text="JSON.stringify(products)" :callback="qr_get" qid="testid" />
                    <vue-qr :size="100" :text="JSON.stringify(products)" />
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-4 text-right float-right" style="min-width: 200px" contenteditable="true">
                    <div class="float-right q-mb-sm print-hide" style="width: 50%; position:relative;">
                      <q-select class="print-hide col-md-6 col-sm-12" filled map-options emit-value v-if="status_download"
                                v-model="client" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                                stack-label input-debounce="0" :option-label="'fullname'" @input="assign_client(client)" :rules="[val => !!val || 'Ce champs est requis']" />
                    </div>
                    <div class="row float-right q-mt-sm">
                      <div class="col-12">Facture Retour #: {{facture_number}}</div>
                      <div class="col-12">Creation: {{date}}</div>
                      <div class="col-12">Entreprise Corporation</div>
                      <div class="col-12">{{client2.fullname}}</div>
                      <div class="col-12">+ {{client2.telephone_code}} {{client2.telephone}}</div>
                      <div class="col-12"> {{client2.email}}</div>
                    </div>

                  </div>
                </div>
              </q-card-section>

              <q-card-section class="no-margin">
                <div class="row no-margin no-padding mobile-hide" style="height: 47px">
                  <div class="col-3 q-pa-sm">Produit</div>
                  <div class="col-1 q-pa-sm">Qte</div>
                  <div class="col-2 q-pa-sm">Prix Uni</div>
                  <div class="col-2 q-pa-sm">Total</div>
                  <div class="col-2 q-pa-sm">Motif</div>
                </div>
                <div class="row q-mb-lg" v-for="(product, index) in products" :key="index">
                  <q-select class="col-sm-12 col-xs-12 col-3 q-pa-sm text-wrap" v-model="product.p" :options="appro_list" option-value="id" use-input @filter="filterFn"
                            option-label="name" @focusout="assign(index)" @input="assign(index)" :dense="true" />
                  <q-input class="col-sm-1 col-xs-1 col-1 q-pa-sm" hint="qty" :dense="true" type="number" v-model="product.quantite" @focusout="getVal(index, product.quantity)" />
                  <q-input class="col-sm-3 col-xs-3 col-2 q-pa-sm" hint="pu" :dense="true" type="number" v-model="product.p.sales_price" />
                  <q-input class="col-sm-3 col-xs-3 col-2 q-pa-sm" hint="tot" :dense="true" type="number" :value="product.p.sales_price * product.quantite" />
                  <q-input class="col-sm-4 col-xs-4 col-2 q-pa-sm" hint="motif" :dense="true" v-model="product.p.motif" />
                  <div class="col-1"><br>
                    <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-if="status_download" v-on:click="delete_product(index)" />
                  </div>
                </div>
                <div class="row no-padding q-mt-xs q-mb-lg">
                  <div class="col-3 q-pa-lg" v-if="status_download">
                  </div>
                  <div class="offset-lg-6 col-sm-9 col-xs-9 col-3 q-pa-sm">
                    <q-input v-if="credit" :dense="true" type="number" v-model="avance" label="Avance"/><br>
                    <div class="text-h6 no-margin no-padding q-mb-lg" style="text-align: right">
                      {{ numerique(Math.round(total)) }} FCFA
                    </div>
                  </div>
                </div>

                <div class="row no-padding q-mt-xs q-mb-lg">
                  <div class="col-3 q-pa-sm" v-if="status_download">
                    <q-input :dense="true" v-model="facture_number" label="N??Facture" filled />
                  </div>
                  <div class="offset-1 col-8 q-pa-sm">
                    <q-input :dense="true" type="textarea" v-model="description" label="Description du probl??me" filled/>
                  </div>
                </div>

                <div class="row q-pa-lg" v-if="status_download">
                  <q-btn class="print-hide" round color="positive" size="xs" icon="add" v-on:click="specialities_add" />&nbsp;&nbsp;
                  <q-btn class="print-hide" label="Valider" size="xs" icon="save" type="submit" color="secondary" />
                </div>


              </q-card-section>

            </q-form>
            <q-card-actions align="right" class="bg-white text-teal print-hide" v-if="status_download">
              <q-btn flat label="Fermer" v-close-popup />
              <q-btn flat label="Telecharger" v-on:click="download()" />
              <q-btn flat label="Imprimer" v-on:click="imprimer()" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-btn class="q-mb-sm" size="sm" label="Ajouter" icon="add" color="secondary" @click="fullWidth = true; validate_status = true" /><br>

        <div class="row q-pa-sm print-hide">
          <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date debut" /></div>
          <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col q-pa-sm"><br><q-btn color="primary" label="filtrer" v-on:click="retour_stats_get()" /></div>
        </div>

        <q-table title="Listes des retours" :grid="grid" :rows="retour_list" :columns="columns"
                 :pagination="pagination" :filter="filter">
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Liste des retours</div>&nbsp;&nbsp;&nbsp;
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <download-excel name="vente.xls" :json-data="retour_list">
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
              <q-td key="quantite_vendu" :props="props"> {{ numerique(parseInt(props.row.quantite)) }}</q-td>
              <q-td key="prix_unitaire" :props="props"> {{ numerique(props.row.prix_unitaire) }}</q-td>
              <q-td key="tva" :props="props"> {{ numerique(props.row.tva) }}</q-td>
              <q-td key="remise_totale" :props="props"> {{ numerique(props.row.remise_totale) }}</q-td>
              <q-td key="a_name" :props="props"> {{ props.row.a_name }} {{ props.row.a_last_name }}</q-td>
              <q-td key="id_vente" :props="props"> {{ props.row.avoir_num }} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-ma-xs" size="xs" color="secondary" v-on:click="get_facture_id(props.row.avoir_num)" icon="visibility" />
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
                        <q-btn class="q-ma-xs" size="xs" color="secondary" v-on:click="get_facture_id(props.id_vente); factures_get_credit(props.id_vente)" icon="visibility" />
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
import vueQr from 'vue-qr/src/packages/vue-qr.vue'
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import * as _ from 'lodash';
export default {
  name: 'RetourPage',
  data () {
    return {
      filter: '',
      fitst: 1,
      last: 30,
      name: null,
      grid: false,
      status_download: true,
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
      description: null,
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      versements: [],
      date: '',
      client: 1,
      client2: { id: null },
      image: '',
      users: [],
      clients: [],
      products: [{ p: { id: 1, prodcat: 'Selectionner', name: 'Selectionner', tva: 0, sell_price: 0 }, quantity: 1 }],
      retour_list: [],
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
        { name: 'a_name', align: 'left', label: 'Agent', field: 'a_name', sortable: true },
        { name: 'id_vente', align: 'left', label: 'Facture ID', field: 'id_vente', sortable: true },
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
    vueQr,
    'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.shop_get();
    this.clients_get();
    // this.users_get();
    this.products_get();
    this.retour_get();
    this.factures_number_get();
    // setInterval(function () {
    //     var connection = navigator.onLine ? 'online' : 'offline';
    //     console.log(connection);
    // }, 2500);
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantite + (item.p.tva * item.p.sales_price * item.quantite)), 0);
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
        this.retour_post();
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
    assign (index) {
      this.products[index].p.tva = 0;
      this.products[index].p.quantite = 1;
      this.products[index].quantite = 1;
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
    retour_post() {
      let params = { agent: this.agent,
        products: this.products,
        id_vente: this.facture_number,
        clientid: this.client2.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total
      };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/retour', params)
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
              this.retour_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    retour_put() {
      if (confirm('Voulez vous modifier')) {
        $httpService.putWithParams('/my/put/retour', this.product).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.retour_get();
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
    retour_get () {
      $httpService.getWithParams('/my/get/retours')
        .then((response) => {
          this.retour_list = response;
        })
    },
    retour_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/retour_stats', params)
        .then((response) => {
          this.retour_list = response;
          this.nbre_vendus = _.sumBy(this.retour_list, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.retour_list, 'montant_vendu');
        })
    },
    specialities_add () {
      this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
    },
    specialities_delete () {
      this.products.pop();
    },
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
    },
    factures_get_credit (factureid) {
      $httpService.getWithParams('/my/get/retour_by_credit?id_vente=' + factureid, { })
        .then((response) => {
          this.versements = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.medium2 = true;
      this.factures_get_id(_id);
    },
    factures_get_id (factureid) {
      $httpService.getWithParams('/my/get/retour_by_idvente?id_vente=' + factureid, { })
        .then((response) => {
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            response[i].p.id = response[i].product_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].prix_unitaire;
            response[i].p.quantity = response[i].quantite;
            response[i].p.quantite = response[i].quantite;
            response[i].p.motif = response[i].motif;
            response[i].quantity = response[i].quantite;
            response[i].p.name = response[i].p_name;
          }
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].avoir_num;
          // this.client = JSON.parse(response[0]['client']);
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
