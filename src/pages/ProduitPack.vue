<template>
  <q-page padding>
    <!--  <div class="row justify-center">-->
    <br>

    <div class="row q-mb-lg">
      <q-btn label="Ajouter un pack" class="offset-lg-1" size="sm" icon="add" color="secondary" @click="medium = true" />
      <br>
      <br>
    </div>


    <div class="row justify-center text-center">

      <div class="col-lg-11 col-12">
        <q-table
title="Treats" :data="data" :columns="columns" row-key="name"
                 :pagination="pagination">
          <template #top="props">
            <div class="col-4 q-table__title">Pack Produit</div>
            <download-excel name="marques.xls" :json-data="data">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="nom" :props="props"> {{props.row.nom}} </q-td>
              <q-td key="description" :props="props"> {{props.row.description}} </q-td>
              <q-td key="photo" :props="props"> {{props.row.photo}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn size="xs" icon="edit" @click="btn_update(props.row); medium = true" />&nbsp;
                <q-btn color="red-4" size="xs" icon="delete" @click="marque_delete(props.row.id)" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="medium" position="top">
        <q-card style="width: 700px; max-width: 90vw;">
          <q-card-section>
            <div class="row">
              <div class="col-10 text-h6">Ajouter une marque</div>
            </div>
          </q-card-section>

          <q-card-section>
            <div class="row justify-center">
              <div class="col-11">

                <q-form  class="q-gutter-md" @submit="onSubmit"  >
                  <q-input v-model="marque.nom"  autocomplete label="Nom *" />
                  <q-input v-model="marque.description" autocomplet outlined type="textarea" label="Description *" />
                  <q-input v-model="marque.photo"  autocomplete type="file" stack-label label="Photo *" />
                  <div>
                    <q-btn v-if="status_update" label="Modifier" type="button" color="secondary" @click="marque_update()"/>&nbsp;&nbsp;
                    <q-btn v-if="!status_update" label="Valider" type="submit" color="secondary"/>
                  </div>
                </q-form>

              </div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn v-close-popup flat label="Fermer" @click="marque = {}" />
          </q-card-actions>
        </q-card>
      </q-dialog>

    </div>
  </q-page>
</template>

<script>
// import $httpservice from '../boot/httpservice';
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin'
export default {
  name: 'ProduitPage',
  components: {
    'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  data () {
    return {
      selected: [],
      marques: [],
      marques_items: [],
      options: [],
      marque: { status_client: true },
      // date: '2020-03-10',
      date: '2020-03-10',
      name: null,
      description: null,
      tva: null,
      code_comptable: null,
      client: null,
      clients: [],
      clients2: [],
      image: 'hhghjj',
      price: null,
      email: null,
      telephone_code: null,
      telephone: null,
      model: null,
      status_update: false,
      filter: '',
      medium: false,
      loading: false,
      visibleColumns: ['email', 'phoneNumber', 'type'],
      data_status: false,
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: [
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'nom', align: 'left', label: 'Nom', field: 'nom', sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'description', required: true, label: 'Description' },
        { name: 'photo', label: 'Photo', field: 'photo', sortable: true },
        { name: 'actions', label: 'Actions' }
        // { name: 'type', label: 'Type', field: 'type', sortable: true }
      ],
      data: []
    }
  },
  created () {
    this.loadData();
  },
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/marques')
        .then((response) => {
          this.data = response;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    btn_update(item) {
      this.marque = item;
      this.status_update = true;
    },
    btn_delete() {
      this.status_update = true;
    },
    onSubmit () {
      if (this.accept !== true) {
        this.marque_register();
      } else {
        this.$q.notify({
          color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted', splitterModel: 20, model: null
        });
      }
    },
    marque_register () {
      $httpService.postWithParams('/my/post/marques', this.marque)
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
          this.loadData();
        })
        .catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    },
    marque_update () {
      $httpService.postWithParams('/my/put/marques', this.marque)
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
          this.loadData();
        })
        .catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    },
    marque_delete (_id) {
      $httpService.postWithParams('/my/delete/marques/' + _id)
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
          this.loadData();
        })
        .catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.clients = this.clients2.filter(
          (v) => { return v.fullname.toLocaleLowerCase().indexOf(needle) > -1 }
        );
      })
    }
  }

}
</script>

<style>
</style>
