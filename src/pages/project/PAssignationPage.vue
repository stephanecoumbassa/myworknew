
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="p_assignations" :rows="p_assignations" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des p_assignation</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key='p_task_id' :props='props'> {{props.row.p_task_id}} </q-td>
              <q-td key='p_stask_id' :props='props'> {{props.row.p_stask_id}} </q-td>
              <q-td key='p_executant_id' :props='props'> {{props.row.p_executant_id}} </q-td>
              <q-td key='p_assigneur_id' :props='props'> {{props.row.p_assigneur_id}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_assignation_delete(props.row.id)" icon="delete"></q-btn>
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
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense type='number' v-model='p_assignation.p_task_id' label='p_task_id' />
                <q-input dense type='number' v-model='p_assignation.p_stask_id' label='p_stask_id' />
                <q-input dense type='number' v-model='p_assignation.p_executant_id' label='p_executant_id' />
                <q-input dense type='number' v-model='p_assignation.p_assigneur_id' label='p_assigneur_id' />
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
import TaskSelect from "components/taskSelect.vue";
export default {
  components: {TaskSelect},
  data () {
    return {
      p_assignation_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
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
  mixins: [basemixin, apimixin],
  created () {
    this.p_assignation_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    taskSelected (task) {
      console.log(`Tâche sélectionnée :`, task)
    },
    update_get (props) {
      this.p_assignation = props
      this.medium2 = true
    },
    setEvent (payload, _name) {
      this.p_assignation[_name] = payload
    },
    handleFile (_name) {
      this.p_assignation[_name] = this.$refs[_name].files[0]
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
