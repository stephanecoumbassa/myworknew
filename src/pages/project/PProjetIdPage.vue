
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
      <div class="col-12 q-pa-lg">

        <q-card class="q-pa-lg q-mb-xs">
          <div class="row">
            <div class="col-2">
              <q-card class="q-pa-lg bg-grey-3 text-center">
                <h4>IDH</h4>
              </q-card>
            </div>
            <div class="col-8 q-pa-lg">
              <span class="text-h5">
                237
                <q-badge outline color="green" label="En cours" />
              </span>
              <p class="text-h6 text-grey">Projets courants</p>
              <br>
              <div class="row">
                <div class="col-2 q-pa-sm q-mr-sm" style="border: 1px #e3e3e3 dashed">
                  <span class="text-weight-bold">20 Déc 2023</span><br>
                  <span>Echéance</span>
                </div>
                <div class="col-2 q-pa-sm q-ml-sm" style="border: 1px #e3e3e3 dashed">
                  <span class="text-weight-bold">10.000.000 </span><br>
                  <span>Budget</span>
                </div>
                <div class="col-2 q-pa-sm q-ml-sm" style="border: 1px #e3e3e3 dashed">
                  <span class="text-weight-bold">10.000.000 </span><br>
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
                  <q-tab name="fichiers" label="Fichiers" />
                  <q-tab name="activites" label="Activités" />
                  <q-tab name="parametre" label="Paramètres" />
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

              <div class="col-6 q-mb-md">
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

              <div class="col-6 q-mb-md">
                <q-card class="q-ml-md q-pa-lg">
                  <span class="text-h5">
                    Derniers fichiers
                  </span>
                  <p class="text-h6 text-grey">Projets courants</p>
                  <list-item />
                </q-card>
              </div>

              <div class="col-6 q-mt-md">
                <q-card class="q-mr-md q-pa-lg">
                  <span class="text-h5">
                    Dernières tâches
                  </span>
                  <p class="text-h6 text-grey">Projets courants</p>
                  <list-item />
                </q-card>
              </div>

              <div class="col-6 q-mt-md">
                <q-card class="q-ml-md q-pa-lg">
                  <span class="text-h5">
                    Utilisateurs
                  </span>
                  <p class="text-h6 text-grey">Projets courants</p>
                  <list-item />
                </q-card>
              </div>

            </div>
          </q-tab-panel>

          <q-tab-panel name="alarms">
            <div class="text-h6">Alarms</div>
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

          <q-tab-panel name="movies">
            <div class="text-h6">Movies</div>
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
          </q-tab-panel>

        </q-tab-panels>
        <!--        </q-card>-->

      </div>
    </div>

  </q-page>
</template>

<script>
import $httpService from '../../boot/httpService';
import basemixin from '../basemixin';
import apimixin from "src/services/apimixin";
import ListItem from "components/listItem.vue";
export default {
  components: {ListItem},
  data () {
    return {
      tab: "mails",
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
