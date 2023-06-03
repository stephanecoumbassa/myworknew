<template>
  <q-page>

    <div class="row justify-center text-center q-pa-md">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center">
          <q-item clickable>
            <q-card-section>
              <div class="text-center text-h5">Rubrique: Produits - Stock Inital par année</div>
              <br>
              <span>
                <q-input
v-model="date" type="number" placeholder="N°Inventaire" :dense="true"
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
          <template #top>
            <div class="col-7 q-table__title">Liste des produits
            </div>
            <div class="row">
              <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            </div>
          </template>
          <template #body="props">
            <q-tr :key="props.row.id" :props="props" v-bind="props.row.qty = 0">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="quantite" :props="props" class="float-right text-right">
                <q-input v-model="props.row.quantite" class="float-right text-right" type="number" dense style="width: 100px" />
<!--                {{props.row.quantite}} -->
              </q-td>
              <q-td key="date" :props="props"> {{props.row.date}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="sm" color="secondary" icon="verified" @click="stock_post(props.row)" />
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
  mixins: [basemixin],
  data () {
    return {
      products: [],
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        // { name: 'photo', align: 'left', label: 'photo' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'quantite', label: 'Quantite' },
        { name: 'date', label: 'Date' },
        // { name: 'difference', label: 'Diff' },
        { name: 'actions', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  mounted () {
    this.stock_get();
  },
  methods: {
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
