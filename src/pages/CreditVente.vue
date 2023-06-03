<template>
  <q-page padding>
    <div class="row justify-center">

      <div class="col-lg-12 col-12">

        <br><br>
        <q-table title="Produits" :rows="credits" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des ventes et des credits<br>
              <q-btn color="red"> Impayés
                {{ numerique( array_somme(credits, 'total') - array_somme(credits, 'credit') ) }}
              </q-btn>
            </div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id"> {{props.row.id_vente}} </q-td>
              <q-td key="name"> {{props.row.client_id}} </q-td>
              <q-td key="total"> {{ numerique(props.row.total) }} </q-td>
              <q-td key="credit"> {{ numerique(props.row.credit) }} </q-td>
              <q-td key="reste"> {{ numerique(props.row.total - props.row.credit) }} </q-td>
              <q-td key="actions">

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
import basemixin from './basemixin'

export default {
  components: {
  },
  mixins: [basemixin],
  data () {
    return {
      products_list: [],
      products: [],
      credits: [{ details: {} }],
      columns: [
        { name: 'id_ap', align: 'left', label: 'Facture Id', field: 'id_ap', sortable: true },
        { name: 'Client', align: 'left', label: 'Client Id', field: 'client_id', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'credit', align: 'left', label: 'Payés', field: 'credit', sortable: true },
        { name: 'reste', align: 'left', label: 'Reste', field: 'reste', sortable: true },
        { name: 'actions', label: 'Actions', align: 'right' }
      ],
      filter: '',
      date: '',
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 50
      }
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.total), 0);
    }
  },
  created () {
    this.products_get();
    this.credit_get();
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
  },
  methods: {
    credit_get () {
      $httpService.getWithParams('/my/get/credit_vente')
        .then((response) => {
          this.credits = response;
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    }
  }
}
</script>

<style>
</style>
