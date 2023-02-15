
<template>
  <q-page padding>

    <div class="row">
      <div class="col-9 q-pa-lg">
        <span class="text-h6">Projets</span>
      </div>
      <div class="col q-pa-lg float-right text-right">
        <q-btn color="primary" size="sm">Créer</q-btn>
      </div>
    </div>

    <div class="row">
      <div class="col q-pa-lg">
        <q-card class="q-pa-md">
          <span class="text-h4">237</span>
          <p class="text-h6 text-grey">Projets courants</p>
          <div class="row">
            <div class="col-6">
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
                <q-item clickable v-ripple>
                  <q-item-label><span class="text-grey text-weight-bold">-</span> En cours</q-item-label>
                </q-item>
                <q-item clickable v-ripple>
                    <q-item-label><span class="text-green text-weight-bold">-</span>Terminé(s)</q-item-label>
                </q-item>
                <q-item clickable v-ripple>
                    <q-item-label><span class="text-red text-weight-bold">-</span>Echec(s)</q-item-label>
                </q-item>
              </q-list>
            </div>
          </div>
        </q-card>
      </div>
      <div class="col q-pa-lg">
        <q-card class="q-pa-md">
          <span class="text-h4">3,290.00 CFA</span>
          <p class="text-h6 text-grey">Projets finance</p>
          <q-list dense>
            <q-item dense>
              <q-item-section>
                <q-item-label>Single line item</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-item-label caption>5 min ago</q-item-label>
              </q-item-section>
            </q-item>
            <q-separator spaced inset />
            <q-item dense>
              <q-item-section>
                <q-item-label>Single line item</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-item-label caption>5 min ago</q-item-label>
              </q-item-section>
            </q-item>
            <q-separator spaced inset />
            <q-item dense>
              <q-item-section>
                <q-item-label>Single line item</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-item-label caption>5 min ago</q-item-label>
              </q-item-section>
            </q-item>
            <q-separator spaced inset />

          </q-list>
        </q-card>
      </div>
      <div class="col q-pa-lg">
        <q-card class="q-pa-md">
          <span class="text-h4">49</span>
          <p class="text-h6 text-grey">Clients</p>
          <div class="row" style="height: 145px;">
            <q-avatar
              v-for="n in 7"
              :key="n"
              size="40px"
              class="overlapping"
              :style="`left: ${n * 25}px`"
            >
              <img :src="`https://cdn.quasar.dev/img/avatar${n + 1}.jpg`">
            </q-avatar>
          </div>
        </q-card>
      </div>
    </div>

    <div class="row">
      <div class="col-9 q-pa-lg">
        <span class="text-h6">Projets</span>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-md-4 q-pa-lg" v-for="project in p_projets" key="project.id">
        <q-card class="q-pa-lg" clickable @click="navigate('/projet/'+project.id)">
          <div class="row">
            <div class="col-6">
              <q-avatar square size="40px" color="grey">J</q-avatar>
            </div>
            <div class="col float-right text-right">
              <q-btn unelevated color="green-3" size="md">Terminé</q-btn>
<!--              <q-btn color="green-1" size="md">En cours</q-btn>-->
<!--              <q-btn color="red" size="md">Echecs</q-btn>-->
            </div>
          </div>
          <br>
          <span class="text-h6">{{project.titre}}</span>
          <p class="text-subtitle1 text-grey">{{project.objectif}}</p>
          <div class="row">
            <div class="col-5 q-pa-sm q-mr-sm" style="border: 1px #e3e3e3 dashed">
              <span class="text-weight-bold">20 Déc 2023</span><br>
              <span>Echéance</span>
            </div>
            <div class="col-5 q-pa-sm q-ml-sm" style="border: 1px #e3e3e3 dashed">
              <span class="text-weight-bold">10.000.000 CFA</span><br>
              <span>Budget</span>
            </div>
          </div>
          <q-linear-progress color="green" :value="0.7" class="q-mt-lg" />
          <div class="row q-mt-lg">
            <q-avatar color="grey" text-color="white" icon="directions" size="40px" />
            &nbsp;
            <q-avatar color="grey" text-color="white" icon="directions" size="40px" />
            &nbsp;
            <q-avatar color="grey" text-color="white" icon="directions" size="40px" />
            &nbsp;
            <q-avatar color="grey" text-color="white" icon="directions" size="40px" />
          </div>
        </q-card>
<!--        </router-link>-->
      </div>
    </div>

    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_projet</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense v-model='p_projet.titre' label='titre' />
                <q-input dense type='textarea' v-model='p_projet.description' label='description' />
                <q-input dense v-model='p_projet.status' label='status' />
                <q-input dense v-model='p_projet.objectif' label='objectif' />
                <q-input dense type='number' v-model='p_projet.progress' label='progress' />
                <q-input dense type='date' v-model='p_projet.datedebut' label='datedebut' />
                <q-input dense type='date' v-model='p_projet.datefin' label='datefin' />
                <q-input dense type='number' v-model='p_projet.createdby' label='createdby' />
                <q-input dense v-model='p_projet.priorite' label='priorite' />
                <q-input dense type='number' v-model='p_projet.cout' label='cout' />
                <q-input dense type='number' v-model='p_projet.clientid' label='clientid' />
                <q-input dense type='number' v-model='p_projet.porteur' label='porteur' />
                <q-input dense v-model='p_projet.puuid' label='puuid' />
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
          <q-btn flat label="Fermer" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import apimixin from "src/services/apimixin";
export default {
  data () {
    return {
      p_projet_id: 1,
      loading1: false,
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
    this.p_projet_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
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
    p_projet_get () {
      $httpService.getApi('/api/get/p_projet')
        .then((response) => {
          this.p_projets = response
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
      this.showLoading()
      $httpService.postWithParams('/api/post/p_projet', this.p_projet)
        .then((response) => {
          this.p_projet = {}
          this.p_projet_get()
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
    p_projet_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_projet/' + _id)
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
