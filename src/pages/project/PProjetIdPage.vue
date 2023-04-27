
<template>
  <q-page padding>

    <div class="row">
      <div class="col-9 q-pa-lg">
        <span class="text-h6">Projets</span>
      </div>
      <!--      <div class="col q-pa-lg float-right text-right">-->
      <!--        <q-btn color="primary" size="sm">Créer</q-btn>-->
      <!--      </div>-->
    </div>

    <div class="row" v-if="p_projet.id">
      <div class="col-12 q-pa-lg">

        <q-card class="q-pa-lg q-mb-xs">
          <div class="row">
            <div class="col-3">
              <q-card class="q-pa-lg bg-grey-3 text-center">
                <br>
                <h5><b>{{p_projet.titre}}</b></h5>
                <br>
              </q-card>
            </div>
            <div class="col-7 q-pa-lg">
              <span class="text-h5">
                {{p_tasks.length}}
                <q-badge outline color="green" label="Taches" />
              </span>
              <!--              <p class="text-h6 text-grey">Projets courants</p>-->
              <p class="text-h6 text-grey">{{p_projet.description}}</p>
              <br>
              <div class="row">
                <div class="col-3 q-pa-sm q-mr-sm" style="border: 1px #e3e3e3 dashed">
                  <!--                  <span class="text-weight-bold">20 Déc 2023</span><br>-->
                  <span class="text-weight-bold">{{p_projet.datedebut}}</span><br>
                  <span>Début</span>
                </div>
                <div class="col-3 q-pa-sm q-ml-sm" style="border: 1px #e3e3e3 dashed">
                  <span class="text-weight-bold">{{p_projet.datefin}}</span><br>
                  <span>Fin</span>
                </div>
                <div class="col-3 q-pa-sm q-ml-sm" style="border: 1px #e3e3e3 dashed">
                  <span class="text-weight-bold">{{numerique(p_projet.cout)}}</span><br>
                  <span>Budget</span>
                </div>
              </div>
            </div>
            <div class="col-2 q-pa-lg">
              <span>Tests</span>
            </div>
            <div class="col-12">
              <q-separator class="no-margin no-padding" color="grey-2" inset />
              <br>
            </div>
            <div class="col-12 no-padding no-margin">
              <q-card flat>
                <q-tabs
                  v-model="tab"
                  dense
                  class="text-grey"
                  active-color="primary"
                  indicator-color="primary"
                  align="left"
                >
                  <q-tab name="mails" label="Résumé" />
                  <q-tab name="taches" label="Taches" />
                  <q-tab name="activites" label="Matières Utilisées" />
                  <q-tab name="fichiers" label="Fichiers" @click="fileStatus=!fileStatus" />
                  <q-tab name="previsions" label="Prévisions" />
                  <q-tab name="depenses" label="Dépenses" />
                  <q-tab name="facture" label="Factures" />
                  <q-tab name="versement" label="Versements" />
                </q-tabs>
                <q-separator />
              </q-card>
            </div>
          </div>
        </q-card>

        <q-tab-panels v-model="tab" animated style="background-color: transparent">

          <q-tab-panel name="mails" class="no-margin no-padding">
            <br>
            <div class="row">

              <div class="col-6 q-mt-md">
                <q-card class="q-mr-md q-pa-lg">
                  <span class="text-h5">
                    Dernières tâches
                  </span>
                  <p class="text-h6 text-grey">Liste des taches courantes</p>
                  <!--                  <list-item />-->
                  <q-list bordered padding class="rounded-borders">
                    <q-item clickable v-ripple v-for="task in p_tasks.slice(0, 5)">
                      <q-item-section avatar top>
                        <q-avatar icon="folder" color="primary" text-color="white" />
                      </q-item-section>

                      <q-item-section>
                        <q-item-label lines="1">{{task.libelle}}</q-item-label>
                        <q-item-label caption>{{task.debut}} au {{task.fin}}</q-item-label>
                      </q-item-section>

                      <q-item-section side>
                        <q-icon name="info" color="green" />
                      </q-item-section>
                    </q-item>
                  </q-list>
                </q-card>
              </div>

              <div class="col-6 q-mt-md">
                <q-card class="q-ml-md q-pa-lg">
                  <span class="text-h5">
                    Utilisateurs
                  </span>
                  <p class="text-h6 text-grey">liste des travailleurs affectés</p>
                  <q-list bordered padding class="rounded-borders">
                    <q-item clickable v-ripple v-for="employe in employes.slice(0, 5)">
                      <q-item-section avatar top>
                        <q-avatar icon="folder" color="primary" text-color="white" />
                      </q-item-section>

                      <q-item-section>
                        <q-item-label lines="1">{{employe.nom}} {{employe.prenom}}</q-item-label>
                        <q-item-label caption>{{employe.fonction}}</q-item-label>
                      </q-item-section>

                      <q-item-section side>
                        <q-icon name="info" color="green" />
                      </q-item-section>
                    </q-item>
                  </q-list>
                  <!--                  <list-item />-->
                </q-card>
              </div>

              <div class="col-6 q-mt-md">
                <q-card class="q-mr-md q-pa-lg">
                  <span class="text-h5">
                    Résumé des tâches
                  </span>
                  <p class="text-h6 text-grey">Projets courants</p>
                  <div class="row">

                    <div class="col-6">
                      <q-circular-progress
                        :value="40"
                        size="250px"
                        :thickness="1"
                        color="grey"
                        track-color="primary"
                        class="q-pa-md"
                      />
                    </div>

                    <div class="col-6 q-pa-md">
                      <q-list dense>
                        <q-item clickable v-ripple>
                          <q-item-label><span class="text-grey text-weight-bold">-</span> En cours</q-item-label>
                        </q-item>
                        <q-item clickable v-ripple>
                          <q-item-label><span class="text-green text-weight-bold">-</span>Terminé(s)</q-item-label>
                        </q-item>
                        <q-item clickable v-ripple>
                          <q-item-label><span class="text-red text-weight-bold">-</span>Echec(s)</q-item-label>
                        </q-item>
                        <q-item clickable v-ripple>
                          <q-item-label><span class="text-red text-weight-bold">-</span>Arrêté(s)</q-item-label>
                        </q-item>
                      </q-list>
                    </div>

                    <div class="col-12">
                      <br>
                      <br>
                    </div>
                  </div>
                </q-card>
              </div>


            </div>

          </q-tab-panel>

          <q-tab-panel name="taches" class="no-padding no-margin">
            <q-card class="q-pa-lg q-mt-lg">
              <task-list :employes="employes" :tasks="p_tasks" :update="myupdate"/>
            </q-card>
          </q-tab-panel>

          <q-tab-panel name="activites" class="no-padding no-margin">
            <br>
            <div class="row">
              <div class="col-12 q-mb-md">
                <q-card class="q-pa-lg">
                  <br>
                  <div class="text-h6">Matières utilisées</div>
                  <br>

                  <div class="row" v-for="use in used">
                    <div class="col-4 q-pa-sm">
                      <q-select
                        v-model="use.product_id"
                        option-label="name"
                        option-value="id"
                        map-options emit-value :options="products" label="produits" />
                    </div>
                    <!--                    <div class="col-2 q-pa-sm">-->
                    <!--                      <q-input :model-value="100" type="number" label="quantité restante" />-->
                    <!--                    </div>-->
                    <div class="col-2 q-pa-sm">
                      <q-input  type="number" label="quantité utilisé" v-model="use.quantite" />
                    </div>
                    <div class="col-3 q-pa-sm">
                      <br>
                      <q-btn icon="add" size="sm" outline color="green"
                             v-if="!use.id" @click="p_projet_stock_post(use)" title="ajouter" />
                      &nbsp;
                      <q-btn icon="edit" size="sm" v-if="use.id" outline title="modifier" />
                      &nbsp;
                      <q-btn icon="delete" size="sm" v-if="use.id" color="red" outline title="supprimer" />
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-4 q-pa-sm">
                      <q-btn label="+" color="secondary" @click="used.push({})" />
                    </div>
                  </div>

                </q-card>
              </div>
            </div>
          </q-tab-panel>

          <q-tab-panel name="previsions" class="no-padding no-margin">
            <br>
            <div class="row">
              <div class="col-12 q-mb-md">
                <q-card class="q-pa-lg">
                  <h6>Prévisions</h6>
                </q-card>
              </div>
            </div>
          </q-tab-panel>

          <q-tab-panel name="parametres" class="no-padding no-margin">
            <br>
            <div class="row">
              <div class="col-12 q-mb-md">
                <q-card class="q-pa-lg">
                  <h6>Paramètres</h6>
                </q-card>
              </div>
            </div>
          </q-tab-panel>

        </q-tab-panels>
        <!--        </q-card>-->

      </div>
    </div>


    <q-dialog v-model="fileStatus">
      <q-card style="width: 600px" class="q-pa-lg">
        <filescomponent type="projet" :typeid="$route.params.id" folder="projet" />
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import apimixin from "src/services/apimixin";
import ListItem from "components/listItem.vue";
import TaskList from "components/taskList.vue";
import {employeGetService, p_task_get} from "src/services/api/rh.api";
import UploadFormData from "components/uploadFormData.vue";
import Filescomponent from "components/filescomponent.vue";

export default {
  components: {UploadFormData, TaskList, ListItem, Filescomponent},
  data () {
    return {
      tab: "mails",
      p_projet_id: 1,
      loading1: false,
      fileStatus: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_projet: {},
      p_projets: [],
      p_tasks: [],
      employes: [],
      products: [],
      used: [],
      product: null,
      columns: [
        { name: 'titre', align: 'left', label: 'titre', field: 'titre', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'status', align: 'left', label: 'status', field: 'status', sortable: true },
        { name: 'objectif', align: 'left', label: 'objectif', field: 'objectif', sortable: true },
        { name: 'progress', align: 'left', label: 'progress', field: 'progress', sortable: true },
        { name: 'datedebut', align: 'left', label: 'datedebut', field: 'datedebut', sortable: true },
        { name: 'datefin', align: 'left', label: 'datefin', field: 'datefin', sortable: true },
        { name: 'createdby', align: 'left', label: 'createdby', field: 'createdby', sortable: true },
        { name: 'priorite', align: 'left', label: 'priorite', field: 'priorite', sortable: true },
        { name: 'cout', align: 'left', label: 'cout', field: 'cout', sortable: true },
        { name: 'clientid', align: 'left', label: 'clientid', field: 'clientid', sortable: true },
        { name: 'porteur', align: 'left', label: 'porteur', field: 'porteur', sortable: true },
        { name: 'execucants', align: 'left', label: 'execucants', field: 'execucants', sortable: true },
        { name: 'puuid', align: 'left', label: 'puuid', field: 'puuid', sortable: true },
        { name: 'photo', align: 'left', label: 'photo', field: 'photo', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  mixins: [basemixin, apimixin],
  created () {
    // console.log(this.$route.params, this.$route)
    this.p_projet_get()
    this.p_projet_stock_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
    this.getTaskList()
    this.employesGet()
    this.productsGet()
  },
  methods: {
    update_get (props) {
      this.p_projet = props
      this.medium2 = true
    },
    setEvent (payload, _name) {
      this.p_projet[_name] = payload
    },
    handleFile (_name) {
      this.p_projet[_name] = this.$refs[_name].files[0]
    },
    productsGet () {
      $httpService.getApi('/my/get/products')
        .then((response) => {
          this.products = response
        })
    },
    p_projet_get () {
      $httpService.getApi('/my/get/p_projet/'+this.$route.params.id)
        .then((response) => {
          this.p_projet = response
        })
    },

    p_projet_stock_get () {
      $httpService.getApi('/my/get/projet_stock/'+this.$route.params.id)
        .then((response) => {
          this.used = response
        })
    },
    p_projet_stock_post (item) {
      item.annee = 2023
      item.project_id = this.$route.params.id
      item.p_project_id = this.$route.params.id
      this.showLoading()
      $httpService.postWithParams('/my/post/projet_stock', item)
        .then((response) => {
          this.p_projet_stock_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_projet_update () {
      this.showLoading()
      $httpService.putWithParams('/api/put/p_projet', this.p_projet)
        .then((response) => {
          this.p_projet_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    getTaskList () {
      p_task_get().then((response) => {
        this.p_tasks = response
      });
    },
    employesGet () {
      employeGetService().then((response) => {
        console.log(response)
        this.employes = response
      });
    },
    myupdate(a, b) {
      // console.log('Hello update')
      // console.log(a)
      // console.log(b)
      // return a
    },
    uploaded(data) {
      console.log(data)
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
