
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true; p_employe= {}" />
        <br><br>
        <q-table
title="p_employes" :rows="p_employes" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des employés</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props" @click="p_employe_id = props.row.id;">
              <q-td key='qrcode' :props='props'>
                <vue-qr :size="128" :text="props.row.euuid" :qid="props.row.euuid" />
                <!--                <vue-qr :size="128" :text="props.row.euuid" :qid="props.row.euuid" />-->
              </q-td>
              <q-td key='nom' :props='props'> {{props.row.nom}} </q-td>
              <q-td key='prenom' :props='props'> {{props.row.prenom}} </q-td>
              <q-td key='telephone' :props='props'> {{props.row.telephone}} </q-td>
              <q-td key='email' :props='props'> {{props.row.email}} </q-td>
              <q-td key='cni' :props='props'> {{props.row.cni}} </q-td>
              <q-td key='datenaissance' :props='props'> {{props.row.datenaissance}} </q-td>
              <q-td key='genre' :props='props'> {{props.row.genre}} </q-td>
              <q-td key='fonction' :props='props'> {{props.row.fonction}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn title="photo" outline class="q-mr-xs" size="xs" color="dark" icon="photo" @click="typeid=props.row.id;modalPhoto=true"></q-btn>
                <q-btn title="modifier" outline class="q-mr-xs" size="xs" color="dark" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn title="modifier" outline class="q-mr-xs" size="xs" color="dark" icon="payments" @click="salaire_get(props.row.id)"></q-btn>
                <br>
                <br>
                <q-btn
title="presence" class="q-mr-xs" size="xs" color="primary" outline icon="event_available"
                       @click="modalArrivee = true; p_arrivee_get(props.row.id)"></q-btn>
                <q-btn title="absence" class="q-mr-xs" size="xs" color="red" outline icon="event_busy" @click="modalAbsence = true; p_absence_get(props.row.id)"></q-btn>
                <q-btn title="supprimer" class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_employe_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="salaireModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div v-if="!p_employe.id" class="text-h6">Salaire par employé</div>
        </q-card-section>
        <q-card-section>

          <div v-for="(salaire, index) in salaires" :key="index" class="row q-ma-sm">
            <div class="col-8">
              <q-input
v-model="salaire.montant" dense outlined :label="salaire.name"
                       @focusout="p_salaire_prime_post(salaire)" />
            </div>
            <div class="col-4 q-pl-lg">
              <q-chip v-if="salaire.gain" outline label="Gains" color="green-3" icon="north_east"></q-chip>
              <q-chip v-if="!salaire.gain" outline label="prelèvements" color="red-3" icon="south_west"></q-chip>
              <!--                <q-input v-model="salaire.montant" :label="salaire.name"  />-->
            </div>
          </div>

          <div class="row q-ma-sm q-pt-lg">
            <div class="col-12">
              <br>
              <q-btn label="Valider" color="primary" />
            </div>
          </div>

        </q-card-section>
      </q-card>
    </q-dialog>


    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div v-if="!p_employe.id" class="text-h6">Ajouter un Employe</div>
          <div v-if="p_employe.id" class="text-h6">Modifier un employe</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <vue-qr v-if="p_employe.id" :size="75" :text="p_employe.euuid" :qid="p_employe.euuid" />
                <br>
                <q-input v-model='p_employe.nom' outlined dense label='Nom' />
                <br>
                <q-input v-model='p_employe.prenom' outlined dense label='Prénom' />
                <br>
                <q-input v-model='p_employe.telephone' outlined dense label='Téléphone' />
                <br>
                <q-input v-model='p_employe.email' outlined dense label='Email' />
                <br>
                <q-input v-model='p_employe.cni' outlined dense label='CNI' />
                <br>
                <q-input v-model='p_employe.cnps' outlined dense label='CNPS' />
                <br>
                <q-input v-model='p_employe.matricule' outlined dense label='Matricule' />
                <br>
<!--                <upload v-model='p_employe.photo' title='photo' @blurevent="setEvent($event, 'photo')" />-->
<!--                <br>-->
                <q-input v-model='p_employe.whatsapp' outlined dense label='Whatsapp' />
                <br>
                <q-input v-model='p_employe.adresse' outlined dense type='textarea' label='Adresse' />
                <br>
                <q-input v-model='p_employe.datenaissance' outlined dense type='date' label='Date de naissance' />
                <br>
                <q-input v-model='p_employe.genre' outlined dense label='Genre' />
                <br>
                <q-input v-model='p_employe.banquerib' outlined dense label='RIB' />
                <br>
                <q-input v-model='p_employe.banquename' outlined dense label='Nom de la banque' />
                <br>
                <q-input v-model='p_employe.fonction' outlined dense label='Fonction' />
                <br>
                <q-input v-model='p_employe.datearrivee' outlined dense type='date' label='Date arrivee' />
                <br>
                <q-input v-model='p_employe.datefin' outlined dense type='date' label='Date Départ' />
                <br>
                <q-input v-model='p_employe.experience' outlined dense type='textarea' label='Experience' />
                <br>
                <q-input v-model='p_employe.education' outlined dense type='textarea' label='Education' />
                <!--                <br>-->
                <!--                <q-input dense v-model='p_employe.euuid' label='euuid' />-->
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

    <q-dialog v-model="modalPhoto">
      <q-card style="width: 1000px" class="q-pa-lg">
        <filescomponent type="employe" :typeid="typeid" folder="employe" />
      </q-card>
    </q-dialog>

    <q-dialog v-model="modalArrivee" position="top" full-width>
      <q-card style="width: 1000px" class="q-pa-lg">
        <div class="row justify-center">
          <div class="col-12 q-mt-md">
            <q-table
title="p_arrivees" :rows="p_arrivees" :columns="columnsArrivees"
                     :filter="filter" :pagination="pagination" row-key="name" dense>
              <template #top>
                <div class="col-7 q-table__title">Liste des arrivées</div>
                <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
              </template>
              <template #body="props">
                <q-tr :props="props">
                  <q-td key='venue' :props='props'> {{props.row.venue}} </q-td>
                  <q-td key='depart' :props='props'> {{props.row.depart}} </q-td>
                  <q-td key='heurepause' :props='props'> {{props.row.heurepause}} </q-td>
                  <q-td key='p_employe_id' :props='props'> {{props.row.p_employe_id}} </q-td>
                </q-tr>
              </template>
            </q-table>
          </div>
        </div>
      </q-card>
    </q-dialog>

    <q-dialog v-model="modalAbsence" position="top" full-width>
      <q-card style="width: 1000px" class="q-pa-lg">
        <q-form  class="q-gutter-md" @submit="p_absence_post()">
          <div class="row justify-center">
            <div class="col-2 q-pr-md">
              <q-input v-model="absence.date" type="date" label="Date" />
            </div>
            <div class="col-1 q-pr-md">
              <q-input v-model="absence.heure" type="number" label="Heure" />
            </div>
            <div class="col-8 q-pr-md">
              <q-input v-model="absence.justificatif" type="text" label="Justificatif" />
            </div>
            <div class="col-1 q-pr-md">
              <q-btn label="Valider" type="submit" />
            </div>
          </div>
        </q-form>
        <br>
        <div class="row justify-center">
          <div class="col-12 q-mt-md">
            <q-table
title="p_arrivees" :rows="p_absences" :columns="columnsAbsences"
                     :filter="filter" :pagination="pagination" row-key="name" dense>
              <template #top>
                <div class="col-7 q-table__title">Liste des absences</div>
                <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
              </template>
              <template #body="props">
                <q-tr :props="props">
                  <q-td key="actions" :props="props">
                    <q-btn class="q-mr-xs" size="xs" color="primary" icon="photo"></q-btn>
                    <q-popup-edit v-model="props.row.id">
                      <filescomponent type="absence" :typeid="props.row.id" folder="absence" />
                    </q-popup-edit>
                  </q-td>
                  <q-td key='date' :props='props'> {{props.row.date}} </q-td>
                  <q-td key='justificatif' :props='props'> {{props.row.justificatif}} </q-td>
                  <q-td key='heure' :props='props'> {{props.row.heure}} </q-td>
                  <q-td key='p_employe_id' :props='props'> {{props.row.p_employe_id}} </q-td>
                </q-tr>
              </template>
            </q-table>
          </div>
        </div>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import vueQr from 'vue-qr/src/packages/vue-qr.vue'
import Filescomponent from "components/filescomponent.vue";

export default {
  components: {
    Filescomponent,
    vueQr
  },
  mixins: [basemixin],
  data () {
    return {
      typeid: 0,
      p_employe_id: 1,
      medium2: false,
      salaireModal: false,
      modalArrivee: false,
      modalAbsence: false,
      p_employe: {},
      absence: {heure: 24},
      p_employes: [],
      p_arrivees: [],
      p_absences: [],
      salaires: [],
      columnsArrivees: [
        { name: 'venue', align: 'left', label: 'venue', field: 'venue', sortable: true },
        { name: 'depart', align: 'left', label: 'depart', field: 'depart', sortable: true },
        { name: 'heurepause', align: 'left', label: 'heurepause', field: 'heurepause', sortable: true },
        { name: 'p_employe_id', align: 'left', label: 'p_employe_id', field: 'p_employe_id', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      columnsAbsences: [
        { name: 'actions', align: 'left', label: 'Actions' },
        { name: 'date', align: 'left', label: 'Date', field: 'date', sortable: true },
        { name: 'justificatif', align: 'left', label: 'Justificatif', field: 'justificatif', sortable: true },
        { name: 'heure', align: 'left', label: 'Heure', field: 'heure', sortable: true },
        { name: 'p_employe_id', align: 'left', label: 'Employe', field: 'p_employe_id', sortable: true },
      ],
      columns: [
        { name: 'qrcode', align: 'left', label: 'qrcode', field: 'qrcode', sortable: true },
        { name: 'nom', align: 'left', label: 'nom', field: 'nom', sortable: true },
        { name: 'prenom', align: 'left', label: 'prenom', field: 'prenom', sortable: true },
        { name: 'telephone', align: 'left', label: 'telephone', field: 'telephone', sortable: true },
        { name: 'email', align: 'left', label: 'email', field: 'email', sortable: true },
        { name: 'cni', align: 'left', label: 'cni', field: 'cni', sortable: true },
        // { name: 'photo', align: 'left', label: 'photo', field: 'photo', sortable: true },
        // { name: 'whatsapp', align: 'left', label: 'whatsapp', field: 'whatsapp', sortable: true },
        // { name: 'adresse', align: 'left', label: 'adresse', field: 'adresse', sortable: true },
        { name: 'datenaissance', align: 'left', label: 'datenaissance', field: 'datenaissance', sortable: true },
        { name: 'genre', align: 'left', label: 'genre', field: 'genre', sortable: true },
        // { name: 'banquerib', align: 'left', label: 'banquerib', field: 'banquerib', sortable: true },
        // { name: 'banquename', align: 'left', label: 'banquename', field: 'banquename', sortable: true },
        { name: 'fonction', align: 'left', label: 'fonction', field: 'fonction', sortable: true },
        // { name: 'datearrivee', align: 'left', label: 'datearrivee', field: 'datearrivee', sortable: true },
        // { name: 'datefin', align: 'left', label: 'datefin', field: 'datefin', sortable: true },
        // { name: 'experience', align: 'left', label: 'experience', field: 'experience', sortable: true },
        // { name: 'education', align: 'left', label: 'education', field: 'education', sortable: true },
        // { name: 'euuid', align: 'left', label: 'euuid', field: 'euuid', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 20 }
    }
  },
  created () {
    this.p_employe_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    salaire_get (__id) {
      this.salaireModal = true
      this.p_salaire_prime_get(__id)
    },
    update_get (props) {
      this.p_employe = props
      this.medium2 = true
    },
    p_arrivee_get (__id) {
      $httpService.getApi('/api/get/p_arrivee/'+__id)
        .then((response) => {
          this.p_arrivees = response
        })
    },
    p_salaire_prime_get (__id) {
      $httpService.getApi('/my/employe/prime/'+__id)
        .then((response) => {
          this.salaires = response
        })
    },
    p_salaire_prime_post (__prime) {
      setTimeout(() => {
        __prime.p_employe_id = this.p_employe_id
        __prime.p_prime_id = __prime.id
        $httpService.postWithParams('/my/post/employe_prime', __prime)
          .then((response) => {
            console.log(response)
            // this.salaires = response
          })
      }, 300)
    },
    p_absence_get (__id) {
      $httpService.getApi('/api/get/p_absence/'+__id)
        .then((response) => {
          this.p_absences = response
        })
    },
    p_employe_get () {
      $httpService.getApi('/api/get/p_employe')
        .then((response) => {
          this.p_employes = response
        })
    },
    onSubmit () {
      if (this.p_employe.id) {
        this.p_employe_update()
      } else {
        this.p_employe_post()
      }
    },
    p_absence_post () {
      this.absence.p_employe_id = this.p_employe_id;
      this.showLoading();
      $httpService.postWithParams('/api/post/p_absence', this.absence)
        .then((response) => {
          this.absence = {heure: 24}
          this.p_absence_get(this.p_employe_id)
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_employe_post () {
      this.showLoading()
      $httpService.postWithParams('/api/post/p_employe', this.p_employe)
        .then((response) => {
          this.p_employe = {}
          this.p_employe_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_employe_update () {
      this.showLoading()
      $httpService.putWithParams('/api/put/p_employe', this.p_employe)
        .then((response) => {
          this.p_employe_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_employe_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_employe/' + _id)
        .then((response) => {
          this.p_employe_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
