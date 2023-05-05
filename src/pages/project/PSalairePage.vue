
<template>
  <q-page padding>

    <div class="row justify-center text-center q-mt-lg">

      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <div class="text-center text-h6">Bulletin de salaire sur une période donnée</div>
              <br>
              <div class="row">
                <div class="col-4 q-pa-sm">
                  <q-input type="month" v-model="date" placeholder="Mois" :dense="true" @change="p_bulletin_get(date)" hint="Mois" />
                </div>
              </div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <br><br>
        <q-table title="p_salaires" :rows="p_salaires" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des salaires payés et impayés</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" @click="p_employe_id = props.row.id;">
              <q-td key='nom' :props='props'> {{props.row.nom}} </q-td>
              <q-td key='prenom' :props='props'> {{props.row.prenom}} </q-td>
              <q-td key='telephone' :props='props'> {{props.row.telephone}} </q-td>
              <q-td key='genre' :props='props'> {{props.row.genre}} </q-td>
              <q-td key='fonction' :props='props'> {{props.row.fonction}} </q-td>
              <q-td key='paye' :props='props'>
                <q-chip label="Payé" color="green-1" size="md" v-if="props.row.paye" />
                <q-chip label="Non Payé" color="red-1" size="md" v-if="!props.row.paye" />
                <!--                {{props.row.fonction}}-->
              </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" outline color="dark" v-on:click="update_get(props.row)" icon="payments"></q-btn>
                <q-btn v-if="props.row.paye" class="q-mr-xs" size="xs" outline color="primary" v-on:click="bulletinModal=true;currentEmploye=props.row"
                       icon="receipt_long" title="Bulletin de salaire"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="bulletinModal">
      <q-card style="width: 700px; max-width: 80vw;">
      <BulletinComponent :employe="currentEmploye" :salaire="JSON.parse(currentEmploye.bulletin.infos)"
                         :bulletin="currentEmploye.bulletin"  />
      </q-card>
    </q-dialog>


    <q-dialog v-model="paiementModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Effectuer un paiement</div>
        </q-card-section>
        <q-card-section>
          <div class="row q-ma-sm" v-for="prime in currentPrimes">
            <div class="col-8">
              <q-input dense outlined v-model="prime.montant" :label="prime.name" />
            </div>
            <div class="col-4 q-pl-lg">
              <q-chip v-if="prime.gain" outline label="Gains" color="green-3" icon="north_east"></q-chip>
              <q-chip v-if="!prime.gain" outline label="prelèvements" color="red-3" icon="south_west"></q-chip>
            </div>
          </div>
          <div class="row q-ma-sm">
            <div class="col-12 q-pt-lg">
              <q-btn label="effectuer le paiement" color="primary" @click="bulletin_pay()" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>


  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import BulletinComponent from "components/BulletinComponent.vue";
export default {
  components: {BulletinComponent},
  data () {
    return {
      p_salaire_id: 1,
      loading1: false,
      date: '',
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      bulletinModal: false,
      paiementModal: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_employe_id: null,
      p_salaire: {},
      p_salaires: [],
      currentPrimes: [],
      currentEmploye: [],
      // columns: [
      //   { name: 'mois', align: 'left', label: 'mois', field: 'mois', sortable: true },
      //   { name: 'annnee', align: 'left', label: 'annnee', field: 'annnee', sortable: true },
      //   { name: 'remuneration', align: 'left', label: 'remuneration', field: 'remuneration', sortable: true },
      //   { name: 'datevirement', align: 'left', label: 'datevirement', field: 'datevirement', sortable: true },
      //   { name: 'p_employe_id', align: 'left', label: 'p_employe_id', field: 'p_employe_id', sortable: true },
      //
      //   { name: 'actions', align: 'left', label: 'Actions' }
      // ],
      columns: [
        { name: 'nom', align: 'left', label: 'nom', field: 'nom', sortable: true },
        { name: 'prenom', align: 'left', label: 'prenom', field: 'prenom', sortable: true },
        { name: 'telephone', align: 'left', label: 'telephone', field: 'telephone', sortable: true },
        { name: 'genre', align: 'left', label: 'genre', field: 'genre', sortable: true },
        { name: 'fonction', align: 'left', label: 'fonction', field: 'fonction', sortable: true },
        { name: 'paye', align: 'left', label: 'Paye', field: 'paye', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  mixins: [basemixin],
  created () {
    // this.p_salaire_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
    this.date = this.year +'-'+this.month
    this.p_bulletin_get()
  },
  methods: {
    update_get (props) {
      this.paiementModal = true
      this.currentPrimes = props.prime
    },
    setEvent (payload, _name) {
      this.p_salaire[_name] = payload
    },
    handleFile (_name) {
      this.p_salaire[_name] = this.$refs[_name].files[0]
    },
    p_salaire_get () {
      $httpService.getApi('/api/get/p_salaire')
        .then((response) => {
          this.p_salaires = response
        })
    },
    p_bulletin_get () {
      let annee = this.date.split('-')[0];
      let mois = this.date.split('-')[1];
      $httpService.getApi('/my/employe/bulletin/'+annee+'/'+mois)
        .then((response) => {
          this.p_salaires = response
        })
    },
    bulletin_pay() {
      const params = {
        annee: this.date.split('-')[0],
        mois: this.date.split('-')[1],
        infos: JSON.stringify(this.currentPrimes),
        p_employe_id: this.p_employe_id,
        paye: 1,
        total: this.calculTotal(this.currentPrimes)
      }
      $httpService.postWithParams('/my/post/p_salaire', params)
        .then((response) => {
          this.p_salaires = response
        })
    },
    calculTotal(primes) {
      let sum = 0;
      for (let i = 0; i < primes.length; i++) {
        if(primes[i].gain == 0) {
          sum = sum - primes[i].montant
        }else{
          sum = sum + primes[i].montant
        }
      }
      return sum;
    },
    onSubmit () {
      if (this.p_salaire.id) {
        this.p_salaire_update()
      } else {
        this.p_salaire_post()
      }
    },
    p_salaire_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_salaire', this.p_salaire)
        .then((response) => {
          this.p_salaire = {}
          this.p_salaire_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_salaire_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_salaire', this.p_salaire)
        .then((response) => {
          this.p_salaire_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_salaire_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_salaire/' + _id)
        .then((response) => {
          this.p_salaire_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
