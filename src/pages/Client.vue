<template>

  <q-page padding>

    <div class="row justify-center">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md">

        <q-table
          id="printMe" title="Treats" :rows="data" :columns="columns" row-key="name"
          :pagination="pagination" :filter="filter" flat>
          <template #top="props">
            <div class="col-4 q-table__title">Liste des clients</div>
            <div class="col-4">
              <q-input v-model="filter" dense debounce="300" placeholder="Rechercher" />
            </div>
            <div class="col-4 text-right">
              <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"  class="q-mr-md" @click="props.toggleFullscreen" />
              <q-btn @click="json2csv(data, 'clients')" round icon="far fa-file-excel" size="sm" title="excel" class="q-ma-sm" color="secondary"  />
              <q-btn v-print="'#printMe'" round icon="print" size="sm" class="q-ma-sm" color="secondary" />
              <q-btn @click="medium = true; client = {contacts: []}" id="add" round class="q-ma-sm" size="sm" icon="add" color="secondary" />
            </div>
            <br>
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
              <q-editor v-model="post.body" :dense="$q.screen.lt.md" :toolbar="toolbar" />
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

                  <q-form  class="q-gutter-md" @submit="onSubmit">
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
                <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" @click="sales_stats_get()"/></div>
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


      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md">
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


      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md" style="min-width: 350px">
        <q-card class="my-card" square flat>
          <q-card-section>
            <areachart-component
              color="primary" type="bar" :horizontal="false" :percent="5"
              :categories="namedata" :series="sumdata" title="Montant généré" titletooltip="depense" />
          </q-card-section>
        </q-card>
      </div>

    </div>




  </q-page>

</template>

<script>
import CountryComponent from '../components/country.vue';
import basemixin from './basemixin';
import * as _ from 'lodash';
import AreachartComponent from "components/areachart.vue";
import Filescomponent from "components/filescomponent.vue";
import {ClientApi, SalesApi} from "src/services/Repository";
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
      options: [ { id: 1, name: 'personne' }, { id: 2, name: 'compagnie' } ],
      client: { exonere: 0},
      status_update: false,
      post: { body: '', subject: '' },
      datemin: null,
      datemax: null,
      filter: '',
      medium: false,
      medium2: false,
      vente_status: false,
      fileStatus: false,
      sales_stats: [],
      clientId: 0,
      nbre_vendus: 0,
      montant_vendus: 0,
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
      products: [],
      sumdata: [],
      namedata: [],
      sales_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'quantite_vendu', align: 'center', label: 'Qté', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', label: 'prix_vente', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'montant_vendu', label: 'Montant Vendu', field: 'montant_vendu', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'dateposted', label: 'Date Vente', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      data: []
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity), 0);
    }
  },
  created () {
    this.loadData();
  },
  methods: {
    async client_stat (datemin, datemax) {
      const response = await ClientApi.stats(datemin, datemax);
      this.sumdata = [{ name: 'Tarif Généré', data: response['sumvente'] }];
      this.namedata = response['fullname'];
    },
    async loadData () {
      this.data = await ClientApi.get();
      this.store.setClients(this.data);
    },
    onSubmit () {
      if (this.accept !== true) {
        this.user_register();
      }
    },
    btn_update(item) {
      this.medium = true
      this.client = item;
      this.status_update = true;
    },
    async user_register () {
      await ClientApi.create(this.client)
      await this.loadData();
    },
    async client_update () {
      await ClientApi.update(this.client)
      await this.loadData();
    },
    async sales_stats_get(clientid) {
      let params = { 'first': this.first, 'last': this.last, 'clientid': clientid };
      this.sales_stats = await SalesApi.clientSalesStats(params);
      this.nbre_vendus = _.sumBy(this.sales_stats, 'quantite_vendu');
      this.montant_vendus = _.sumBy(this.sales_stats, 'montant_vendu');
    },
    send_email () {
      this.sendMail({ id: this.client.id, email: this.client.email, subject: this.post.subject, message: this.post.body })
    },
  }

}
</script>

<style>
</style>
