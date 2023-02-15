<template>

  <q-page>

    <div class="row  q-pa-xs q-ma-xs">
      <q-btn label="Ajouter une depense" class="offset-lg-1 offset-md-1" size="sm" icon="add" color="secondary" v-on:click="medium = true" />
      <br>
    </div>

    <div class="row justify-center text-center  q-pa-xs q-ma-xs">

      <div class="col-md-10 col-sm-12 col-xs-12">

        <q-table title="Treats" :rows="data" :columns="columns" row-key="name" class="q-pa-md q-ma-md"
                 :pagination="pagination">
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Depenses</div>
            <download-excel name="depenses.xls" :data="data">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md"></q-btn>
            </download-excel>
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="price" :props="props"> {{props.row.price}} </q-td>
              <q-td key="client" :props="props"> {{props.row.client}} </q-td>
              <q-td key="email" :props="props"> {{props.row.email}} </q-td>
              <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn size="xs" icon="edit" v-on:click="btn_update(props.row); medium = true"></q-btn>
                <q-btn color="red-4" size="xs" icon="delete" v-on:click="btn_delete()"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <q-dialog v-model="medium">
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6">Ajouter une depense</div>
            </q-card-section>

            <q-card-section>
              <div class="row justify-center">
                <div class="col-10">

                  <q-form  @submit="onSubmit" @reset="onReset" class="q-gutter-md"  >
                    <q-input autocomplete v-model="depense.name" label="Titre *" hint="Titre"
                             lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
                    <q-input autocomplet type="textarea" v-model="depense.description" label="Description *" />
                    <q-input autocomplete  v-model="depense.price" label="Prix Prestation *" />
                    <q-input autocomplete type="date" v-model="depense.date" label="Date *"
                             lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
                    <q-input type="text" v-model="depense.code_comptable"  label="code comptable"></q-input>
                    <q-input type="text" v-model="depense.client"  label="Beneficiaire *"></q-input>
                    <q-input type="text" v-model="depense.telephone"  label="Telephone *"></q-input>
                    <q-input type="email" v-model="depense.email"  label="Email *"></q-input>
                    <div>
                      <q-btn label="Modifier" v-if="status_update" v-on:click="depense_update()" type="button" color="secondary"/>
                      <q-btn label="Valider" v-if="!status_update"  type="submit" color="secondary"/>
                    </div>
                  </q-form>

                </div>
              </div>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

      </div>

    </div>

  </q-page>

</template>

<script>
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin'
export default {
  name: 'DepensePage',
  data () {
    return {
      selected: [],
      options: [],
      depense: {},
      date: '2020-03-10',
      name: null,
      description: null,
      tva: null,
      code_comptable: null,
      client: null,
      image: 'hhghjj',
      price: null,
      email: null,
      telephone_code: null,
      telephone: null,
      model: null,
      filter: '',
      status_update: false,
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
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true },
        { name: 'name', align: 'center', label: 'Titre', field: 'name', sortable: true },
        { name: 'price', required: true, label: 'Depense', field: row => row.price, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'client', label: 'Beneficiaire', field: 'client', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'telephone', label: 'Telephone', field: 'telephone', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      data: []
    }
  },
  components: {
    'downloadExcel': vue3JsonExcel
  },
  created () {
    this.loadData();
    var today = new Date();
    this.depense.date = this.formatDate(today);
    this.date = this.formatDate(today);
  },
  mixins: [basemixin],
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/depenses')
        .then((response) => {
          this.data = response;
          this.data_status = true;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    onSubmit () {
      if (this.accept !== true) {
        this.depense_register();
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
    btn_update(item) {
      this.service = item;
      this.status_update = true;
    },
    btn_delete() {
      this.status_update = true;
    },
    depense_register () {
      $httpService.postWithParams('/my/post/depenses', this.depense)
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
    depense_update () {
      $httpService.postWithParams('/my/put/depenses', this.depense)
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
    }
  }

}
</script>

<style>
</style>
