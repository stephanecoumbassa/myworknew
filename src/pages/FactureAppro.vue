<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
      <div class="col-lg-10 col-md-11 col-12">

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture
              name="Facture de devis"
              :entreprise="entreprise" :client="fournisseur" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

        <q-dialog v-model="fullWidth" position="top">
          <q-card id="facture" class="q-ma-sm q-pa-sm" :flat="true" style="width: 1000px; max-width: 100%;">
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
                    <q-input
                      v-model="dateposted" stack-label type="datetime-local"
                      class="col-md-7 col-12 q-pa-sm offset-md-2" label="Date" :dense="true"></q-input>
                    <q-btn
                      class="col-md-3 col-6" size="sm" color="grey-5" :dense="true" style="height: 40px"
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
              <q-form  class="" @submit="onSubmit">
                <br>
                <div v-for="(prod, index) in products" :key="index" class="row q-pa-sm">
                  <q-select
                    v-model="prod.product_id" class="col-3 row q-pl-sm" map-options emit-value
                    option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                  <q-input v-model="prod.amount" class="col-2 row q-pl-sm" autocomplete type="number" label="Quantité" />
                  <q-input v-model="prod.buying_price" class="col-2 row q-pl-sm" autocomplete type="number" label="Prix Achat" />
                  <q-input v-model="prod.sell_price" class="col-2 row q-pl-sm" autocomplete type="number" label="Prix Vente" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" :value="prod.amount * prod.buying_price" label="total" />
                  <div class="col-1 row q-pl-xs">
                    <br>
                    <q-btn flat size="sm" class="print-hide" color="secondary" icon="edit" @click="sales_update(prod)" />
                    <q-btn flat size="sm" class="print-hide" color="negative" icon="remove" @click="sales_delete(prod.id, 'Erreur Saisie', prod.code_ap)" />
                  </div>
                </div>

                <div class="row q-mt-xs" style="min-height: 20px">
                  <h6 class="col-3 no-padding"><small>Reste= {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} CFA</small></h6>
                  <h6 class="offset-5 text-right col-4 no-padding">{{numerique(total)}}CFA</h6>
                </div>

                Liste des versement
                &nbsp;<q-btn size="xs" @click="versements.pop()">-</q-btn>
                &nbsp;<q-btn size="xs" @click="versements.push({montant: 0})">+</q-btn>
                <div v-for="fac in versements" :key="fac.id" class="row">
                  <q-input v-model="fac.date" class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label  />
                  <q-input v-model="fac.montant" class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" />
                  <q-btn v-if="fac.id" size="xs" @click="credit_update(fac)">✎</q-btn>
                  <q-btn v-if="!fac.id" size="xs" @click="credit_add(fac)">✅</q-btn>
                </div>
                <!--                                <q-btn class="print-hide" label="Valider" icon="save" type="submit" color="secondary"/>-->
              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal print-hide">
              <q-btn color="red-4" label="Supprimer la facture" @click="factures_delete()" />
              <q-btn v-close-popup flat label="Fermer" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <div class="row q-mt-lg">
          <div class="col-12 q-pa-lg">
            <q-input
              v-model="search" class="row" autocomplete type="search"
              label="Rechercher" @keyup="facture_filter_get(search)" />
          </div>
          <div class="col-12">
            <q-table
              title="Listes de ventes" :grid="grid" :rows="factures" :columns="columns_facture"
              :pagination="pagination" :filter="filter">
              <template #top="props">
                <div class="col-4 q-table__title">Liste des factures</div>&nbsp;&nbsp;&nbsp;
                <q-input v-model="filter" dense debounce="300" type="search" icon="search" placeholder="Rechercher" />
                <q-btn flat round dense icon="grid_on" class="q-ml-md" @click="grid = !grid" />
                <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" class="q-ml-md" @click="props.toggleFullscreen" />
              </template>
              <template #body="props">
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

import $httpService from '../boot/httpService';
export default {
  components: {
    'facture': FactureComponent
  },
  mixins: [basemixin],
  data () {
    return {
      facture_status2: false,
      fullWidth: false,
      agent: null,
      date: '',
      grid: false,
      filter: '',
      dateposted: '',
      fournisseur: {},
      entreprise: {},
      search: null,
      facture_number: null,
      versements: [],
      factures: [],
      factures_init: [],
      factures_details: [],
      facture_id: null,
      products: [],
      products_list: [],
      columns_facture: [
        { name: 'facture', align: 'left', label: 'Facture', field: 'facture', sortable: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'fullname', align: 'left', label: 'Fournisseur', field: 'fullname', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'versement', align: 'left', label: 'Versement', field: 'versement', sortable: true },
        { name: 'reste', align: 'left', label: 'reste', field: 'reste', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.p.sell_price * item.quantity), 0);
      // return this.products.reduce((product, item) => product + (item.quantite_vendu * item.prix_unitaire), 0);
      // return this.products.reduce((product, item) => product + (item.amount * item.buying_price), 0);
      return this.products.reduce((product, item) => product + (item.buying_price * item.amount + (item.tva * item.buying_price * item.amount)), 0);
    }
  },
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth(), date.getDate()), 4);
    this.products_get();
    this.factures_get();
    this.shop_get();
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
    }
  }
}
</script>

<style>
::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
</style>
