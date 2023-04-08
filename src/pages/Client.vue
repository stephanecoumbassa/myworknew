<template>

  <q-page>

    <div class="row justify-center text-center">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md justify-left text-left">
        <q-btn label="Ajouter" class="q-ma-sm" size="sm" icon="add" color="secondary" v-on:click="medium = true" />
      </div>

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md">
        <!--grid-->

        <q-table id="printMe" title="Treats" :rows="data" :columns="columns" row-key="name"
                 class="q-pa-md q-ma-md" :pagination="pagination" :filter="filter" flat>
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Clients</div>
            <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(data, 'vente')"/>
            <q-btn flat round dense icon="print" v-print="'#printMe'" class="q-ml-md" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"  @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="last_name" :props="props"> {{props.row.last_name}} </q-td>
              <q-td key="email" :props="props"> {{props.row.email}} </q-td>
              <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
              <q-td key="actions" :props="props">

                <q-btn-dropdown size="xs" color="dark">
                  <q-item clickable v-close-popup>
                    <q-item-label v-on:click="btn_update(props.row)"> Modifier</q-item-label>
                  </q-item>
                  <q-item clickable @click="medium2 = true">
                    <q-item-label>Enoyer un email</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Envoyer une proforma</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="vente_status = true; product_id = props.row.id; sales_stats_get(props.row.id);">
                    <q-item-label>Liste des achats</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="vente_status = true; product_id = props.row.id; sales_stats_get(props.row.id);">
                    <q-item-label>Liste des devis</q-item-label>
                  </q-item>
                </q-btn-dropdown>

              </q-td>
            </q-tr>
          </template>
        </q-table>
        <!--    <q-page-sticky position="bottom-right" :offset="[18, 18]">-->
        <q-dialog v-model="medium2">
          <div class="row" style="max-width: 100%; width: 800px; background: white">
            <div class="col-12 q-ma-md">
              <q-input v-model="post.subject" label="Sujet"></q-input>
            </div>
            <div class="col-12 q-ma-md">
              <q-editor v-model="post.body" :dense="$q.screen.lt.md" :definitions="definitions" :toolbar="toolbar" />
            </div>
            <div class="col-12 q-ma-md">
              <q-btn size="md" v-on:click="send_email()">Envoyer l'email</q-btn>
            </div>
          </div>
        </q-dialog>
        <!--    </q-page-sticky>-->
        <q-dialog v-model="medium" position="top">
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6" v-if="!status_update">Ajouter un client</div>
              <div class="text-h6" v-if="status_update">Modifier un client</div>
            </q-card-section>

            <q-card-section>
              <div class="row justify-center">
                <!--      <div class="col-2">1</div>-->
                <div class="col-10">

                  <q-form  @submit="onSubmit" @reset="onReset" class="q-gutter-md"  >
                    <q-select filled v-model="client.type" :options="options" label="Type de client"
                              map-options emit-value option-value="id" option-label="name" />
                    <q-input autocomplete v-model="client.name" label="Nom *"
                             lazy-rules :rules="[ val => val && val.length > 0 || 'Please type something']" />
                    <q-input autocomplete  v-model="client.last_name" label="Prenom *" />
                    <q-input type="number" v-model="client.telephone_code"  label="indicatif *" />
                    <q-input type="text" v-model="client.telephone" label="telephone *" />
                    <q-input type="email" v-model="client.email" label="Email *" />
                    <country-component v-model="client.country" code="civ"></country-component>
                    <q-input type="text" v-model="client.city" label="Ville" />
                    <q-input type="textarea" v-model="client.address" label="Adresse" />
                    <!--                    <q-input type="email" v-model="client.compagnie" label="Compagnie" />-->
                    <!--                <q-input type="password" v-model="client.password" label="Mot de passe *" />-->

                    <div>
                      <br>
                      <q-btn label="Modifier" v-if="status_update" v-on:click="client_update()" type="button" color="secondary"/>
                      <q-btn label="Valider" v-if="!status_update"  type="submit" color="secondary"/>
                    </div>
                  </q-form>

                </div>
                <!--      <div class="col-2">1</div>-->
              </div>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="medium3">
          <facture-component style="width: 1000px; max-width: 100%;"></facture-component>
        </q-dialog>

        <q-dialog v-model="vente_status" transition-show="slide-up" transition-hide="slide-down">

          <q-table :rows="sales_stats" :columns="sales_columns" style="width: 800px; max-width: 100%"
                   row-key="id" :pagination="pagination">
            <template v-slot:top>
              <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
            </template>
            <template v-slot:top-left>
              <div class="row">
                <div class="col-5 "><q-input type="date"  v-model="first" label="debut" /></div>
                <div class="col-5"><q-input type="date"  v-model="last" label="fin" /></div>
                <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" v-on:click="sales_stats_get(product_id)"/></div>
              </div>
            </template>
            <template v-slot:top-right="props">
              <q-btn size="sm" :label="'Nb Produits vendus: '+ numerique(nbre_vendus)" /><br>
              <q-btn size="sm" class="q-ml-sm" :label="'total: '+numerique(montant_vendus)+' FCFA'" />
              <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                     @click="props.toggleFullscreen" class="q-ml-md float-right" />
            </template>
          </q-table>

        </q-dialog>

      </div>

    </div>


    <div class="row justify-center text-center q-pa-md">


      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center q-pa-lg">
          <div class="text-center text-h6">Stats par interval</div>
          <br>
          <div class="grid">
            <div class="row">
              <div class="col-3 q-pa-sm">
                <q-input type="date" v-model="datemin" placeholder="Mois" :dense="true" hint="Mois" />
              </div>
              <div class="col-3 q-pa-sm">
                <q-input type="date" v-model="datemax" placeholder="Mois" :dense="true"  hint="Mois" />
              </div>
              <div class="col-2 q-pa-sm">
                <q-btn label="filtrer" @click="client_stat(datemin, datemax)" />
              </div>
            </div>
          </div>
        </q-card>
      </div>


      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md" style="min-width: 350px">
        <q-card class="my-card" square>
          <q-card-section>
<!--            {{namedata}}-->
            <areachart-component color="primary" type="bar" :horizontal="false" :percent="5"
                                 :categories="namedata" :series="sumdata" title="Montant généré" titletooltip="depense" />
          </q-card-section>
        </q-card>
      </div>

    </div>

  </q-page>

</template>

<script>
import $httpService from '../boot/httpService';
import FactureComponent from '../components/facture_component.vue';
import CountryComponent from '../components/country.vue';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import * as _ from 'lodash';
import AreachartComponent from "components/areachart.vue";
export default {
  name: 'ClientPage',
  data () {
    return {
      selected: [],
      options: [ { id: 1, name: 'personne' }, { id: 2, name: 'compagnie' } ],
      client: { },
      date: false,
      status_update: false,
      post: { body: '', subject: '' },
      entreprise: {},
      datemin: null,
      datemax: null,
      model: null,
      filter: '',
      medium: false,
      medium2: false,
      medium3: false,
      vente_status: false,
      loading: false,
      sales_stats: [],
      appro_stats: [],
      nbre_achetes: 0,
      montant_achetes: 0,
      nbre_vendus: 0,
      montant_vendus: 0,
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
        { name: 'name', align: 'center', label: 'Nom', field: 'name', sortable: true },
        { name: 'last_name', required: true, label: 'Prenom', field: row => row.last_name, format: val => `${val}`, sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'telephone', label: 'Telephone', field: 'telephone', sortable: true },
        { name: 'actions', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      facture_id: null,
      products: [],
      sales_list: [],
      products_list: [],
      sumdata: [],
      namedata: [],
      appro_list: [{ p: { sell_price: 0, id: null, quantity: 1 }, quantity: 1 }],
      product: { description: '' },
      sales_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'quantite_vendu', align: 'center', label: 'Qté', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', label: 'prix_vente', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'montant_vendu', label: 'Montant Vendu', field: 'montant_vendu', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'dateposted', label: 'Date Vente', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      data: [],
      definitions: {
        insert_img: {
          tip: 'Inserer une image', icon: 'photo', handler: this.insertImg
        }
      }
    }
  },
  components: {
    AreachartComponent,
    FactureComponent,
    CountryComponent,
    'downloadExcel': vue3JsonExcel
  },
  mixins: [basemixin],
  created () {
    this.loadData();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    // this.loadData2();
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity), 0);
    }
  },
  methods: {
    client_stat (datemin, datemax) {
      $httpService.postWithParams('/my/get/client_stats', {datemin, datemax})
        .then((response) => {
          console.log(response);
          this.sumdata = [{ name: 'Tarif Généré', data: response['sumvente'] }];
          this.namedata = response['fullname'];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    loadData () {
      $httpService.getWithParams('/my/get/client')
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
        this.user_register();
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
      this.medium = true
      this.client = item;
      this.status_update = true;
    },
    user_register () {
      // console.log(this.client);
      // console.log(this.model);
      $httpService.postWithParams('/my/post/client', this.client)
        .then((response) => {
          // this.options = response.data;
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
    client_update () {
      $httpService.postWithParams('/my/put/client', this.client)
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
    insertImg() { // insertImg method
      const post = this.post
      // create an input file element to open file dialog
      // input.onchange = _ => {

      const input = document.createElement('input')
      input.type = 'file'
      input.accept = '.png, .jpg' // file extensions allowed
      let file
      input.onchange = () => {
        const files = Array.from(input.files)
        file = files[0]
        // lets load the file as dataUrl
        const reader = new FileReader()
        let dataUrl = ''
        reader.onloadend = function() {
          dataUrl = reader.result
          // append result to the body of your post
          post.body += '<div><img src="' + dataUrl + '" /></div>'
        }
        reader.readAsDataURL(file)
      }
      input.click()
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign (index) {
      this.products[index].p.quantity = 1;
      this.products[index].quantity = 1;
    },
    qr_get(dataUrl) {
      this.image = dataUrl;
    },
    send_email () {
      $httpService.postWithParams('/my/send/email', { id: this.client.id, email: this.client.email, subject: this.post.subject, message: this.post.body })
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
        })
        .catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    },
    startDownload() {
      confirm('Voulez-vous generer');
      return false;
    },
    sales_stats_get(clientid) {
      let params = { 'first': '2023-02-31', 'last': '2023-02-28', 'clientid': clientid };
      $httpService.getWithParams('/my/get/sales_client_stats', params)
        .then((response) => {
          this.sales_stats = response;
          this.nbre_vendus = _.sumBy(this.sales_stats, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_stats, 'montant_vendu');
        })
    }
  }

}
</script>

<style>
</style>
