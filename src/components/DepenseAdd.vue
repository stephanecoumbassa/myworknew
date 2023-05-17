<template>
  <q-card flat style="width: 700px; max-width: 80vw;">
    <q-card-section>
      <div class="text-h6">Ajouter une depense</div>
    </q-card-section>

    <q-card-section>
      <div class="row justify-center">
        <div class="col-10">
          <q-form  @submit="onSubmit" @reset="onReset" class="q-gutter-md"  >
            <q-input outlined dense autocomplete v-model="depense.name" label="Titre *" hint="Titre"
                     lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
            <q-input outlined dense autocomplet type="textarea" v-model="depense.description" label="Description *" />
            <q-input outlined dense autocomplete  v-model="depense.price" label="Prix Prestation *" />
            <q-input outlined dense autocomplete type="date" v-model="depense.date" label="Date *"
                     lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
            <q-input outlined dense type="text" v-model="depense.code_comptable"  label="code comptable"></q-input>
            <q-input outlined dense type="text" v-model="depense.client"  label="Beneficiaire *"></q-input>
            <q-input outlined dense type="text" v-model="depense.telephone"  label="Telephone *"></q-input>
            <q-input outlined dense type="email" v-model="depense.email"  label="Email *"></q-input>
<!--            {{depense.projetid}}-->
            <q-select class="print-hide col-md-6 col-sm-12" filled map-options emit-value :dense="true"
                      v-model="depense.projetid" :options="p_projets" label="Projets" :option-value="'id'" :option-label="'titre'"
                      input-debounce="0" />
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
</template>

<script>
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from '../pages/basemixin'
export default {
  name: 'DepenseAdd',
  emits: ['reload'],
  props: ['depense', 'projetid'],
  data () {
    return {
      selected: [],
      options: [],
      // depense: {},
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
      grid: false,
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
        { name: 'date', label: 'Date', field: 'date', sortable: true },
        { name: 'actions', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      data: [],
      p_projets: [],
    }
  },
  components: {
    'downloadExcel': vue3JsonExcel
  },
  created () {
    var today = new Date();
    this.depense.date = this.formatDate(today);
    this.date = this.formatDate(today);
    this.p_projet_get();
    this.loadData();
  },
  mixins: [basemixin],
  methods: {
    p_projet_get () {
      $httpService.getApi('/my/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },
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
          this.$emit('reload', true);
          this.loadData();
        }).catch(() => {
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
          this.$emit('reload', true);
          this.loadData();
        }).catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    }
  }
}
</script>
