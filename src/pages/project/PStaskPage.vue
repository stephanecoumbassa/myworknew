
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table
title="p_stasks" :rows="p_stasks" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des p_stask</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='titre' :props='props'> {{props.row.titre}} </q-td>
              <q-td key='description' :props='props'> {{props.row.description}} </q-td>
              <q-td key='p_task_id' :props='props'> {{props.row.p_task_id}} </q-td>
              <q-td key='debut' :props='props'> {{props.row.debut}} </q-td>
              <q-td key='fin' :props='props'> {{props.row.fin}} </q-td>
              <q-td key='stuuid' :props='props'> {{props.row.stuuid}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_stask_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_stask</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-input v-model='p_stask.titre' dense label='titre' />
                <q-input v-model='p_stask.description' dense type='textarea' label='description' />
                <q-input v-model='p_stask.p_task_id' dense type='number' label='p_task_id' />
                <q-input v-model='p_stask.debut' dense type='date' label='debut' />
                <q-input v-model='p_stask.fin' dense type='date' label='fin' />
                <q-input v-model='p_stask.stuuid' dense label='stuuid' />

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
  mixins: [basemixin],
  data () {
    return {
      medium2: false,
      p_stask: {},
      p_stasks: [],
      columns: [
        { name: 'titre', align: 'left', label: 'titre', field: 'titre', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'p_task_id', align: 'left', label: 'p_task_id', field: 'p_task_id', sortable: true },
        { name: 'debut', align: 'left', label: 'debut', field: 'debut', sortable: true },
        { name: 'fin', align: 'left', label: 'fin', field: 'fin', sortable: true },
        { name: 'stuuid', align: 'left', label: 'stuuid', field: 'stuuid', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.p_stask_get()
  },
  methods: {
    update_get (props) {
      this.p_stask = props
      this.medium2 = true
    },
    p_stask_get () {
      $httpService.getApi('/api/get/p_stask')
        .then((response) => {
          this.p_stasks = response
        })
    },
    onSubmit () {
      if (this.p_stask.id) {
        this.p_stask_update()
      } else {
        this.p_stask_post()
      }
    },
    p_stask_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_stask', this.p_stask)
        .then((response) => {
          this.p_stask = {}
          this.p_stask_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_stask_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_stask', this.p_stask)
        .then((response) => {
          this.p_stask_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_stask_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_stask/' + _id)
        .then((response) => {
          this.p_stask_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
