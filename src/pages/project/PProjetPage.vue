
<template>
  <q-page padding>

    <div class="row">
      <div class="col-9 q-pa-lg">
        <span class="text-h6">Projets</span>
      </div>
      <div class="col q-pa-lg float-right text-right">
        <q-btn
color="primary" icon="add" size="sm"
               @click="projectModal=true; p_projet={status: 'ENCOURS'}">Créer</q-btn>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-6 q-pa-lg">
        <q-card class="q-pa-md" flat>
          <span class="text-h5">237</span>
          <p class="text-h6 text-grey">Projets courants</p>
          <div class="row">
            <div class="col-6 ">
              <q-circular-progress
                :value="40"
                size="150px"
                :thickness="1"
                color="grey"
                track-color="primary"
                class="q-pa-md"
              />
            </div>
            <div class="col-6 q-pa-md">
              <q-list dense>
                <q-item v-ripple clickable>
                  <q-item-label><span class="text-grey text-weight-bold">-</span> ({{stats.cours}}) En cours</q-item-label>
                </q-item>
                <q-item v-ripple clickable>
                    <q-item-label><span class="text-green text-weight-bold">-</span> ({{stats.termine}}) Terminé(s)</q-item-label>
                </q-item>
                <q-item v-ripple clickable>
                    <q-item-label><span class="text-red text-weight-bold">-</span> ({{stats.attente}}) Attente(s)</q-item-label>
                </q-item>
              </q-list>
            </div>
          </div>
        </q-card>
      </div>
      <div class="col-md-4 col-6 q-pa-lg">
        <q-card class="q-pa-md" flat>
          <span class="text-h5">{{numerique(array_somme(p_projections, 'montant_ht'))}} CFA</span>
          <p class="text-h6 text-grey">Projets finance</p>
          <q-list bordered>
            <q-item v-for="(projection, index) in p_projections.slice(0, 3)" :key="index" class="q-pa-md" dense border>
              <q-item-section>
                <q-item-label>{{ projection.titre }}</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-item-label caption>{{ numerique(projection.montant_ht) }} CFA</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </q-card>
      </div>
      <div class="col-md-4 col-6 q-pa-lg">
        <q-card class="q-pa-md" flat>
          <span class="text-h5">49</span>
          <p class="text-h6 text-grey">Clients</p>
          <div class="row" style="height: 145px;">
            {{clients.length}}
            <q-avatar
              v-for="(n, index) in clients"
              :key="n.id"
              size="40px"
              class="overlapping"
              :style="`left: ${index * 25}px`"
            >
              <img :src="`https://cdn.quasar.dev/img/avatar${index + 1}.jpg`">
            </q-avatar>
          </div>
        </q-card>
      </div>
    </div>

<!--    <div class="row">-->
<!--      <div class="col-12 q-pa-lg">-->
<!--        <span class="text-h6">Projets</span>-->
<!--      </div>-->
<!--    </div>-->

    <div class="row justify-center">
      <div class="col-12 q-pa-lg">
        <q-table
title="p_projets" :rows="p_projets" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name" flat>
          <template #top="props">
            <div class="col-7 q-table__title">Liste des projets</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='client' :props='props'> {{props.row.client}} </q-td>
              <q-td key='titre' :props='props'> {{props.row.titre}} </q-td>
              <q-td key='description' :props='props'> {{props.row.description}} </q-td>
              <q-td key='status' :props='props'>
                <q-btn outline color="grey" size="sm">{{props.row.status}} </q-btn>
              </q-td>
              <q-td key='ponctualite' :props='props'>
                <q-btn v-if="props.row.ponctualite === 'RETARD'" outline color="red" size="sm">{{props.row.ponctualite}} </q-btn>
                <q-btn v-if="props.row.ponctualite === 'OK'" outline color="green" size="sm">{{props.row.ponctualite}} </q-btn>
              </q-td>
              <q-td key='qte' :props='props'> {{props.row.qte}} </q-td>
              <q-td key='prix_unitaire' :props='props'> {{props.row.prix_unitaire}} </q-td>
              <q-td key='montant_ht' :props='props'> {{props.row.montant_ht}} </q-td>
<!--              <q-td key='objectif' :props='props'> {{props.row.objectif}} </q-td>-->
              <q-td key='progress' :props='props'> {{props.row.progress}} </q-td>
              <q-td key='datedebut' :props='props'> {{props.row.datedebut}} </q-td>
              <q-td key='datefin' :props='props'> {{props.row.datefin}} </q-td>
              <q-td key='createdby' :props='props'> {{props.row.createdby}} </q-td>
              <q-td key='priorite' :props='props'> {{props.row.priorite}} </q-td>
              <q-td key='cout' :props='props'> {{props.row.cout}} </q-td>
              <q-td key='clientid' :props='props'> {{props.row.clientid}} </q-td>
              <q-td key='execucants' :props='props'> {{props.row.execucants}} </q-td>
              <q-td key='puuid' :props='props'> {{props.row.puuid}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" outline size="xs" color="dark" icon="visibility" @click="navigate('/projet/'+props.row.id)" />
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)" />
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_projet_delete(props.row.id)" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>


    <q-dialog v-model="projectModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un projet</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-select
v-model="p_projet.clientid" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                          :dense="true" :options="clients" label="Clients" option-value="id" option-label="fullname"
                          input-debounce="0" :rules="[val => !!val || 'Ce champs est requis']" />
                <q-select
v-model='p_projet.status'
                          :options="['ENATTENTE', 'ENCOURS', 'TERMINE','STOPPE']" filled outlined class="q-mb-sm" dense label='status' />
                <q-input v-model='p_projet.titre' outlined class="q-mb-sm" dense label='titre' />
                <q-input v-model='p_projet.description' outlined class="q-mb-sm" dense type='textarea' label='description' />
<!--                <q-input outlined class="q-mb-sm" dense v-model='p_projet.status' label='status' />-->
<!--                <q-input outlined class="q-mb-sm" dense v-model='p_projet.objectif' label='objectif' />-->
                <q-input v-model='p_projet.progress' outlined class="q-mb-sm" dense type='number' label='progression' />
                <q-input v-model='p_projet.datedebut' stack-label outlined class="q-mb-sm" dense type='date' label='datedebut' />
                <q-input v-model='p_projet.datefin' stack-label outlined class="q-mb-sm" dense type='date' label='datefin' />
                <q-input v-model='p_projet.datelivraison' stack-label outlined class="q-mb-sm" dense type='date' label='livraison' />
                <q-input
v-model='p_projet.priorite' outlined class="q-mb-sm" dense label='priorite'
                type="number" min="0" max="5" />
                <q-input v-model='p_projet.cout' outlined class="q-mb-sm" dense type='number' label='cout' />
                <q-select
v-model="p_projet.productid" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                          :dense="true" :options="products" label="Produits" option-value="id" option-label="name"
                          input-debounce="0" :rules="[val => !!val || 'Ce champs est requis']" />
                <q-input v-model='p_projet.qte' outlined class="q-mb-sm" dense type='number' label='Quantité' />
                <q-input v-model='p_projet.prix_unitaire' outlined class="q-mb-sm" dense type='number' label='Prix unitaire' />
                <q-input
outlined class="q-mb-sm" dense type='number'
                         :model-value="p_projet.qte * p_projet.prix_unitaire" label='Montant' />

<!--                <q-input outlined class="q-mb-sm" dense type='number' v-model='p_projet.clientid' label='clientid' />-->
<!--                <q-input outlined class="q-mb-sm" dense type='number' v-model='p_projet.porteur' label='porteur' />-->
<!--                <q-input outlined class="q-mb-sm" dense v-model='p_projet.puuid' label='puuid' />-->
<!--                <upload v-model='p_projet.photo' title='photo' @blurevent="setEvent($event, 'photo')" />-->
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <q-btn color="primary" label="Valider" type="submit" />
              </div>
            </div>
          </q-form>
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn v-close-popup flat label="Fermer" />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import apimixin from "src/services/apimixin";
import {ClientApi} from "src/services/api/client.api";
import {PProjetApi} from "src/services/api/PProjetApi";
export default {
  mixins: [basemixin, apimixin],
  data () {
    return {
      projectModal: false,
      medium2: false,
      stats: {},
      p_projet: {},
      p_projets: [],
      p_projections: [],
      clients: [],
      products: [],
      columns: [
        { name: 'client', align: 'left', label: 'Client', field: 'client', sortable: true },
        { name: 'titre', align: 'left', label: 'titre', field: 'titre', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'status', align: 'left', label: 'status', field: 'status', sortable: true },
        { name: 'ponctualite', align: 'left', label: 'Ponctualite', field: 'ponctualite', sortable: true },
        { name: 'qte', align: 'left', label: 'Qté', field: 'qte', sortable: true },
        { name: 'prix_unitaire', align: 'left', label: 'P unit.', field: 'prix_unitaire', sortable: true },
        { name: 'montant_ht', align: 'left', label: 'HT', field: 'montant_ht', sortable: true },
        // { name: 'objectif', align: 'left', label: 'objectif', field: 'objectif', sortable: true },
        { name: 'progress', align: 'left', label: 'progress', field: 'progress', sortable: true },
        { name: 'datedebut', align: 'left', label: 'datedebut', field: 'datedebut', sortable: true },
        { name: 'datefin', align: 'left', label: 'datefin', field: 'datefin', sortable: true },
        { name: 'createdby', align: 'left', label: 'createdby', field: 'createdby', sortable: true },
        { name: 'priorite', align: 'left', label: 'priorite', field: 'priorite', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.products_get()
    this.p_projet_stats()
    const date = new Date()
    ClientApi.get().then((res) => this.clients = res)
    this.p_projet_previson_get('2023-03')
    PProjetApi.get().then((res) => this.p_projets = res)
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
        })
    },
    p_projet_previson_get (date='2023-03') {
      let year = date.split('-')[0];
      this.year = date.split('-')[0];
      let month = date.split('-')[1];
      this.month = date.split('-')[1];
      $httpService.getApi('/my/get/p_projet_previson?mois='+month+'&annee='+year)
        .then((response) => {
          this.p_projections = response['data'];
          this.$q.notify(response['msg']);
        })
    },
    update_get (props) {
      this.p_projet = props
      this.medium2 = true
    },
    p_projet_stats () {
      $httpService.getApi('/my/stats/p_projet')
        .then((response) => {
          this.stats = response
        })
    },
    onSubmit () {
      if (this.p_projet.id) {
        this.p_projet_update()
      } else {
        this.p_projet_post()
      }
    },
    p_projet_post () {
      this.p_projet.montant_ht = this.p_projet.qte * this.p_projet.prix_unitaire
      this.p_projet.puuid = this.generateUuid();
      this.p_projet.createdby = this.$q.localStorage.getItem('userid');
      this.showLoading()
      $httpService.postWithParams('/my/post/p_projet', this.p_projet)
        .then((response) => {
          this.p_projet = {}
          this.p_projet_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
          this.projectModal = false
        }).catch(() => { this.hideLoading() })
    },
    p_projet_update () {
      this.p_projet.montant_ht = this.p_projet.qte * this.p_projet.prix_unitaire
      this.showLoading()
      $httpService.putWithParams('/my/put/p_projet', this.p_projet)
        .then((response) => {
          this.p_projet_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_projet_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/my/delete/p_projet/' + _id)
        .then((response) => {
          this.p_projet_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>

<style scoped>
.overlapping {
  border: 2px solid white;
  position: absolute;
}
</style>
