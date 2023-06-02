
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
                  <q-input v-model="date" type="month" placeholder="Mois" :dense="true" hint="Mois" @change="p_bulletin_get(date)" />
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
        <q-table
          title="p_salaires" :rows="p_salaires" :columns="columns" :filter="filter"
          :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des salaires payés et impayés</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props" @click="p_employe_id = props.row.id;">
              <q-td key='nom' :props='props'> {{props.row.nom}} </q-td>
              <q-td key='prenom' :props='props'> {{props.row.prenom}} </q-td>
              <q-td key='telephone' :props='props'> {{props.row.telephone}} </q-td>
              <q-td key='genre' :props='props'> {{props.row.genre}} </q-td>
              <q-td key='fonction' :props='props'> {{props.row.fonction}} </q-td>
              <q-td key='paye' :props='props'>
                <q-chip v-if="props.row.paye" label="Payé" color="green-1" size="md" />
                <q-chip v-if="!props.row.paye" label="Non Payé" color="red-1" size="md" />
                <!--                {{props.row.fonction}}-->
              </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" outline color="dark" icon="payments" @click="update_get(props.row)"></q-btn>
                <q-btn
                  v-if="props.row.paye" class="q-mr-xs" size="xs" outline color="primary" icon="receipt_long"
                  title="Bulletin de salaire" @click="bulletinModal=true;currentEmploye=props.row"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="bulletinModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <BulletinComponent
          :employe="currentEmploye" :salaire="JSON.parse(currentEmploye.bulletin.infos)"
          :bulletin="currentEmploye.bulletin"  />
      </q-card>
    </q-dialog>


    <q-dialog v-model="paiementModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Effectuer un paiement</div>
        </q-card-section>
        <q-card-section>
          <div v-for="(prime, index) in currentPrimes" :key="index" class="row q-ma-sm">
            <div class="col-8">
              <q-input v-model="prime.montant" dense outlined :label="prime.name" />
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
  mixins: [basemixin],
  data () {
    return {
      bulletinModal: false,
      paiementModal: false,
      p_employe_id: null,
      p_salaires: [],
      currentPrimes: [],
      currentEmploye: [],
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
  created () {
    this.date = this.year +'-'+this.month
    this.p_bulletin_get()
  },
  methods: {
    update_get (props) {
      this.paiementModal = true
      this.currentPrimes = props.prime
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
  }
}
</script>
