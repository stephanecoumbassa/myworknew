<template>

  <div class="row justify-center text-center">

    <div class="col-md-11 col-12 q-pa-xs q-ma-xs">
      <!--grid-->

      <q-table
title="Treats" :rows="data" :columns="columns" row-key="name" class="q-pa-md q-ma-md"
               :pagination="pagination" :filter="filter" flat>
        <template #top="props">
          <div class="col-4 q-table__title">
            <q-btn label="Ajouter" class="q-ma-md" size="sm" icon="add" color="secondary" @click="fournisseur = {} ; medium = true" />
            Fournisseurs</div>
          <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
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

              <q-btn-dropdown size="xs" color="dark" label="">
                <q-list>
                  <q-item v-close-popup clickable>
                    <q-item-section>
                      <q-item-label @click="btn_update(props.row) ; medium = true"> Modifier</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium2 = true">
                    <q-item-section>
                      <q-item-label>Enoyer un email</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-section>
                      <q-item-label>Envoyer une proforma</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item v-close-popup clickable  @click="appro_status = true; product_id = props.row.id; appro_stats_get(props.row.id);">
                    <q-item-section>
                      <q-item-label>Liste des commandes</q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-btn-dropdown>
            </q-td>
          </q-tr>
        </template>
      </q-table>

      <q-dialog v-model="medium" position="top">
        <q-card style="width: 700px; max-width: 80vw;">
          <q-card-section>
            <div v-if="!status_update" class="text-h6">Ajouter un fournisseur</div>
            <div v-if="status_update" class="text-h6">Modifier un fournisseur</div>
          </q-card-section>

          <q-card-section>
            <div class="row justify-center">
              <div class="col-10">

                <q-form  class="q-gutter-md" @submit="onSubmit" @reset="onReset">
                  <q-select
v-model="fournisseur.type" filled :options="options" label="Type de fournisseur"
                            map-options emit-value option-value="id" option-label="name" />
                  <q-input
v-model="fournisseur.name" autocomplete label="Nom *"
                           lazy-rules :rules="[ val => val && val.length > 0 || 'Please type something']" />
                  <q-input v-model="fournisseur.last_name"  autocomplete label="Prenom *" />
                  <q-input v-model="fournisseur.telephone_code" type="number"  label="indicatif *" />
                  <q-input v-model="fournisseur.telephone" type="text"  label="telephone *" />
                  <q-input v-model="fournisseur.email" type="email"  label="Email *" />
                  <country-component v-model="fournisseur.country" code="mli"></country-component>
                  <q-input v-model="fournisseur.city" type="text" label="Ville" />
                  <q-input v-model="fournisseur.address" type="textarea" label="Adresse" />
                  <div>
                    <br>
                    <q-btn v-if="status_update" label="Modifier" type="button" color="secondary" @click="fournisseur_update()"/>
                    <q-btn v-if="!status_update" label="Valider"  type="submit" color="secondary"/>
                  </div>
                </q-form>

              </div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn v-close-popup flat label="Fermer" />
          </q-card-actions>
        </q-card>
      </q-dialog>

      <q-dialog v-model="medium2">
        <div class="row" style="max-width: 100%; width: 800px; background: white">
          <div class="col-12 q-ma-md">
            <q-input v-model="post.subject" label="Sujet"></q-input>
          </div>
          <div class="col-12 q-ma-md">
            <q-editor v-model="post.body" :dense="$q.screen.lt.md" :toolbar="toolbar" :fonts="fonts" />
          </div>
          <div class="col-12 q-ma-md">
            <q-btn size="md" @click="send_email()">Envoyer l'email</q-btn>
          </div>
        </div>
      </q-dialog>

      <q-dialog v-model="medium3">
        <facture-component style="width: 1000px; max-width: 100%;"></facture-component>
      </q-dialog>

      <q-dialog v-model="appro_status" position="top" transition-show="slide-up" transition-hide="slide-down">
        <q-table
:data="appro_stats" :columns="appro_columns" style="width: 1000px; max-width: 100%"
                 row-key="id" :pagination="pagination">
          <template #top-left>
            <div class="row">
              <div class="col-5 "><q-input v-model="first"  type="date" label="debut" /></div>
              <div class="col-5"><q-input v-model="last"  type="date" label="fin" /></div>
              <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" @click="appro_stats_get(product_id)" /></div>
            </div>
          </template>
          <template #top-right="props">
            <q-btn size="sm" :label="'Nbre de produits achetes: '+ numerique(nbre_achetes)" /><br>
            <q-btn size="sm" class="q-ml-sm" :label="'Montant total: '+numerique(montant_achetes)+' FCFA'" />
            <download-excel name="vente.xls" :data="appro_columns">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md float-right" @click="props.toggleFullscreen" />
          </template>
        </q-table>
      </q-dialog>

    </div>

  </div>

</template>

<script>
import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import FactureComponent from '../components/facture_component.vue';
import CountryComponent from '../components/country.vue';
import * as _ from 'lodash';
export default {
  name: 'FournisseurPage',
  components: {
    'downloadExcel': vue3JsonExcel,
    FactureComponent,
    CountryComponent
  },
  mixins: [basemixin],
  data () {
    return {
      options: [ { id: 1, name: 'personne' }, { id: 2, name: 'compagnie' } ],
      fournisseur: { },
      status_update: false,
      post: {},
      filter: '',
      medium: false,
      medium2: false,
      medium3: false,
      appro_status: false,
      appro_stats: [],
      nbre_achetes: 0,
      montant_achetes: 0,
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
        // { name: 'type', label: 'Type', field: 'type', sortable: true }
      ],
      appro_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'amount', label: 'Quantite', field: 'amount', align: 'left', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'p_buying_price', align: 'center', label: 'Prix Achat', field: 'p_buying_price', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'p_sell_price', label: 'Prix Vente', align: 'left', field: 'p_sell_price', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'dateposted', label: 'Date Achat', align: 'left', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      data: []
    }
  },
  watch: {
  },
  created () {
    this.loadData();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    // this.loadData2();
  },
  methods: {
    loadData () {
      $httpService.getWithParams('/my/get/fournisseur')
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
      this.fournisseur = item;
      this.status_update = true;
    },
    user_register () {
      $httpService.postWithParams('/my/post/fournisseur', this.fournisseur)
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
    fournisseur_update () {
      $httpService.postWithParams('/my/put/fournisseur', this.fournisseur)
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
    appro_stats_get(pid) {
      let params = { 'first': this.first, 'last': this.last, 'fournisseurid': pid };
      $httpService.getWithParams('/my/get/appro_fournisseur_stats', params)
        .then((response) => {
          this.appro_stats = response;
          this.nbre_achetes = _.sumBy(this.appro_stats, 'amount');
          this.montant_achetes = _.sumBy(this.appro_stats, 'buying_price');
        })
    }
  }

}
</script>

<style>
</style>
