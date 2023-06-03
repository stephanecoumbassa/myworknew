<template>

  <q-page>

    <div class="row q-pa-xs q-ma-xs">
      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md">
        <q-btn label="Ajouter une depense" class="offset-lg-1 offset-md-1" size="sm" icon="add" color="secondary" @click="medium = true" />
        <br>
      </div>
    </div>

    <div class="row justify-center text-center q-pa-xs q-ma-xs">

      <div class="col-md-12 col-sm-12 col-xs-12">

        <q-table
id="printMe" title="Treats" :rows="data" :columns="columns" row-key="name" class="q-pa-md q-ma-md"
                 :pagination="pagination" :filter="filter" :grid="grid" dense flat>
          <template #top>
            <div class="print-hide col-4 q-table__title">Depenses</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md print-hide" @click="json2csv(data, 'vente')"/>
            <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md print-hide" />
            <br>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="price" :props="props"> {{props.row.price}} </q-td>
              <q-td key="client" :props="props"> {{props.row.client}} </q-td>
              <q-td key="email" :props="props"> {{props.row.email}} </q-td>
              <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
              <q-td key="date" :props="props"> {{props.row.date}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn size="xs" icon="edit" @click="btn_update(props.row); medium = true"></q-btn>
                <q-btn color="red-4" size="xs" icon="delete" @click="btn_delete()"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <q-dialog v-model="medium">
          <DepenseAdd :depense="depense" @reload="loadData()" />
        </q-dialog>

      </div>

    </div>

  </q-page>

</template>

<script>
import $httpService from '../boot/httpService';
// import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin'
import DepenseAdd from "components/DepenseAdd.vue";
export default {
  name: 'DepensePage',
  components: {
    DepenseAdd,
    // 'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  data () {
    return {
      depense: {},
      filter: '',
      status_update: false,
      grid: false,
      medium: false,
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: [
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true },
        { name: 'name', align: 'center', label: 'Titre', field: 'name', sortable: true },
        { name: 'price', required: true, label: 'Depense', field: row => row.price, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'client', label: 'Beneficiaire', field: 'client', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'telephone', label: 'Telephone', field: 'telephone', sortable: true },
        { name: 'date', label: 'Date', field: 'date', sortable: true },
        { name: 'actions', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      data: []
    }
  },
  created () {
    this.loadData();
    var today = new Date();
    this.depense.date = this.formatDate(today);
    this.date = this.formatDate(today);
  },
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/depenses')
        .then((response) => {
          this.data = response;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    btn_update(item) {
      this.depense = item;
      this.service = item;
      this.status_update = true;
    },
    btn_delete() {
      this.status_update = true;
    },
  }
}
</script>

<style>
</style>
