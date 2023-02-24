<template>
  <q-page padding>

    <div class="row justify-center text-center">

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-sm text-center">
        <q-card class="my-card text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <h5 class="text-center">Rubrique: Ventes</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-lg">
        <router-link class="item item-link" to="/ventes/new">
          <q-card clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Ventes</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-lg">
        <router-link class="item item-link" to="/ventes/factures">
          <q-card clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Factures</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-lg">
        <router-link class="item item-link" to="/ventes/credit">
          <q-card clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Credits</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <!--      <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 q-pa-lg">-->
      <!--        <router-link tag="div" class="item item-link" to="/ventes/devis">-->
      <!--          <q-card class="my-card" clickable>-->
      <!--            <q-item clickable>-->
      <!--              <q-card-section>-->
      <!--                <div class="text-h5">Devis</div>-->
      <!--              </q-card-section>-->
      <!--            </q-item>-->
      <!--          </q-card>-->
      <!--        </router-link>-->
      <!--      </div>-->

      <div class="col-lg-3 col-md-3 col-sm-6 q-pa-lg">
        <router-link class="item item-link" to="/ventes/retour">
          <q-card clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Retour</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 400px">
        <q-card class="my-card">
          <q-card-section>
            <areachart c :series="series_vente_sum" title="Montants Vendus en FCFA" titletooltip="vente" />
          </q-card-section>
        </q-card>
      </div>

<!--      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 400px">-->
<!--        <q-card class="my-card">-->
<!--          <q-card-section>-->
<!--            <columnchart />-->
<!--          </q-card-section>-->
<!--        </q-card>-->
<!--      </div>-->

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-sm">
        <div class="row q-pa-sm print-hide">
          <div class="col-4 q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col-4 q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col-2 q-pa-sm"><br><q-btn color="secondary" label="filtrer" v-on:click="sales_stats_get()" /></div>
        </div>
        <q-table :rows="sales_stats" :columns="columns" row-key="id" :pagination="pagination">
          <template v-slot:top-left>
            <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
          </template>
          <template v-slot:top-right="props">
            <q-btn size="md" :label="'Nbre de produits vendus: '+ numerique(nbre_vendus)" /><br>
            <q-btn size="md" class="q-ml-sm" :label="'Montant total: '+numerique(montant_vendus)+' FCFA'" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md float-right" />
          </template>
        </q-table>
      </div>

    </div>

  </q-page>
</template>

<script>

import $httpService from '../boot/httpService';
import AreachartComponent from '../components/areachart.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
import Columnchart from "components/columnchart.vue";

export default {
  name: 'VenteName',
  data () {
    return {
      tab: '0',
      first: null,
      last: null,
      products: [],
      sales_stats: [],
      nbre_vendus: 0,
      montant_vendus: 0,
      filter: '',
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'quantite_vendu', align: 'center', label: 'Qté', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', label: 'prix_vente', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        // { name: 'remise_totale', label: 'Remise', field: 'remise_totale', format: val => `${this.numerique(val)}` },
        { name: 'montant_vendu', label: 'Montant Vendu', field: 'montant_vendu', format: val => `${this.numerique(val)}`, sortable: true },
        // { name: 'prix_achat', label: 'Prix Achat', field: 'prix_achat', format: val => `${this.numerique(val)}` },
        // { name: 'benefice_vente', label: 'Benefice', field: 'benefice_vente', sortable: true },
        { name: 'dateposted', label: 'Date Vente', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      products_columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'amount', align: 'left', label: 'Quantité Restante', field: 'amount', sortable: true }
        // { name: 'actions', label: 'Actions' }
      ],
      series_vente_count: [{ name: 'Montant Achete.', data: [] }],
      series_vente_sum: [{ name: 'Montant Vendu par mois', data: [] }],
      slide: 'style'
    }
  },
  mixins: [basemixin],
  components: {
    Columnchart,
    areachart: AreachartComponent
  },
  filters: {
    capitalize: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    },
    numerique: function (value) {
      if (!value) return ''
      return String(value).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }
  },
  mounted () {
    // var date = new Date();
    // this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    // this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.first = this.convert(new Date());
    this.last = this.convert(new Date());
    this.sales_stats_get();
    this.appro_stats_global();
    this.products_get();

    // const path = require('path');
    //
    // // This will save the database in the same directory as the application.
    // const location = path.join(__dirname, '');
    //
    // db.createTable('customers', location, (succ, msg) => {
    //     // succ - boolean, tells if the call is successful
    //     if (succ) {
    //         console.log(msg)
    //     } else {
    //         console.log('An error has occured. ' + msg)
    //     }
    // })
  },
  methods: {
    convert(str) {
      var date = new Date(str),
        mnth = ('0' + (date.getMonth() + 1)).slice(-2),
        day = ('0' + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join('-');
    },
    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/sales_stats', params)
        .then((response) => {
          this.sales_stats = response;
          this.nbre_vendus = _.sumBy(this.sales_stats, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_stats, 'montant_vendu');
        })
    },
    appro_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/appro_stats', params)
        .then((response) => {
          this.appro_stats = response;
          this.nbre_achetes = _.sumBy(this.appro_stats, 'amount');
          this.montant_achetes = _.sumBy(this.appro_stats, 'buying_price');
        })
    },
    appro_stats_global() {
      $httpService.getWithParams('/my/get/shop_stats')
        .then((response) => {
          // this.vente_sum = JSON.parse(response.vente_sum.replace(/null/g, '0'));
          let vente_sum = response.vente_credit_sum.vente;
          let credit_sum = response.vente_credit_sum.credit;
          const val3 = Object.values(vente_sum);
          const valcredit = Object.values(credit_sum);
          this.series_vente_sum = [
            { name: 'Vendu', data: val3 },
            { name: 'Encaissé', data: valcredit },
          ];
        });
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
        })
    }
  }
}
</script>

<style>
</style>
