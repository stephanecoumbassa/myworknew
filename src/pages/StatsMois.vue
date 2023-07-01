<template>
  <q-page>
    <div class="row justify-center">
      <div class="col-lg-11 col-12">
        <!-- content -->
        <br>

        <div class="row text-center">
          <div class="col-md-3 col-sm-6 col-xs-12 q-pa-sm">
            <q-card class="q-pa-sm pointer" flat clickableflat @click="$router.push('/clients')">
              <q-card-section>
                Clients: <br><br><span class="text-h5">{{clients_count}}</span>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12 q-pa-sm">
            <q-card
              class="q-pa-sm pointer" flat clickableflat
              @click="$router.push('/users')">
              <q-card-section>
                Utilisateurs: <br><br>
                <span class="text-h5">{{users_count}}</span>
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-3 col-sm-6 col-xs-12 q-pa-sm">
            <q-card
              class="q-pa-sm pointer" flat clickableflat
              @click="$router.push('/fournisseurs')">
              <q-card-section>Fournisseurs: <br><br>
                <span class="text-h5">{{fournisseurs_count}}</span>
              </q-card-section>
            </q-card>
          </div>
          <div
            class="col-md-3 col-sm-6 col-xs-12 q-pa-sm"
            @click="$router.push('/ventes/new')">
            <q-card class="q-pa-sm pointer" flat clickable>
              <q-card-section>Vente Jour: <br><br>
                <span class="text-h5">{{numerique(jour_stat.quantite ?? 0)}}</span>
              </q-card-section>
              <!--                <q-card-section> <span class="text-h5">{{numerique(jour_stat.somme)}}</span> CFA </q-card-section>-->
            </q-card>
          </div>
        </div>

        <div class="row" style="max-width: 1265px">
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 350px">
            <q-card class="my-card" flat>
              <q-card-section>
                <areachart color="#FF9900" :series="series_vente_sum" title="Montants Vendus en FCFA" titletooltip="vente" />
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 350px">
            <span style="color: #ec8888"></span>
            <q-card class="my-card" flat>
              <q-card-section>
                <areachart color="#ec8888" :series="series_appro_sum" title="Montants d'achat de matière première" titletooltip="achat" />
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 350px">
            <q-card class="my-card" flat>
              <q-card-section>
                <!--                <areachart type="bar" :horizontal="true" :series="service_sum_data" title="Montants generere par les services" titletooltip="service" />-->
                <areachart type="bar" :horizontal="true" :series="service_sum_data" title="Cumul des sorties(Achats, Dépense, Salaire)" titletooltip="service" />
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 350px">
            <q-card class="my-card" flat>
              <q-card-section>
                <areachart color="red" :series="depense_sum_data" title="Montants des depenses" titletooltip="depense" />
                <!--                <areachart color="primary" type="bar" :horizontal="false" :series="depense_sum_data" title="Montants des depenses" titletooltip="depense" />-->
              </q-card-section>
            </q-card>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm">
            <q-table :rows="sales_stats" :columns="columns" row-key="id" :pagination="pagination" flat>
              <template #top-left>
                <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
              </template>
              <template #top-right="props">
                <q-btn size="sm" :label="'Nbre de produits vendus: '+ numerique(nbre_vendus)" /><br>
                <q-btn size="sm" class="q-ml-sm" :label="'Montant total: '+numerique(montant_vendus)+' FCFA'" />
                <q-btn
                  flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                  class="q-ml-md float-right" @click="props.toggleFullscreen" />
              </template>
            </q-table>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 q-pa-sm">
            <q-table :rows="appro_stats" :columns="appro_columns" row-key="id" :pagination="pagination" flat>
              <template #top-left>
                <span>{{'Appro du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
              </template>
              <template #top-right="props">
                <q-btn size="sm" :label="'Nbre de produits achetes: '+ numerique(nbre_achetes)" /><br>
                <q-btn size="sm" class="q-ml-sm" :label="'Montant total: '+numerique(montant_achetes)+' FCFA'" />
                <q-btn
                  flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                  class="q-ml-md float-right" @click="props.toggleFullscreen" />
              </template>
            </q-table>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12  q-pa-sm">
            <q-table
              title="Produits" :rows="products" :columns="products_columns" flat
              :pagination="pagination" :filter="filter" row-key="name">
              <template #top="props">
                <div class="col-7 q-table__title">Liste des produits</div>
                <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
                <q-btn
                  flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                  class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
              </template>
              <template #body="props">
                <!--                <q-tr :props="props" :class="alerte(props.row)">-->
                <q-tr :props="props">
                  <q-td key="id" :props="props"> {{props.row.id}} </q-td>
                  <q-td key="name" :props="props"> {{props.row.name}} </q-td>
                  <q-td key="amount" :props="props"> {{props.row.reste}} </q-td>
                  <q-td key="actions" :props="props">  </q-td>
                </q-tr>
              </template>
            </q-table>
          </div>
        </div>
        <!--          </q-tab-panel>-->

        <!--        </q-tab-panels>-->
        <!--    </q-card>-->

      </div>
    </div>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import {salesColumnsHome, approColumnsHome, productColumnsHome} from '../services/columnService';
import AreachartComponent from '../components/areachart.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
// import {useCounterStore} from "stores/base-store";

export default {
  components: {
    areachart: AreachartComponent
  },
  mixins: [basemixin],
  name: 'StatsMoisPage',
  data () {
    return {
      products: [],
      sales_stats: [],
      appro_stats: [],
      appro_count: [],
      appro_sum: [],
      vente_count: [],
      vente_sum: [],
      depense_sum: [],
      depense_sum_data: [],
      service_sum: [],
      service_sum_data: [],
      credit: 0,
      nbre_achetes: 0,
      montant_achetes: 0,
      nbre_vendus: 0,
      montant_vendus: 0,
      users_count: 0,
      clients_count: 0,
      fournisseurs_count: 0,
      jour_stat: {},
      filter: '',
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: salesColumnsHome,
      appro_columns: approColumnsHome,
      products_columns: productColumnsHome,
      series: [{ name: 'Nbre de Produit.', data: [] }],
      series_appro_sum: [{ name: 'Montant Achete par mois', data: [] }],
      series_vente_count: [{ name: 'Montant Achete.', data: [] }],
      series_vente_sum: [{ name: 'Montant Vendu par mois', data: [] }],
    }
  },
  mounted () {
    this.sales_stats_get();
    this.appro_stats_get();
    this.appro_stats_global();
    this.products_get();
    // const store = useCounterStore()
    // console.log(store.increment())
    // console.log(store.counter)
    // console.log(store.doubleCount)
  },
  methods: {

    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      console.log(params)
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
          this.credit = response.restant;
          this.jour_stat = response.vente_jour;

          this.users_count = response.users_count;
          this.clients_count = response.clients_count;
          this.fournisseurs_count = response.fournisseurs_count;

          this.appro_count = response.appro_count;
          const val = Object.values(this.appro_count);
          this.series = [{ name: 'Nbre Dons.', data: val }];

          this.appro_sum = response.appro_sum;
          const val2 = Object.values(this.appro_sum);
          this.series_appro_sum = [{ name: 'Francs CFA.', data: val2 }];

          this.vente_sum = response.vente_sum;
          const val3 = Object.values(this.vente_sum);
          this.series_vente_sum = [{ name: 'Francs CFA.', data: val3 }];

          this.vente_count = response.vente_count;
          const val4 = Object.values(this.vente_count);
          this.series_vente_count = [{ name: 'Francs CFA.', data: val4 }];

          this.service_sum = response.service_sum;
          const val5 = Object.values(this.service_sum);
          this.service_sum_data = [{ name: 'Francs CFA.', data: val5 }];

          this.depense_sum = response.depense_sum;
          const val6 = Object.values(this.depense_sum);
          this.depense_sum_data = [{ name: 'Francs CFA.', data: val6 }];
        });
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
        })
    },
    // alerte(item) {
    // if (item.amount <= item.alert_threshold) {
    //   return 'bg-red-2';
    // }
    // }
  }
}
</script>

<style>
</style>
