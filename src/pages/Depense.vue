<template>

  <q-page padding>

    <div class="row justify-center">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md">

        <q-table
          id="printMe" title="Treats" :rows="data" :columns="columns" row-key="name"
          :pagination="pagination" :filter="filter" :grid="grid" flat>
          <template #top>
            <div class="col-4 q-table__title">Liste des dépenses</div>
            <div class="col-4">
              <q-input v-model="filter" dense debounce="300" placeholder="Rechercher" />
            </div>
            <div class="col-4 text-right">
              <q-btn @click="json2csv(data, 'depenses')" round icon="far fa-file-excel" size="sm" title="excel" class="q-ma-sm" color="secondary"  />
              <q-btn v-print="'#printMe'" round icon="print" size="sm" class="q-ma-sm" color="secondary" />
              <q-btn @click="medium = true" id="add" round class="q-ma-sm" size="sm" icon="add" color="secondary" />
            </div>
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
                <q-btn color="grey-9" size="xs" icon="edit" @click="btn_update(props.row); medium = true"></q-btn>
                &nbsp;
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
