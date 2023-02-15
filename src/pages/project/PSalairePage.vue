
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="p_salaires" :rows="p_salaires" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des p_salaire</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key='mois' :props='props'> {{props.row.mois}} </q-td>
              <q-td key='annnee' :props='props'> {{props.row.annnee}} </q-td>
              <q-td key='remuneration' :props='props'> {{props.row.remuneration}} </q-td>
              <q-td key='datevirement' :props='props'> {{props.row.datevirement}} </q-td>
              <q-td key='p_employe_id' :props='props'> {{props.row.p_employe_id}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_salaire_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_salaire</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense type='number' v-model='p_salaire.mois' label='mois' />
                <q-input dense type='number' v-model='p_salaire.annnee' label='annnee' />
                <q-input dense type='number' v-model='p_salaire.remuneration' label='remuneration' />
                <q-input dense v-model='p_salaire.datevirement' label='datevirement' />
                <q-input dense type='number' v-model='p_salaire.p_employe_id' label='p_employe_id' />

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
export default {
  data () {
    return {
      p_salaire_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_salaire: {},
      p_salaires: [],
      columns: [
        { name: 'mois', align: 'left', label: 'mois', field: 'mois', sortable: true },
        { name: 'annnee', align: 'left', label: 'annnee', field: 'annnee', sortable: true },
        { name: 'remuneration', align: 'left', label: 'remuneration', field: 'remuneration', sortable: true },
        { name: 'datevirement', align: 'left', label: 'datevirement', field: 'datevirement', sortable: true },
        { name: 'p_employe_id', align: 'left', label: 'p_employe_id', field: 'p_employe_id', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  mixins: [basemixin],
  created () {
    this.p_salaire_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_salaire = props
      this.medium2 = true
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
