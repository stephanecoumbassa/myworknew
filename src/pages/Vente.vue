<template>
  <q-page padding>

    <div class="row justify-center text-center">

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-md text-center">
        <q-card class="my-card text-center justify-center content-center" flat>
          <q-item>
            <q-card-section>
              <h5 class="text-center">Rubrique: Ventes</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/ventes/new')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h5">Ventes</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/ventes/factures')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h5">Factures</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/ventes/credit')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h5">Credits</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-6 q-pa-md">
        <q-card clickable flat class="pointer" @click="$router.push('/ventes/retour')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h5">Retour</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-md" style="min-width: 400px">
        <q-card flat class="no-margin">
          <q-card-section>
            <!--            <areachart type="bar" :series="series_vente_sum" title="Montants Vendus en FCFA" titletooltip="vente" />-->
            <mixedchart :series="series_vente_sum" />
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

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 q-pa-md">
        <div class="row q-pa-sm print-hide">
          <div class="col-4 q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col-4 q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col-2 q-pa-sm"><br><q-btn color="secondary" label="filtrer" @click="sales_stats_get()" /></div>
        </div>
        <q-table :rows="sales_stats" :columns="columns" row-key="id" :pagination="pagination" flat>
          <template #top-left>
            <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
          </template>
          <template #top-right="props">
            <q-btn size="md" :label="'Nbre de produits vendus: '+ numerique(nbre_vendus)" /><br>
            <q-btn size="md" class="q-ml-sm" :label="'Montant total: '+numerique(montant_vendus)+' FCFA'" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md float-right" @click="props.toggleFullscreen" />
          </template>
        </q-table>
      </div>

    </div>

  </q-page>
</template>

<script>

import $httpService from '../boot/httpService';
// import AreachartComponent from '../components/areachart.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
// import Columnchart from "components/columnchart.vue";
import Mixedchart from "components/mixedchart.vue";
import {SalesApi} from "src/services/api/salesApi";

export default {
  name: 'VenteName',
  components: {
    Mixedchart,
    // Columnchart,
    // areachart: AreachartComponent
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
  mixins: [basemixin],
  data () {
    return {
      first: null,
      last: null,
      products: [],
      sales_stats: [],
      nbre_vendus: 0,
      montant_vendus: 0,
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
      series_vente_sum: [{ name: 'Montant Vendu par mois', data: [] }],
    }
  },
  mounted () {
    this.sales_stats_get();
    this.shop_stats();
    this.products_get();
  },
  methods: {
    async sales_stats_get() {
      this.sales_stats = await SalesApi.salesStates(this.first, this.last, 1);
      this.nbre_vendus = _.sumBy(this.sales_stats, 'quantite_vendu');
      this.montant_vendus = _.sumBy(this.sales_stats, 'montant_vendu');
    },
    shop_stats() {
      $httpService.getWithParams('/my/get/shop_stats')
        .then((response) => {
          let vente_sum = response.vente_credit_sum.vente;
          let credit_sum = response.vente_credit_sum.credit;
          const val3 = Object.values(vente_sum);
          const valcredit = Object.values(credit_sum);
          const budgetrevenu = Object.values(response.budgetrevenu);
          console.log(response);
          this.series_vente_sum = [
            { name: 'Vendu', type: 'column', data: val3 },
            { name: 'Encaissé', type: 'column', data: valcredit },
            {name: 'Budget', type: 'line', data: budgetrevenu }
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
