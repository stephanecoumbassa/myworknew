<template>
  <q-page padding>
    <br>
    <br>

    <div class="row q-mb-lg">
      <q-btn label="Ajouter un service" class="offset-lg-1" size="sm" icon="add" color="secondary" v-on:click="medium = true" />
    </div>


    <div class="row justify-center text-center">

      <div class="col-lg-10 col-12">
        <q-table title="Treats" :rows="data" :columns="columns" row-key="name"
                 :pagination="pagination">
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Prestations</div>
            <download-excel name="services.xls" :data="data">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="photo" :props="props">
                <img v-if="props.row.photos" :src="uploadurl+'/'+entreprise.id+'/product/'+JSON.parse(props.row.photos)[0]['name']" style="width: 50px; height: 50px; object-fit: cover"/>
              </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="price_min" :props="props"> {{props.row.price_min}} </q-td>
              <q-td key="price_max" :props="props"> {{props.row.price_max}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn size="xs" icon="edit" v-on:click="btn_update(props.row); medium = true" />&nbsp;
                <q-btn class="q-mr-xs" size="xs" color="blue-grey-7" label="photo" v-on:click="photo_get(props.row)" icon="photo" />&nbsp;
                <q-btn color="red-4" size="xs" icon="delete" v-on:click="btn_delete()" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="medium">
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section>
            <div class="row">
              <div class="col-10 text-h6">Service</div>
            </div>
          </q-card-section>

          <q-card-section>
            <div class="row justify-center">
              <!--      <div class="col-2">1</div>-->
              <div class="col-10">

                <q-form  @submit="onSubmit" @reset="onReset" class="q-gutter-md">

                  <q-input autocomplete v-model="service.name" label="Titre *" hint="Titre"
                           lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
                  <q-input autocomplet type="textarea" v-model="service.description" label="Description *" />
                  <q-input autocomplete  v-model="service.price_min" label="Prix Minimum" />
                  <q-input autocomplete type="text" v-model="service.price_max" label="Prix Maximum" />
                  <q-select v-model="service.domain_id" :options="domains" label="Domains" map-options emit-value :dense="true"
                            option-value="id" stack-label input-debounce="0" option-label="name"
                            @input="parent_get(service.domain_id)" :rules="[ val => val || 'champs obligattoire']" />

                  <q-select v-model="service.categorie_id" :options="parents" label="Parents" map-options emit-value :dense="true"
                            option-value="id" stack-label input-debounce="0" option-label="name"
                            @input="categorie_get(service.categorie_id)" :rules="[ val => val || 'champs obligattoire']" />

                  <div>
                    <q-btn label="Modifier" v-if="status_update" v-on:click="service_update()" type="button" color="secondary"/>
                    <q-btn label="Valider" v-if="!status_update"  type="submit" color="secondary"/>
                  </div>
                </q-form>

              </div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn flat label="Fermer" v-close-popup v-on:click="btn_update = false ; service = {}" />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="medium2">
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section>
            <div class="text-h6">Gestion des photos</div>
          </q-card-section>

          <q-card-section>
            <hello-component :width="300" :height="300" :typerubrique="3" :idligne="product_id" folder="product" />
            <br>
            <br>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn flat label="Fermer" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>

    </div>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import HelloComponent from '../components/hello.vue';

export default {
  name: 'ServiceItemPage',
  data () {
    return {
      selected: [],
      options: [],
      parents: [],
      parents2: [],
      categories: [],
      categories2: [],
      categoriesall: [],
      domains: [],
      domains2: [],
      service: { status_client: true },
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
      product_id: null,
      telephone_code: null,
      telephone: null,
      model: null,
      status_update: false,
      filter: '',
      medium: false,
      loading: false,
      visibleColumns: ['email', 'phoneNumber', 'type'],
      data_status: false,
      medium2: false,
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      },
      columns: [
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'photo', align: 'left', label: 'Photo', classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' },
        { name: 'price_min', required: true, label: 'Prix Min', field: row => row.price_min, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'price_max', required: true, label: 'Prix Max', field: row => row.price_max, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'actions', label: 'Actions' }
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
    this.categories_all();
    // this.loadData2();
  },
  components: {
    HelloComponent,
    'downloadExcel': JsonExcel
  },
  mixins: [basemixin],
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/services_items')
        .then((response) => {
          this.data = response;
          this.data_status = true;
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    categories_all () {
      $httpService.getWithParams('/my/get/categories_all')
        .then((response) => {
          this.categoriesall = response;
          this.domains = response[0];
          this.domains2 = response[0];
          this.parents = response[1];
          this.parents2 = response[1];
          this.categories = response[2];
          this.categories2 = response[2];
        })
    },
    parent_get(value) {
      let val = this.parents2.filter((x) => {
        return x.domainid == value;
      });
      this.parents = val;
    },
    categorie_get (parentid) {
      let val = this.categories2.filter((x) => {
        return x.parentid == parentid;
      });
      this.categories = val;
    },
    btn_update(item) {
      this.service = item;
      this.status_update = true;
    },
    btn_delete() {
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
      $httpService.postWithParams('/my/post/services_items', this.service)
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
      $httpService.postWithParams('/my/put/services_items', this.service)
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
    },
    photo_get(props) {
      this.product_id = props.id;
      this.medium2 = true;
    }
  }

}
</script>

<style>
</style>
