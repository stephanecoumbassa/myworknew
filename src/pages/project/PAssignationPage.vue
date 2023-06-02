
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table
title="p_assignations" :rows="p_assignations" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des p_assignation</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='p_task_id' :props='props'> {{props.row.p_task_id}} </q-td>
              <q-td key='p_stask_id' :props='props'> {{props.row.p_stask_id}} </q-td>
              <q-td key='p_executant_id' :props='props'> {{props.row.p_executant_id}} </q-td>
              <q-td key='p_assigneur_id' :props='props'> {{props.row.p_assigneur_id}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_assignation_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_assignation</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-input v-model='p_assignation.p_task_id' dense type='number' label='p_task_id' />
                <q-input v-model='p_assignation.p_stask_id' dense type='number' label='p_stask_id' />
                <q-input v-model='p_assignation.p_executant_id' dense type='number' label='p_executant_id' />
                <q-input v-model='p_assignation.p_assigneur_id' dense type='number' label='p_assigneur_id' />
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
import basemixin from '../basemixin';
import apimixin from "src/services/apimixin";
export default {
  components: {},
  mixins: [basemixin, apimixin],
  data () {
    return {
      medium2: false,
      p_assignation: {},
      p_assignations: [],
      columns: [
        { name: 'p_task_id', align: 'left', label: 'p_task_id', field: 'p_task_id', sortable: true },
        { name: 'p_stask_id', align: 'left', label: 'p_stask_id', field: 'p_stask_id', sortable: true },
        { name: 'p_executant_id', align: 'left', label: 'p_executant_id', field: 'p_executant_id', sortable: true },
        { name: 'p_assigneur_id', align: 'left', label: 'p_assigneur_id', field: 'p_assigneur_id', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.p_assignation_get()
  },
  methods: {
    update_get (props) {
      this.p_assignation = props
      this.medium2 = true
    },
    p_assignation_get () {
      this.getApi('/api/get/p_assignation')
        .then((response) => {
          this.p_assignations = response
        })
    },
    onSubmit () {
      if (this.p_assignation.id) {
        this.p_assignation_update()
      } else {
        this.p_assignation_post()
      }
    },
    p_assignation_post () {
      this.showLoading()
      this.postApi('/api/post/p_assignation', this.p_assignation)
        .then((response) => {
          this.p_assignation = {}
          this.p_assignation_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_assignation_update () {
      this.showLoading()
      this.this.putApi('/api/put/p_assignation', this.p_assignation)
        .then((response) => {
          this.p_assignation_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_assignation_delete (_id) {
      this.showLoading()
      this.deleteApi('/api/delete/p_assignation/' + _id)
        .then((response) => {
          this.p_assignation_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
