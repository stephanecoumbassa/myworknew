<template>
  <q-page padding>
    <br>

    <div class="row q-mb-lg">
      <q-btn label="Ajouter une prestation" class="offset-lg-1" size="sm" icon="add" color="secondary" @click="medium = true" />
      <br>
    </div>


    <div class="row justify-center text-center">

      <div class="col-lg-11 col-12">
        <q-table
title="Treats" :rows="data" :columns="columns" row-key="name"
                 :pagination="pagination">
          <template #top="props">
            <div class="col-4 q-table__title">Prestations</div>
            <download-excel name="services.xls" :data="data">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="price" :props="props"> {{props.row.price}} </q-td>
              <q-td key="client" :props="props"> {{props.row.nom}} {{props.row.last_name}} </q-td>
              <q-td key="email" :props="props"> {{props.row.email}} </q-td>
              <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn size="xs" icon="edit" @click="btn_update(props.row); medium = true" />&nbsp;
                <q-btn color="red-4" size="xs" icon="delete" @click="service_delete(props.row.id)" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="medium" position="top">
        <q-card style="width: 700px; max-width: 90vw;">
          <q-card-section>
            <div class="row">
              <div class="col-10 text-h6">Ajouter une prestation</div>
            </div>
          </q-card-section>

          <q-card-section>
            <div class="row justify-center">
              <div class="col-10">

                <q-form  class="q-gutter-md" @submit="onSubmit" @reset="onReset"  >
                  <q-select
v-model="service.service_item" :options="services_items" label="Services" map-options emit-value
                            option-value="id" input-debounce="0" option-label="name" :rules="[ val => val || 'champs obligattoire']" />
                  <!--                <q-input autocomplete v-model="service.name" label="Titre *" hint="Titre"-->
                  <!--                         lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />-->
                  <q-input v-model="service.description" autocomplet type="textarea" label="Description *" />
                  <q-input v-model="service.price"  autocomplete label="Prix Prestation *" />
                  <q-input v-model="service.date" autocomplete type="date" label="Date *" />
                  <q-input v-model="service.tva" type="text"  label="TVA *" />
                  <q-input v-model="service.code_comptable" type="text"  label="code comptable" />
                  <q-select
v-model="service.clientid" map-options emit-value use-input :options="clients"
                            label="Client ID" option-value="id" option-label="fullname" :rules="[ val => val || 'champs obligattoire']" @filter="filterFn" />
                  <!--                <div class="q-gutter-sm">-->
                  <!--                    <q-radio color="secondary" v-model="service.status_client" :val="true" label="Déja client" />-->
                  <!--                    <q-radio color="dark" v-model="service.status_client" :val="false" label="Pas encore Client" />-->
                  <!--                </div>-->
                  <!--                <q-input v-if="!service.status_client && !service.clientid" type="text" v-model="service.client"  label="client *" />-->
                  <!--                <q-input v-if="!service.status_client && !service.clientid" type="text" v-model="service.telephone"  label="Telephone *" />-->
                  <!--                <q-input v-if="!service.status_client && !service.clientid" type="email" v-model="service.email"  label="Email *" />-->

                  <div>
                    <q-btn v-if="status_update" label="Modifier" type="button" color="secondary" @click="service_update()"/>&nbsp;&nbsp;
                    <q-btn v-if="!status_update" label="Valider" type="submit" color="secondary"/>
                    <!--                  <q-btn label="Annuler" type="reset" color="red" flat class="q-ml-sm" />-->
                  </div>
                </q-form>

              </div>
              <!--      <div class="col-2">1</div>-->
            </div>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn v-close-popup flat label="Fermer" @click="btn_update = false ; service = {}" />
          </q-card-actions>
        </q-card>
      </q-dialog>

    </div>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin'
export default {
  name: 'ServicesPage',
  components: {
    'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  data () {
    return {
      services_items: [],
      service: { status_client: true },
      date: '2020-03-10',
      name: null,
      client: null,
      clients: [],
      clients2: [],
      status_update: false,
      medium: false,
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: [
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'price', required: true, label: 'Prix', field: row => row.price, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'client', label: 'Client', field: 'client', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'telephone', label: 'Telephone', field: 'telephone', sortable: true },
        { name: 'actions', label: 'Actions' }
        // { name: 'type', label: 'Type', field: 'type', sortable: true }
      ],
      data: []
    }
  },
  created () {
    this.loadData();
    var today = new Date();
    this.service.date = this.formatDate(today);
    this.date = this.formatDate(today);
    this.clients_get();
    this.services_items_get();
    // this.loadData2();
  },
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/services')
        .then((response) => {
          this.data = response;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    services_items_get () {
      $httpService.getWithParams('/my/get/services_items')
        .then((response) => {
          this.services_items = response;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    btn_update(item) {
      this.service = item;
      this.status_update = true;
    },
    onSubmit () {
      if (this.accept !== true) {
        this.service_register();
      } else {
        this.$q.notify({
          color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted', splitterModel: 20, model: null
        });
      }
    },
    onReset () {
      this.name = null;
      this.age = null;
      this.accept = false;
    },
    service_register () {
      $httpService.postWithParams('/my/post/services', this.service)
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
    service_update () {
      $httpService.postWithParams('/my/put/services', this.service)
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
    service_delete (_id) {
      $httpService.postWithParams('/my/delete/services/' + _id)
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
    clients_get () {
      $httpService.getWithParams('/my/get/client')
        .then((response) => {
          this.clients = response;
          this.clients2 = response;
          this.client = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
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
