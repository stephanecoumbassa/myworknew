
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

    <div v-if="p_projet.id" class="row">
      <div class="col-12 q-pa-lg">

        <q-card class="q-pa-lg q-mb-xs" flat>
          <div class="row">
            <div class="col-3">
              <q-card class="q-pa-lg bg-grey-3 text-center" flat>
                <br>
                <h5><b>{{p_projet.titre}}</b></h5>
                <br>
              </q-card>
            </div>
            <div class="col-7 q-pa-lg">
              <span class="text-h5">
<!--                {{p_tasks.length}}-->
                <!--                <q-badge size="lg" outline color="green" label="Taches" />-->
                <q-btn size="md" outline color="green" :label="p_tasks.length+ ' Taches'" />
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
              <!--              <span>Tests</span>-->
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
                  <q-tab name="depenses" label="Dépenses" />
                  <q-tab name="facture" label="Facture" @click="factures_get()"/>
                  <!--                  <q-tab name="previsions" label="Prévisions" />-->
                  <!--                  <q-tab name="facture" label="Factures" />-->
                  <!--                  <q-tab name="versement" label="Versements" />-->
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
                    <q-item v-for="(task, index) in p_tasks.slice(0, 5)" :key="index" v-ripple clickable>
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
                    <q-item v-for="(employe, index) in employes.slice(0, 5)" :key="index" v-ripple clickable>
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
                        <q-item v-ripple clickable>
                          <q-item-label><span class="text-grey text-weight-bold">-</span> En cours</q-item-label>
                        </q-item>
                        <q-item v-ripple clickable>
                          <q-item-label><span class="text-green text-weight-bold">-</span>Terminé(s)</q-item-label>
                        </q-item>
                        <q-item v-ripple clickable>
                          <q-item-label><span class="text-red text-weight-bold">-</span>Echec(s)</q-item-label>
                        </q-item>
                        <q-item v-ripple clickable>
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
            <q-card class="q-pa-lg q-mt-lg" flat>
              <task-list :employes="employes" :tasks="p_tasks" :project_id="$route.params.id" @reload="getTaskList()" />
            </q-card>
          </q-tab-panel>

          <q-tab-panel name="activites" class="no-padding no-margin">
            <br>
            <div class="row">
              <div class="col-12 q-mb-md">
                <q-card class="q-pa-lg" flat>
                  <br>
                  <div class="text-h6">Matières utilisées</div>
                  <br>

                  <div v-for="(use, index) in used" :key="index" class="row">
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
                      <q-input  v-model="use.quantite" type="number" label="quantité utilisé" />
                    </div>
                    <div class="col-3 q-pa-sm">
                      <br>
                      <q-btn
                        v-if="!use.id" icon="add" size="sm" outline
                        color="green" title="ajouter" @click="p_projet_stock_post(use)" />
                      &nbsp;
                      <q-btn v-if="use.id" icon="edit" size="sm" outline title="modifier" />
                      &nbsp;
                      <q-btn v-if="use.id" icon="delete" size="sm" color="red" outline title="supprimer" />
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
                <q-card class="q-pa-lg" flat>
                  <h6>Prévisions</h6>
                </q-card>
              </div>
            </div>
          </q-tab-panel>

          <q-tab-panel name="depenses" class="no-padding no-margin">
            <br>
            <q-card class="q-pa-lg" flat>
              <div class="row">
                <div class="col-12 q-pa-sm">
                  <q-table
                    id="printMe" title="Treats" :rows="depenses" :columns="columnsDepense" row-key="name"
                    :filter="filter" flat>
                    <template #top>
                      <div class="print-hide col-4 q-table__title">Depenses</div>
                      <div class="col-3"></div>
                      <div class="col-5">
                        <q-btn dense outline color="primary" label="Ajouter" class="q-ml-md print-hide" @click="depenseModal=true"/>
                        <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md print-hide" @click="json2csv(data, 'vente')"/>
                        <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md print-hide" />
                        <q-input v-model="filter" dense debounce="300" placeholder="Rechercher" class="float-right q-ml-md" />
                      </div>
                      <br>
                      <br>
                      <br>
                    </template>
                    <template #body="props">
                      <q-tr :props="props">
                        <q-td key="id" :props="props"> {{props.row.id}} </q-td>
                        <q-td key="name" :props="props"> {{props.row.name}} </q-td>
                        <q-td key="price" :props="props"> {{props.row.price}} </q-td>
                        <q-td key="client" :props="props"> {{props.row.client}} </q-td>
                        <q-td key="email" :props="props"> {{props.row.email}} </q-td>
                        <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
                        <q-td key="date" :props="props"> {{props.row.date}} </q-td>
                        <q-td key="actions" :props="props">
                          <!--                          <q-btn size="xs" icon="edit" v-on:click="btn_update(props.row); medium = true"></q-btn>-->
                          <!--                          <q-btn color="red-4" size="xs" icon="delete" v-on:click="btn_delete()"></q-btn>-->
                        </q-td>
                      </q-tr>
                    </template>
                  </q-table>


                  <q-dialog v-model="depenseModal">
                    <DepenseAdd :depense="depense" @reload="depenseGet()" />
                  </q-dialog>

                </div>

              </div>
            </q-card>
          </q-tab-panel>

          <q-tab-panel name="facture" class="no-padding no-margin">
            <br>
            <q-card class="q-pa-lg" flat>
              <div class="row">

                <div class="col-12 q-mb-lg q-pa-sm">
                  <q-card v-if="versements && products" id="facture" style="width: 1200px; max-width: 100%;" :flat="true">

                    <div class="row print-hide">

                      <div class="col-12 q-mb-lg">
                        <div class="text-h6">Gestion des versements</div>
                        <br>
                        &nbsp;<q-btn outline color="grey" size="xs" @click="versements.pop()">-</q-btn>
                        &nbsp;<q-btn outline color="primary" size="xs" icon="add" @click="versements.push({montant: 0})">
                        <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">Ajouter un versement</q-tooltip>
                      </q-btn>
                      </div>

                      <div v-for="fac in versements" :key="fac.id" class="row q-mb-md">

                        <q-input v-model="fac.echeance" filled class="col-1 q-mr-sm" dense label="Date Echéance" type="date" stack-label  />
                        <q-input
                          v-model="fac.pourcentage" filled class="col-1 q-mr-sm" dense label="Pourcentage" type="number" stack-label
                          @update:model-value="fac.montant_echeance=(total * fac.pourcentage)/100" />
                        <q-input v-model="fac.montant_echeance" filled class="col-1 q-mr-sm" dense label="Montant Echéance" stack-label type="number" />
                        &nbsp;
                        &nbsp;
                        <q-select
                          v-model="fac.paiement" class="col-1 q-mr-sm" dense label="Type paiement" stack-label
                          :options="['virement', 'cheque', 'espece']" />
                        <q-input v-model="fac.date" class="col-1 q-mr-sm" dense label="Date Vers" type="date" stack-label  />
                        <q-input v-model="fac.montant" class="col-1 q-mr-sm" dense label="Montant Vers" stack-label type="number" />
                        <q-input v-model="fac.numero" class="col-2 q-mr-sm" dense label="N°Chèque/Virement" />
                        <q-input v-model="fac.banque" class="col-1 q-mr-sm" dense label="Banque" />
                        <q-input v-model="fac.emission" class="col-1 q-mr-sm" dense label="Date Emission" type="date" stack-label  />

                        <div class="col-1 q-ml-sm q-pt-md">
                          <q-btn v-if="fac.id" outline color="secondary" size="sm" @click="credit_update(fac)">✎</q-btn>
                          <q-btn v-if="!fac.id" outline size="sm" @click="credit_add(fac)">✅</q-btn>
                        </div>
                      </div>
                    </div>

                  </q-card>
                </div>


                <div class="col-12 q-mt-md q-pa-sm text-center">
                  <facture-component
                    v-if="products" name="Facture de devis"
                    :entreprise="entreprise" :client="client" :facturenum="facture_number" :products="products" />
                </div>

              </div>
            </q-card>
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
import TaskList from "components/taskList.vue";
import {employeGetService, p_task_projet_get} from "src/services/api/rh.api";
import Filescomponent from "components/filescomponent.vue";
import DepenseAdd from "components/DepenseAdd.vue";
import FactureComponent from "components/facture_component.vue";

export default {
  components: {FactureComponent, DepenseAdd, TaskList, Filescomponent},
  mixins: [basemixin, apimixin],
  data () {
    return {
      tab: "mails",
      fileStatus: false,
      depenseModal: false,
      dateposted: null,
      facture_number: null,
      depense: {},
      client: {},
      p_projet: {},
      p_tasks: [],
      employes: [],
      products: [],
      versements: [],
      factures: [],
      factures_init: [],
      used: [],
      depenses: [],
      columnsDepense: [
        { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true },
        { name: 'name', align: 'center', label: 'Titre', field: 'name', sortable: true },
        { name: 'price', required: true, label: 'Depense', field: row => row.price, format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'client', label: 'Beneficiaire', field: 'client', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'telephone', label: 'Telephone', field: 'telephone', sortable: true },
        { name: 'date', label: 'Date', field: 'date', sortable: true },
        { name: 'actions', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      filter: '',
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.total ), 0);
    }
  },
  created () {
    this.depense.projetid = Number(this.$route.params.id)
    this.depenseGet()
    this.p_projet_get()
    this.p_projet_stock_get()
    this.getTaskList()
    this.employesGet()
    this.productsGet()
  },
  methods: {
    factures_get () {
      $httpService.getWithParams('/my/get/factures_id/'+this.$route.params.id)
        .then((response) => {
          this.factures = response;
          this.factures_init = response;
          this.factures_get_id(response[0].facture);
          this.factures_get_credit(response[0].facture);
          for (let i = 0; i < this.factures.length; i++) {
            if (this.factures[i].client) {
              this.factures[i].fullname = JSON.parse(this.factures[i].client)['fullname'];
            }
          }
        })
    },
    factures_get_id (__facture) {
      $httpService.getWithParams('/my/get/sales_by_facture', { id_vente: __facture })
        .then((response) => {
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = JSON.parse(response[0]['client']);
          this.date = this.dateformat(this.products[0]['dateposted'], 4);
          this.dateposted = new Date(this.products[0]['dateposted']).toISOString().slice(0, 16);

          for (let i = 0; i < response.length; i++) {
            response[i].price = response[i].prix_unitaire;
            response[i].quantity = response[i].quantite_vendu;
          }
        })
    },
    factures_get_credit (__facture) {
      $httpService.getWithParams('/my/get/sales_by_credit', { id_vente: __facture })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_add(facture) {
      facture.factureid = this.facture_number;
      facture.vente = 'vente';
      facture.type = 'vente';
      $httpService.postWithParams('/my/post/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit(this.facture_number);
        })
    },
    credit_update(facture) {
      facture.factureid = this.facture_number;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/put/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit(this.facture_number);
        })
    },
    depenseGet () {
      $httpService.getWithParams('/my/projet/depenses/'+this.$route.params.id)
        .then((response) => {
          this.depenses = response;
        })
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
    getTaskList () {
      p_task_projet_get(this.$route.params.id).then((response) => {
        this.p_tasks = response
      });
    },
    employesGet () {
      employeGetService().then((response) => {
        this.employes = response
      });
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
