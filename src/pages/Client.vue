<template>

  <q-page>

    <div class="row justify-center text-center">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md justify-left text-left">
        <q-btn label="Ajouter" class="q-ma-sm" size="sm" icon="add" color="secondary" @click="medium = true; client = {contacts: []}" />
      </div>

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md">

        <q-table
id="printMe" title="Treats" :rows="data" :columns="columns" row-key="name"
                 class="q-pa-md q-ma-md" :pagination="pagination" :filter="filter" flat>
          <template #top="props">
            <div class="col-4 q-table__title">Clients</div>
            <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(data, 'vente')"/>
            <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"  class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="last_name" :props="props"> {{props.row.last_name}} </q-td>
              <q-td key="email" :props="props"> {{props.row.email}} </q-td>
              <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
              <q-td key="actions" :props="props">

                <q-btn-dropdown size="xs" color="dark">
                  <q-item v-close-popup clickable>
                    <q-item-label @click="btn_update(props.row)"> Modifier</q-item-label>
                  </q-item>
                  <!--                  <q-item clickable @click="medium2 = true">-->
                  <!--                    <q-item-label>Enoyer un email</q-item-label>-->
                  <!--                  </q-item>-->
                  <!--                  <q-item clickable v-close-popup @click="medium3 = true">-->
                  <!--                    <q-item-label>Envoyer une proforma</q-item-label>-->
                  <!--                  </q-item>-->
                  <q-item v-close-popup clickable @click="vente_status = true; product_id = props.row.id; sales_stats_get(props.row.id);">
                    <q-item-label>Liste des achats</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="fileStatus = true; clientId = props.row.id">
                    <q-item-label>Photos</q-item-label>
                  </q-item>
                </q-btn-dropdown>

              </q-td>
            </q-tr>
          </template>
        </q-table>

        <q-dialog v-model="medium2">
          <div class="row" style="max-width: 100%; width: 800px; background: white">
            <div class="col-12 q-ma-md">
              <q-input v-model="post.subject" label="Sujet"></q-input>
            </div>
            <div class="col-12 q-ma-md">
              <q-editor v-model="post.body" :dense="$q.screen.lt.md" :definitions="definitions" :toolbar="toolbar" />
            </div>
            <div class="col-12 q-ma-md">
              <q-btn size="md" @click="send_email()">Envoyer l'email</q-btn>
            </div>
          </div>
        </q-dialog>

        <q-dialog v-model="medium" position="top">
          <q-card style="width: 700px; max-width: 80vw;">

            <q-card-section>
              <div class="row justify-center">
                <!--      <div class="col-2">1</div>-->
                <div v-if="!status_update" class="text-h6">Ajouter un client</div>
                <div v-if="status_update" class="text-h6">Modifier un client</div>
                <div class="col-12 q-pa-lg">

                  <q-form  class="q-gutter-md" @submit="onSubmit" @reset="onReset"  >
                    <q-select
v-model="client.type" dense outlined filled :options="options" label="Type de client"
                              map-options emit-value option-value="id" option-label="name" />
                    <q-input
v-model="client.name" dense outlined autocomplete label="Nom *"
                             lazy-rules :rules="[ val => val && val.length > 0 || 'Please type something']" />
                    <q-input v-model="client.last_name" dense outlined  autocomplete label="Prenom *" />
                    <q-input v-model="client.telephone_code" dense outlined type="number"  label="indicatif *" />
                    <q-input v-model="client.telephone" dense outlined type="text" label="telephone *" />
                    <q-input v-model="client.email" dense outlined type="email" label="Email *" />
                    <country-component v-model="client.country" code="civ"></country-component>
                    <q-input v-model="client.city" dense outlined type="text" label="Ville" />
                    <q-input v-model="client.address" dense outlined type="textarea" label="Adresse" />
                    <br>
                    <div class="q-gutter-sm">
                      <q-radio v-model="client.exonere" :val="0" label="Pas exonere" color="grey" />
                      <q-radio v-model="client.exonere" :val="1" label="Exonere" color="green" />
                    </div>

                    <div class="row">
                      <div class="col q-pa-sm">
                        <q-btn icon="add" label="Ajouter des contacts" size="sm" color="grey-8" @click="client.contacts.push({})" />
                      </div>
                    </div>
                    <div v-for="(contact, index) in client.contacts" :key="index" class="row">
                      <q-input v-model="contact.nom" class="col-4 q-pa-sm" dense outlined label="Nom"></q-input>
                      <q-input v-model="contact.job" class="col-4 q-pa-sm" dense outlined label="Profession"></q-input>
                      <q-input v-model="contact.tel" class="col-3 q-pa-sm" dense outlined label="Numero"></q-input>
                      <div class="col-1 q-pa-sm">
                        <q-btn label="-" color="red-3" @click="client.contacts.splice(index, 1)" />
                      </div>
                    </div>

                    <div>
                      <br>
                      <br>
                      <q-btn v-if="status_update" label="Modifier" type="button" color="primary" @click="client_update()"/>
                      <q-btn v-if="!status_update" label="Valider"  type="submit" color="primary"/>
                    </div>
                  </q-form>

                </div>
                <!--      <div class="col-2">1</div>-->
              </div>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-dark">
              <q-btn v-close-popup flat label="Fermer" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <!--        <q-dialog v-model="medium3">-->
        <!--          <facture-component style="width: 1000px; max-width: 100%;"></facture-component>-->
        <!--        </q-dialog>-->

        <q-dialog v-model="vente_status" transition-show="slide-up" transition-hide="slide-down">

          <q-table
:rows="sales_stats" :columns="sales_columns" style="width: 800px; max-width: 100%"
                   row-key="id" :pagination="pagination">
            <template #top>
              <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
            </template>
            <template #top-left>
              <div class="row">
                <div class="col-5 "><q-input v-model="first"  type="date" label="debut" /></div>
                <div class="col-5"><q-input v-model="last"  type="date" label="fin" /></div>
                <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" @click="sales_stats_get(product_id)"/></div>
              </div>
            </template>
            <template #top-right="props">
              <q-btn size="sm" :label="'Nb Produits vendus: '+ numerique(nbre_vendus)" /><br>
              <q-btn size="sm" class="q-ml-sm" :label="'total: '+numerique(montant_vendus)+' FCFA'" />
              <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                     class="q-ml-md float-right" @click="props.toggleFullscreen" />
            </template>
          </q-table>

        </q-dialog>


        <q-dialog v-model="fileStatus">
          <q-card style="width: 600px" class="q-pa-lg">
            <filescomponent type="client" :typeid="clientId" folder="client" />
          </q-card>
        </q-dialog>

      </div>

    </div>


    <div class="row justify-center text-center q-pa-md">


      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center q-pa-lg" flat square>
          <div class="text-center text-h6">Stats par interval</div>
          <br>
          <div class="grid">
            <div class="row">
              <div class="col-3 q-pa-sm">
                <q-input v-model="datemin" type="date" placeholder="Mois" :dense="true" hint="Mois" />
              </div>
              <div class="col-3 q-pa-sm">
                <q-input v-model="datemax" type="date" placeholder="Mois" :dense="true"  hint="Mois" />
              </div>
              <div class="col-2 q-pa-sm">
                <q-btn label="filtrer" @click="client_stat(datemin, datemax)" />
              </div>
            </div>
          </div>
        </q-card>
      </div>


      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md" style="min-width: 350px">
        <q-card class="my-card" square flat>
          <q-card-section>
            <!--            {{namedata}}-->
            <areachart-component
color="primary" type="bar" :horizontal="false" :percent="5"
                                 :categories="namedata" :series="sumdata" title="Montant généré" titletooltip="depense" />
          </q-card-section>
        </q-card>
      </div>

    </div>

  </q-page>

</template>

<script lang="ts">
import $httpService from '../boot/httpService';
import CountryComponent from '../components/country.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
import AreachartComponent from "components/areachart.vue";
import Filescomponent from "components/filescomponent.vue";
export default {
  name: 'ClientPage',
  components: {
    Filescomponent,
    AreachartComponent,
    CountryComponent,
  },
  mixins: [basemixin],
  data () {
    return {
      selected: [],
      options: [ { id: 1, name: 'personne' }, { id: 2, name: 'compagnie' } ],
      client: { exonere: 0},
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
      fileStatus: false,
      loading: false,
      sales_stats: [],
      appro_stats: [],
      clientId: 0,
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
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity), 0);
    }
  },
  created () {
    this.loadData();
    // var date = new Date();
    // this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    // this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    // this.loadData2();
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
      input.accept = '.png, .jpg'
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
