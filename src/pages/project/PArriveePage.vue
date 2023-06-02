
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table
title="p_arrivees" :rows="p_arrivees" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des p_arrivee</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='venue' :props='props'> {{props.row.venue}} </q-td>
              <q-td key='depart' :props='props'> {{props.row.depart}} </q-td>
              <q-td key='heurepause' :props='props'> {{props.row.heurepause}} </q-td>
              <q-td key='p_employe_id' :props='props'> {{props.row.p_employe_id}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_arrivee_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_arrivee</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-input v-model='p_arrivee.heurepause' dense type='number' label='heurepause' />
                <q-input v-model='p_arrivee.p_employe_id' dense type='number' label='p_employe_id' />

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
export default {
  name: 'PArriveePage',
  mixins: [basemixin],
  data () {
    return {
      medium2: false,
      p_arrivee: {},
      p_arrivees: [],
      columns: [
        { name: 'venue', align: 'left', label: 'venue', field: 'venue', sortable: true },
        { name: 'depart', align: 'left', label: 'depart', field: 'depart', sortable: true },
        { name: 'heurepause', align: 'left', label: 'heurepause', field: 'heurepause', sortable: true },
        { name: 'p_employe_id', align: 'left', label: 'p_employe_id', field: 'p_employe_id', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.p_arrivee_get()
  },
  methods: {
    update_get (props) {
      this.p_arrivee = props
      this.medium2 = true
    },
    p_arrivee_get () {
      $httpService.getApi('/api/get/p_arrivee')
        .then((response) => {
          this.p_arrivees = response
        })
    },
    onSubmit () {
      if (this.p_arrivee.id) {
        this.p_arrivee_update()
      } else {
        this.p_arrivee_post()
      }
    },
    p_arrivee_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_arrivee', this.p_arrivee)
        .then((response) => {
          this.p_arrivee = {}
          this.p_arrivee_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_arrivee_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_arrivee', this.p_arrivee)
        .then((response) => {
          this.p_arrivee_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_arrivee_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_arrivee/' + _id)
        .then((response) => {
          this.p_arrivee_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
