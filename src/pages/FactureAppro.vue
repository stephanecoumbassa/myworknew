<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
      <div class="col-lg-10 col-md-11 col-12">

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture name="Facture de devis"
                     :entreprise="entreprise" :client="fournisseur" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

        <q-dialog v-model="fullWidth" position="top">
          <q-card class="q-ma-sm q-pa-sm" :flat="true" id="facture" style="width: 1000px; max-width: 100%;">
            <q-card-section>
              <div class="row">
                <div class="col-6 alignleft">
                  <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>
                  <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>
                  <div>{{entreprise.name}}</div>
                  <div>{{entreprise.telephone}}</div>
                  <div>{{entreprise.email}}</div>
                </div>
                <div class="col-6 text-right float-right">
                  <div class="row">
                    <q-input stack-label v-model="dateposted" type="datetime-local"
                             class="col-md-7 col-12 q-pa-sm offset-md-2" label="Date" :dense="true"></q-input>
                    <q-btn class="col-md-3 col-6" size="sm" color="grey-5" :dense="true" style="height: 40px"
                           @click="sales_dateposted(products[0])">Modifier la date</q-btn>
                  </div>
                  <br>
                  <div>Facture #: {{facture_number}}</div>
                  <div>Creation: {{date}}</div>
                  <div><q-icon name="business" />Entreprise Corporation</div>
                  <div><q-icon name="face" />{{fournisseur.name}} {{fournisseur.last_name}}</div>
                  <div><q-icon name="phone" /> {{fournisseur.telephone_code}} {{fournisseur.telephone}}</div>
                  <div><q-icon name="email" /> {{fournisseur.email}}</div>
                </div>
              </div>
            </q-card-section>

            <q-card-section>
              <q-form  @submit="onSubmit" class="">
                <br>
                <div class="row q-pa-sm" v-for="(product, index) in products" :key="index">
                  <q-select class="col-3 row q-pl-sm" v-model="product.product_id" map-options emit-value
                            option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.amount" label="Quantité" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.buying_price" label="Prix Achat" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.sell_price" label="Prix Vente" />
                  <!-- <q-input class="col-2 no-margin" autocomplete type="number" v-model="product.montant_vendu" label="Prix de Vente" />-->
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" :value="product.amount * product.buying_price" label="total" />
                  <div class="col-1 row q-pl-xs">
                    <br>
                    <q-btn flat size="sm" class="print-hide" color="secondary" v-on:click="sales_update(product)" icon="edit" />
                    <q-btn flat size="sm" class="print-hide" color="negative" v-on:click="sales_delete(product.id, 'Erreur Saisie', product.code_ap)" icon="remove" />
                  </div>
                </div>

                <div class="row q-mt-xs" style="min-height: 20px">
                  <h6 class="col-3 no-padding"><small>Reste= {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} CFA</small></h6>
                  <h6 class="offset-5 text-right col-4 no-padding">{{numerique(total)}}CFA</h6>
                </div>

                Liste des versement
                &nbsp;<q-btn size="xs" v-on:click="versements.pop()">-</q-btn>
                &nbsp;<q-btn size="xs" v-on:click="versements.push({montant: 0})">+</q-btn>
                <div class="row" v-for="fac in versements" :key="fac.id">
                  <q-input class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label v-model="fac.date"  />
                  <q-input class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" v-model="fac.montant" />
                  <q-btn size="xs" v-if="fac.id" v-on:click="credit_update(fac)">✎</q-btn>
                  <q-btn size="xs" v-if="!fac.id" v-on:click="credit_add(fac)">✅</q-btn>
                </div>
                <!--                                <q-btn class="print-hide" label="Valider" icon="save" type="submit" color="secondary"/>-->
              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal print-hide">
              <q-btn color="red-4" label="Supprimer la facture" v-on:click="factures_delete()" />
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <div class="row q-mt-lg">
          <div class="col-12 q-pa-lg">
            <q-input class="row" autocomplete type="search" v-model="search"
                     v-on:keyup="facture_filter_get(search)" label="Rechercher" />
          </div>
          <div class="col-12">
            <q-table title="Listes de ventes" :grid="grid" :rows="factures" :columns="columns_facture"
                     :pagination="pagination" :filter="filter">
              <template v-slot:top="props">
                <div class="col-4 q-table__title">Liste des factures</div>&nbsp;&nbsp;&nbsp;
                <q-input dense debounce="300" type="search" icon="search" v-model="filter" placeholder="Rechercher" />
                <q-btn flat round dense icon="grid_on" @click="grid = !grid" class="q-ml-md" />
                <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen" class="q-ml-md" />
              </template>
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td key="facture" :props="props">{{ props.row.facture }}</q-td>
                  <q-td key="dateposted" :props="props">{{ props.row.date }}</q-td>
                  <q-td key="fullname" :props="props">{{ props.row.fullname }}</q-td>
                  <q-td key="total" :props="props">{{ props.row.total }}</q-td>
                  <q-td key="versement" :props="props">{{ props.row.versement }}</q-td>
                  <q-td key="reste" :props="props">{{ props.row.total - props.row.versement }}</q-td>
                  <q-td key="actions" :props="props">
                    <q-btn flat icon="edit" @click="fullWidth = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                    <q-btn flat icon="receipt" @click="facture_status2 = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                  </q-td>
                </q-tr>
              </template>

            </q-table>
          </div>

        </div>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>

import basemixin from './basemixin';
import FactureComponent from '../components/facture_component.vue';

const data = [{ 'name': 'Facebook' }, { 'name': 'Google' }, { 'name': 'Twitter' }];
import $httpService from '../boot/httpService';
export default {
  data () {
    return {
      items: data,
      value: 'Facebook',
      name: null,
      facture_status2: false,
      fullWidth: false,
      medium: false,
      medium2: false,
      agent: null,
      date: '',
      grid: false,
      filter: '',
      dateposted: '',
      fournisseur: {},
      entreprise: {},
      facture: { type: 'achat' },
      position: {},
      search: null,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      buy: null,
      versements: [],
      categories: [],
      users: [],
      factures: [],
      factures_init: [],
      factures_details: [],
      facture_id: null,
      products: [],
      sales_list: [],
      products_list: [],
      appro_list: [{ p: { sell_price: 0, id: null, quantity: 1 }, quantity: 1 }],
      product: { description: '' },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true },
        { name: 'dateposted', align: 'left', label: 'date', field: 'dateposted', sortable: true },
        { name: 'amount', align: 'left', label: 'Qte', field: 'amount', sortable: true },
        { name: 'buying_price', align: 'left', label: 'Prix Achat', field: 'buying_price', sortable: true },
        { name: 'sell_price', align: 'left', label: 'Prix Vente.', field: 'sell_price', sortable: true },
        { name: 'fournisseur_user', align: 'left', label: 'Fournisseur', field: 'fournisseur_user', sortable: true },
        { name: 'code_ap_name', align: 'left', label: 'Code Appruo', field: 'code_ap_name', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      columns_facture: [
        { name: 'facture', align: 'left', label: 'Facture', field: 'facture', sortable: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'fullname', align: 'left', label: 'Fournisseur', field: 'fullname', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'versement', align: 'left', label: 'Versement', field: 'versement', sortable: true },
        { name: 'reste', align: 'left', label: 'reste', field: 'reste', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
      data: []
    }
  },
  mixins: [basemixin],
  components: {
    'facture': FactureComponent
  },
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth(), date.getDate()), 4);
    this.products_get();
    this.factures_get();
    this.shop_get();
    this.dateposted = date.toISOString().slice(0, 16);
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.p.sell_price * item.quantity), 0);
      // return this.products.reduce((product, item) => product + (item.quantite_vendu * item.prix_unitaire), 0);
      // return this.products.reduce((product, item) => product + (item.amount * item.buying_price), 0);
      return this.products.reduce((product, item) => product + (item.buying_price * item.amount + (item.tva * item.buying_price * item.amount)), 0);
    }
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
    assign (index) {
      this.products[index].p.quantity = 1;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    sales_post() {
      let params = { 'agent': this.agent, 'fournisseur': this.fournisseur, 'products': this.products, 'id_vente': this.facture_number };
      $httpService.postWithParams('/my/post/sales', params)
        .then((response) => {
          this.products_get();
          this.appro_get();
          this.$q.notify({
            color: 'green', position: 'top-right', message: response.msg, icon: 'report_problem'
          });
        })
    },
    get_facture_id (_id) {
      // this.fullWidth = true;
      this.facture_id = _id;
      this.factures_get_id();
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/appro_by_facture', { id_ap: this.facture_id })
        .then((response) => {
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_ap;
          this.fournisseur = JSON.parse(response[0]['fournisseur']);
          this.date = this.dateformat(this.products[0]['dateposted'], 4);
          this.dateposted = new Date(this.products[0]['dateposted']).toISOString().slice(0, 16);
          // if (this.products[0]['dateposted']) this.date = this.dateformat(this.products[0]['dateposted'], 4);
        })
    },
    facture_filter_get() {
      const val1 = this.factures_init.filter((x) => { return x.facture.toString().includes(this.search); });
      this.factures = val1;
    },
    factures_get () {
      $httpService.getWithParams('/my/get/factures_appro')
        .then((response) => {
          this.factures = response;
          this.factures_init = response;
          for (let i = 0; i < this.factures.length; i++) {
            if (this.factures[i].fournisseur) {
              this.factures[i].fullname = JSON.parse(this.factures[i].fournisseur)['fullname'];
            }
          }
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/appro_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_add(facture) {
      if (confirm('Voulez vous ajouter')) {
        facture.factureid = this.facture_id;
        facture.vente = 'achat';
        $httpService.postWithParams('/my/post/credit', facture)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_credit();
          })
      }
    },
    credit_update(facture) {
      if (confirm('Voulez vous modifier')) {
        facture.factureid = this.facture_id;
        facture.vente = 'achat';
        $httpService.postWithParams('/my/put/credit', facture)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_credit();
          })
      }
    },
    sales_update(product) {
      product.dateposted = this.dateposted;
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/put/appro', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_dateposted(product) {
      product.dateposted = this.dateposted;
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/dateposted/appro', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_delete(id, motif, codeAp) {
      if (confirm('Voulez vous supprimer')) {
        $httpService.postWithParams('/my/delete/appro', { id: id, motif: motif, code_ap: codeAp })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    factures_delete() {
      if (confirm('Voulez vous supprimer la facture?')) {
        $httpService.postWithParams('/my/delete/appro_all', { factureid: this.facture_number })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
            this.factures_get_credit();
            this.factures_get();
          })
      }
    },
    imprimer() {
      window.print();
    },
    colorize (value) {
      if (value > 0) {
        return 'bg-red-1';
      }
    },
    filterFn(val, update) {
      this.value = null;
      if (val === '') {
        update(() => {
          this.items = data
        });
        return
      }
      update(() => {
        const needle = val.toLowerCase();
        this.items = data.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
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
