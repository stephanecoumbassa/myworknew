<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
      <div class="col-lg-11 col-12">


        <div class="row">
          <div class="col-12 q-pa-lg">
            <q-input
              v-model="search" class="row" autocomplete type="search"
              label="Rechercher" @keyup="facture_filter_get(search)" />
          </div>
          <div class="col-12">

            <q-table
              id="printMe" title="Listes de ventes" :grid="grid" :rows="factures" :columns="columns_facture"
              :pagination="pagination" :filter="filter" dense>
              <template #top="props">
                <div class="col-4 q-table__title">Liste des factures</div>&nbsp;&nbsp;&nbsp;
                <!--                <q-input dense debounce="300" type="search" icon="search" v-model="filter" placeholder="Rechercher" />-->
                <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(factures, 'vente')"/>
                <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md" />
                <q-btn flat round dense icon="grid_on" class="q-ml-md" @click="grid = !grid" />
                <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" class="q-ml-md" @click="props.toggleFullscreen" />
              </template>
              <template #body="props">
                <q-tr :props="props">
                  <q-td key="facture" :props="props">{{ props.row.facture }}</q-td>
                  <q-td key="dateposted" :props="props">{{ props.row.dateposted }}</q-td>
                  <q-td key="fullname" :props="props">{{ props.row.fullname }}</q-td>
                  <q-td key="total" :props="props">{{ props.row.total }}</q-td>
                  <q-td key="versement" :props="props">{{ props.row.versement }}</q-td>
                  <q-td key="reste" :props="props">{{ props.row.total - props.row.versement }}</q-td>
                  <q-td key="actions" :props="props" class="print-hide">
                    <q-btn flat icon="edit" @click="fullWidth = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                    <q-btn flat icon="receipt" @click="facture_status2 = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                  </q-td>
                </q-tr>
              </template>

            </q-table>
          </div>

        </div>

        <q-dialog v-model="fullWidth" position="top">
          <q-card id="facture" style="width: 1200px; max-width: 100%;" :flat="true">
            <q-card-section>
              <div class="row">
                <div class="col-5 alignleft">
                  <!--                  <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>-->
                  <!--                  <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>-->
                  <img src="~assets/fmmi-logo.jpeg" style="width: 100px; height: 100px; object-fit: cover"/>
                  <div>{{entreprise.name}}</div>
                  <div>{{entreprise.telephone}}</div>
                  <div>{{entreprise.email}}</div>
                </div>
                <div class="col-3 text-center">
                  <q-input v-model="dateposted" bottom-slots stack-label type="datetime-local" :dense="true">
                    <template #after>
                      <q-btn round dense flat icon="send" @click="sales_dateposted(products[0])" />
                    </template>
                  </q-input>
                </div>
                <div class="col-4 text-right float-right">
                  <div>Facture #: {{facture_number}}</div>
                  <div>Creation: {{date}}</div>
                  <div><q-icon name="business" />Entreprise Corporation</div>
                  <div v-if="client"><q-icon name="face" />{{client.name}} {{client.last_name}}</div>
                  <div v-if="client"><q-icon name="phone" />+ {{client.telephone_code}} {{client.telephone}}</div>
                  <div v-if="client"><q-icon name="email" /> {{client.email}}</div>
                </div>
              </div>
            </q-card-section>

            <q-card-section>
              <q-form  class="" @submit="onSubmit">
                <br>
                <div class="row no-margin no-padding" style="height: 47px">
                  <div class="col-4 q-pa-sm">Nom</div>
                  <div class="col-1 q-pa-sm">Qte</div>
                  <div class="col-2 q-pa-sm">Prix</div>
                  <div class="col-2 q-pa-sm">TVA</div>
                  <div class="col-2 q-pa-sm">Total</div>
                </div>
                <div v-for="(product, index) in products" :key="index" class="row q-pa-sm">
                  <q-select
                    v-model="product.product_id" outlined class="col-4 no-margin text-no-wrap truncate" use-input map-options emit-value
                    dense option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                  <q-input v-model="product.quantite_vendu" dense class="col-1 row q-pl-sm" autocomplete type="number" label="Quantité" />
                  <q-input v-model="product.prix_unitaire" dense class="col-2 row q-pl-sm" autocomplete type="number" label="Prix" />
                  <q-input v-model="product.tva" dense class="col-2 row q-pl-sm" autocomplete type="number" label="tva" />
                  <q-input
                    dense class="col-2 row q-pl-sm" autocomplete type="number" label="total" :set="product.total = (product.quantite_vendu*product.prix_unitaire) + (product.quantite_vendu*product.prix_unitaire * product.tva) /100"
                    :model-value="product.total" />
                  <div class="col-1 row q-pl-xs print-hide">
                    <q-btn flat size="sm" color="secondary" icon="edit" @click="sales_update(product)" />
                    <q-btn flat size="sm" color="negative" icon="remove" @click="sales_delete(product.id, 'Erreur Saisie', product.code_ap)" />
                  </div>
                </div>
                <div v-if="status_download" class="row q-pa-sm print-hide">
                  <div class="col-3 row q-pa-sm">
                    <h6 v-if="total - array_somme(versements, 'montant') >= 0" class="bg-red-1 q-pa-sm">
                      <small>Reste=</small> {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA
                    </h6>
                    <h6 v-if="total - array_somme(versements, 'montant') < 0" class="no-padding bg-red-1">
                      <small>Vous devez</small> - {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA
                    </h6>
                  </div>
                  <div class="col-3 offset-6 row q-pa-sm">
                    <h6 class="text-right no-padding">{{numerique(Math.round(total)) }} CFA </h6>
                  </div>
                </div>
                <div class="print-hide">
                  <div class="col-12">
                    <div class="text-h6">Gestion des versements</div>
                    &nbsp;<q-btn outline color="grey" size="xs" @click="versements.pop()">-</q-btn>
                    &nbsp;<q-btn outline color="primary" size="xs" icon="add" @click="versements.push({montant: 0})">
                    <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">Ajouter un versement</q-tooltip>
                  </q-btn>
                  </div>
                  <div v-for="fac in versements" :key="fac.id" class="row">

                    <q-input v-model="fac.pourcentage" filled class="col-1 q-ma-sm" dense label="Pourcentage" type="number" stack-label  />
                    <q-input v-model="fac.echeance" filled class="col-1 q-ma-sm" dense label="Date Echéance" type="date" stack-label  />
                    <q-input v-model="fac.montant_echeance" filled class="col-1 q-ma-sm" dense label="Montant Echéance" stack-label type="number" />
                    &nbsp;
                    &nbsp;
                    <q-select
                      v-model="fac.paiement" class="col-1 q-ma-sm" dense label="Type paiement" stack-label
                      :options="['virement', 'cheque', 'espece']" />
                    <q-input v-model="fac.date" class="col-1 q-ma-sm" dense label="Date Vers" type="date" stack-label  />
                    <q-input v-model="fac.montant" class="col-1 q-ma-sm" dense label="Montant Vers" stack-label type="number" />
                    <q-input v-model="fac.numero" class="col-1 q-ma-sm" dense label="N°Chèque/Virement" />
                    <q-input v-model="fac.banque" class="col-1 q-ma-sm" dense label="Banque" />
                    <q-input v-model="fac.emission" class="col-1 q-ma-sm" dense label="Date Emission" type="date" stack-label  />

                    <div class="col-1">
                      <br>
                      <q-btn v-if="fac.id" outline color="secondary" size="sm" @click="credit_update(fac)">✎</q-btn>
                      <q-btn v-if="!fac.id" outline size="sm" @click="credit_add(fac)">✅</q-btn>
                    </div>
                  </div>
                </div>

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal print-hide q-ma-lg q-pa-lg">
              <q-btn color="red-4" label="Supprimer la facture" @click="factures_delete()" />
              <q-btn v-close-popup color="dark" flat label="Fermer" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="facture_status2" position="top" facture_filter_getstyle="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture
              name="Facture de devis"
              :entreprise="entreprise" :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import basemixin from './basemixin';

import $httpService from '../boot/httpService';
import FactureComponent from '../components/facture_component.vue';
export default {
  name: 'FacturePage',
  components: {
    'facture': FactureComponent
  },
  mixins: [basemixin],
  data () {
    return {
      filter: '',
      fullWidth: false,
      status_download: true,
      facture_status2: false,
      agent: null,
      client: {},
      entreprise: {},
      fournisseur: null,
      facture_number: null,
      search: null,
      versements: [],
      factures: [],
      grid: false,
      factures_init: [],
      factures_details: [],
      facture_id: null,
      products: [],
      products_list: [],
      date: '',
      dateposted: '',
      columns_facture: [
        { name: 'facture', align: 'left', label: 'Facture', field: 'facture', sortable: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'fullname', align: 'left', label: 'Client', field: 'fullname', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'versement', align: 'left', label: 'Versement', field: 'versement', sortable: true },
        { name: 'reste', align: 'left', label: 'reste', field: 'reste', sortable: true },
        { name: 'actions', align: 'left', classes: 'print-hide', headerClasses: 'print-hide', label: 'Actions' }
      ],
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.montant_vendu - item.remise_totale), 0);
      // return this.products.reduce((product, item) => product + (item.total ), 0);
      return this.products.reduce((product, item) => product + (item.total ), 0);
    }
  },
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.products_get();
    this.factures_get();
    this.shop_get();
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
    facture_filter_get() {
      const val1 = this.factures_init.filter((x) => {
        return x.facture.toLowerCase().includes(this.search)
          || x.dateposted.toString().includes(this.search)
          || x.fullname.toLowerCase().includes(this.search.toLowerCase())
          || x.montant_vendu.includes(this.search.toLowerCase())
          ;
      });
      this.factures = val1;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    sales_post() {
      if (confirm('Voulez vous ajouter')) {
        let params = {
          'agent': this.agent,
          'fournisseur': this.fournisseur,
          'products': this.products,
          'id_vente': this.facture_number
        };
        $httpService.postWithParams('/my/post/sales', params)
          .then((response) => {
            this.products_get();
            this.appro_get();
            this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
          })
      }
    },
    factures_get () {
      $httpService.getWithParams('/my/get/factures_id')
        .then((response) => {
          this.factures = response;
          this.factures_init = response;
          for (let i = 0; i < this.factures.length; i++) {
            if (this.factures[i].client) {
              this.factures[i].fullname = JSON.parse(this.factures[i].client)['fullname'];
            }
          }
        })
    },
    get_facture_id (_id) {
      this.facture_id = _id;
      this.factures_get_id();
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/sales_by_facture', { id_vente: this.facture_id })
        .then((response) => {
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = JSON.parse(response[0]['client']);
          this.dateposted = this.products[0].date;
          this.date = this.dateformat(this.products[0]['dateposted'], 4);
          this.dateposted = new Date(this.products[0]['dateposted']).toISOString().slice(0, 16);

          for (let i = 0; i < response.length; i++) {
            response[i].price = response[i].prix_unitaire;
            response[i].quantity = response[i].quantite_vendu;
          }
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/sales_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_add(facture) {
      facture.factureid = this.facture_id;
      facture.vente = 'vente';
      facture.type = 'vente';
      $httpService.postWithParams('/my/post/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    },
    credit_update(facture) {
      facture.factureid = this.facture_id;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/put/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    sales_update(product) {
      if (confirm('Voulez vous mettre à jour')) {
        product.p_id = product.product_id;
        $httpService.postWithParams('/my/put/sales', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_dateposted(product) {
      product.dateposted = this.dateposted;
      if (confirm('Voulez vous modifier la date de la facture')) {
        $httpService.postWithParams('/my/dateposted/sales', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_delete(id, motif, codeAp) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.postWithParams('/my/delete/sales', { id: id, motif: motif, code_ap: codeAp })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    factures_delete() {
      if (confirm('Voulez vous supprimer la facture?')) {
        $httpService.postWithParams('/my/delete/sales_all', { factureid: this.facture_number })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_credit();
            this.factures_get_id();
            this.factures_get();
          })
      }
    }
  }
}
</script>

<style>
</style>
