<template>
  <q-page padding>

    <div class="row justify-center text-center">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md text-center">
        <q-card class="my-card text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <h5 class="text-center">Rubrique: Achats</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12 q-pa-md">
        <router-link class="item item-link" to="/achats/commandes">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Achats</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12 q-pa-md">
        <router-link class="item item-link" to="/achats/factures">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Factures</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12 q-pa-md">
        <router-link class="item item-link" to="/achats/credit">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Impayés</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12 q-pa-md">
        <router-link class="item item-link" to="/achats/avoir">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h5">Avoir</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-md-12 col-12 col-xs-12 q-pa-lg" style="min-width: 400px">
        <q-card class="my-card">
          <q-card-section>
            <areachart type="bar" color="#A66172" :series="series_appro_sum" title="Montants Achetes en FCFA" titletooltip="achat" />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-sm">
        <div class="row q-pa-sm print-hide">
          <div class="col-4 q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col-4 q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col-2 q-pa-sm"><br><q-btn color="teal" label="filtrer" v-on:click="appro_stats_get()" /></div>
        </div>

        <div class="col-md-10 col-sm-12 col-xs-12 q-pa-sm">
          <q-table :rows="appro_stats" :columns="appro_columns" row-key="id" :pagination="pagination">
            <template v-slot:top-left>
              <span>{{'Appro du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
            </template>
            <template v-slot:top-right="props">
              <q-btn size="md" :label="'Nbre de produits achetes: '+ numerique(nbre_achetes)" /><br>
              <q-btn size="md" class="q-ml-sm" :label="'Montant total: '+numerique(montant_achetes)+' FCFA'" />
              <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                     @click="props.toggleFullscreen" class="q-ml-md float-right" />
            </template>
          </q-table>
        </div>

      </div>

    </div>

  </q-page>
</template>



<script>
import $httpService from '../boot/httpService';
import AreachartComponent from '../components/areachart.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
export default {
  name: 'AchatPage',
  data () {
    return {
      tab: '0',
      first: null,
      last: null,
      products: [],
      appro_stats: [],
      appro_count: [],
      appro_sum: [],
      credit: 0,
      nbre_achetes: 0,
      montant_achetes: 0,
      data: [],
      filter: '',
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      appro_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'amount', label: 'Quantite', field: 'amount', align: 'left', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'p_buying_price', align: 'center', label: 'Prix Achat', field: 'p_buying_price', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'p_sell_price', label: 'Prix Vente', align: 'left', field: 'p_sell_price', format: val => `${this.numerique(val)}`, sortable: true },
        // { name: 'price', label: 'Prix Achat Moyen', align: 'left', field: 'price', format: val => `${this.numerique(val)}` },
        // { name: 'sales_price', label: 'Prix Vente Moyen', align: 'left', field: 'sales_price', format: val => `${this.numerique(val)}` },
        { name: 'dateposted', label: 'Date Achat', align: 'left', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      products_columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'amount', align: 'left', label: 'Quantité Restante', field: 'amount', sortable: true }
        // { name: 'actions', label: 'Actions' }
      ],
      series: [{ name: 'Nbre de Produit.', data: [] }],
      series_appro_sum: [{ name: 'Montant Achete par mois', data: [] }],
      slide: 'style'
    }
  },
  mixins: [basemixin],
  components: {
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
  created () {
    // var date = new Date();
    // this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    // this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.first = this.convert(new Date());
    this.last = this.convert(new Date());
    this.appro_stats_get();
    this.appro_stats_global();
    this.products_get();
  },
  methods: {
    convert(str) {
      var date = new Date(str),
        mnth = ('0' + (date.getMonth() + 1)).slice(-2),
        day = ('0' + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join('-');
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
          // this.appro_count = JSON.parse(response.appro_count.replace(/null/g, '0'));
          // const val = Object.values(this.appro_count);
          // this.series = [{ name: 'Nbre Dons.', data: val }];

          // this.appro_sum = JSON.parse(response.appro_sum.replace(/null/g, '0'));
          this.appro_sum = response.appro_credit_sum.appro;
          let credit = response.appro_credit_sum.credit;
          const val2 = Object.values(this.appro_sum);
          const creditval = Object.values(credit);
          console.log(val2)
          this.series_appro_sum = [
            { name: 'Total', data: val2 },
            { name: 'Versé', data: creditval },
          ];
        });
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
        })
    },
    alerte(item) {
      if (item.amount <= item.alert_threshold) {
        return 'bg-red-2';
      }
    }
  }
}
</script>


<style>
</style>
