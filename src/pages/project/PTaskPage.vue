
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table
title="p_tasks" :rows="p_tasks" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des p_task</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='libelle' :props='props'> {{props.row.libelle}} </q-td>
              <q-td key='description' :props='props'> {{props.row.description}} </q-td>
              <q-td key='status' :props='props'>
                <q-btn outline color="grey" size="sm">{{props.row.status}} </q-btn>
              </q-td>
              <q-td key='p_projet_id' :props='props'> {{props.row.p_projet_id}} </q-td>
              <q-td key='debut' :props='props'> {{props.row.debut}} </q-td>
              <q-td key='fin' :props='props'> {{props.row.fin}} </q-td>
              <q-td key='tuuid' :props='props'> {{props.row.tuuid}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_task_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_task</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-input v-model='p_task.libelle' dense label='libelle' />
                <q-input v-model='p_task.description' dense type='textarea' label='description' />
<!--                <q-input dense v-model='p_task.status' label='status' />-->
                <q-select
v-model='p_task.status'
                          :options="['ENATTENTE', 'ENCOURS', 'TERMINE','STOPPE', 'STOPPE']" outlined class="q-mb-sm" dense label='status' />
                <q-input v-model='p_task.p_projet_id' dense type='number' label='p_projet_id' />
                <q-input v-model='p_task.debut' dense type='date' label='debut' />
                <q-input v-model='p_task.fin' dense type='date' label='fin' />
<!--                <q-input dense v-model='p_task.tuuid' label='tuuid' />-->
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
import {p_task_get} from 'src/services/api/rh.api';
import basemixin from '../basemixin';
export default {
  mixins: [basemixin],
  data () {
    return {
      medium2: false,
      p_task: {},
      p_tasks: [],
      columns: [
        { name: 'libelle', align: 'left', label: 'libelle', field: 'libelle', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'status', align: 'left', label: 'status', field: 'status', sortable: true },
        { name: 'p_projet_id', align: 'left', label: 'p_projet_id', field: 'p_projet_id', sortable: true },
        { name: 'debut', align: 'left', label: 'debut', field: 'debut', sortable: true },
        { name: 'fin', align: 'left', label: 'fin', field: 'fin', sortable: true },
        { name: 'tuuid', align: 'left', label: 'tuuid', field: 'tuuid', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.p_task_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_task = props
      this.medium2 = true
    },
    onSubmit () {
      if (this.p_task.id) {
        this.p_task_update()
      } else {
        this.p_task_post()
      }
    },
    p_task_get () {
      p_task_get().then((response) => {
        this.p_tasks = response
      });
    },
    p_task_post () {
      this.showLoading()
      this.p_task.tuuid = this.generateUuid()
      $httpService.postWithParams('/api/post/p_task', this.p_task)
        .then((response) => {
          this.p_task = {}
          this.p_task_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_task_update () {
      this.showLoading()
      $httpService.putWithParams('/api/put/p_task', this.p_task)
        .then((response) => {
          this.p_task_get()
          this.showAlert(response, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_task_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_task/' + _id)
        .then((response) => {
          this.p_task_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
