
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="p_absences" :rows="p_absences" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des p_absence</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key='date' :props='props'> {{props.row.date}} </q-td>
              <q-td key='justificatif' :props='props'> {{props.row.justificatif}} </q-td>
              <q-td key='heure' :props='props'> {{props.row.heure}} </q-td>
              <q-td key='all' :props='props'> {{props.row.all}} </q-td>
              <q-td key='p_employe_id' :props='props'> {{props.row.p_employe_id}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_absence_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_absence</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense type='date' v-model='p_absence.date' label='date' />
                <q-input dense v-model='p_absence.justificatif' label='justificatif' />
                <q-input dense type='number' v-model='p_absence.heure' label='heure' />
                <q-input dense type='number' v-model='p_absence.p_employe_id' label='p_employe_id' />

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
      p_absence_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_absence: {},
      p_absences: [],
      columns: [
        { name: 'date', align: 'left', label: 'date', field: 'date', sortable: true },
        { name: 'justificatif', align: 'left', label: 'justificatif', field: 'justificatif', sortable: true },
        { name: 'heure', align: 'left', label: 'heure', field: 'heure', sortable: true },
        { name: 'all', align: 'left', label: 'all', field: 'all', sortable: true },
        { name: 'p_employe_id', align: 'left', label: 'p_employe_id', field: 'p_employe_id', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  mixins: [basemixin],
  created () {
    this.p_absence_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_absence = props
      this.medium2 = true
    },
    setEvent (payload, _name) {
      this.p_absence[_name] = payload
    },
    handleFile (_name) {
      this.p_absence[_name] = this.$refs[_name].files[0]
    },
    p_absence_get () {
      $httpService.getApi('/api/get/p_absence')
        .then((response) => {
          this.p_absences = response
        })
    },
    onSubmit () {
      if (this.p_absence.id) {
        this.p_absence_update()
      } else {
        this.p_absence_post()
      }
    },
    p_absence_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_absence', this.p_absence)
        .then((response) => {
          this.p_absence = {}
          this.p_absence_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_absence_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_absence', this.p_absence)
        .then((response) => {
          this.p_absence_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_absence_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_absence/' + _id)
        .then((response) => {
          this.p_absence_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
