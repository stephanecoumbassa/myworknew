<template>
  <q-page>

    <div class="row justify-center text-center q-pa-md">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center">
          <q-item clickable>
            <q-card-section>
              <div class="text-center text-h5">Rubrique: Produits - Stocks</div>
              <br>
              <span>
                <q-input type="number" v-model="date" placeholder="N°Inventaire" :dense="true"
                         @change="stock_get()" />
              </span>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>


    <div class="row justify-center q-pa-md">
      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md">

        <q-table title="Produits" :rows="products" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des produits
            </div>
            <div class="row">
              <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" :key="props.row.id" v-bind="props.row.qty = 0">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="quantite" :props="props" class="float-right text-right">
                <q-input class="float-right text-right" type="number" dense v-model="props.row.quantite" style="width: 100px" />
<!--                {{props.row.quantite}} -->
              </q-td>
              <q-td key="date" :props="props"> {{props.row.date}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="sm" color="secondary" v-on:click="stock_post(props.row)" icon="verified" />
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin';
export default {
  name: 'ProduitStockPage',
  data () {
    return {
      tab: 'mails',
      product_id: 1,
      alert: false,
      loading1: false,
      date: '2023',
      red: '#6d1412',
      first: null,
      inventaire: null,
      inventaires: [],
      lists: [],
      lists_products: [],
      name: null,
      last: null,
      entreprise: {},
      search: '',
      maximizedToggle: true,
      products: [],
      product: { description: '', stock: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        // { name: 'photo', align: 'left', label: 'photo' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'quantite', label: 'Quantite' },
        { name: 'date', label: 'Date' },
        // { name: 'difference', label: 'Diff' },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  mixins: [basemixin],
  mounted () {
    this.stock_get();
    // this.inventaires_get();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
  },
  methods: {
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    stock_get () {
      $httpService.getWithParams('/my/get/stock/' + this.date)
        .then((response) => {
          this.products = response;
        })
    },
    stock_post(stock) {
      if (confirm("Voulez vous appliquer l'inventaire ?")) {
        $httpService.postWithParams('/my/post/stock', stock)
          .then((response) => {
            this.$q.notify({
              color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
            });
            this.products_get();
          });
      }
    },
  }
}
</script>

<style>
</style>
