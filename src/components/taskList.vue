<template>

  <q-dialog v-model="openModal">
    <q-card style="width: 700px; max-width: 80vw;">
      <q-bar>
        <q-space />
        <q-btn dense flat icon="close" v-close-popup>
          <q-tooltip class="bg-white text-primary">Close</q-tooltip>
        </q-btn>
      </q-bar>
      <q-card-section>
        <div class="text-h6" v-if="!p_task.id">Ajouter une tâche</div>
        <div class="text-h6" v-if="p_task.id">Modifier une tâche</div>
      </q-card-section>
      <q-card-section>
        <q-form  @submit="p_task_post" class="q-gutter-md">
          <div class="row">
            <div class="col-12">
              <q-input outlined dense v-model='p_task.libelle' label='libelle' />
              <br>
              <q-input outlined dense type='textarea' v-model='p_task.description' label='description' />
              <br>
              <q-input outlined dense v-model='p_task.status' label='status' />
              <br>
              <q-input outlined dense type='number' v-model='p_task.p_projet_id' label='p_projet_id' />
              <br>
              <q-select outlined filled v-model="p_task.p_employe_id" :options="employes" label="Executant"
                        map-options emit-value option-value="id" option-label="nom" />
              <br>
              <q-input outlined dense type='date' v-model='p_task.debut' label='debut' />
              <br>
              <q-input outlined dense type='date' v-model='p_task.fin' label='fin' />
              <br>
              <q-input outlined dense v-model='p_task.tuuid' label='tuuid' />
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <q-btn v-if="!p_task.id" color="primary" label="Valider" type="submit" />
              <q-btn v-if="p_task.id" color="primary" label="Modifier" type="submit" />
            </div>
          </div>
        </q-form>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn flat label="Fermer" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>

<!--  <q-dialog v-model="openAssign">-->
<!--    <q-card style="width: 700px; max-width: 80vw;">-->
<!--      <q-card-section>-->
<!--        <div class="text-h6">Assigner une tâche</div>-->
<!--      </q-card-section>-->
<!--      <q-card-section>-->
<!--        <q-form class="q-gutter-md" @submit="assignationPost()">-->
<!--          <div class="row">-->
<!--            <div class="col-12">-->
<!--              <q-input readonly dense type='number' v-model='p_assignation.p_task_id' label='p_task_id' />-->
<!--              <q-input readonly dense type='number' v-model='p_assignation.p_assigneur_id' label='p_assigneur_id' />-->
<!--              <q-select filled v-model="p_assignation.p_executant_id" :options="employes" label="Executant"-->
<!--                        map-options emit-value option-value="id" option-label="nom"  />-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="row">-->
<!--            <div class="col-12">-->
<!--              <q-btn color="primary" label="Valider" type="submit" />-->
<!--            </div>-->
<!--          </div>-->
<!--        </q-form>-->
<!--      </q-card-section>-->
<!--      <q-card-actions align="right" class="bg-white text-teal">-->
<!--        <q-btn flat label="Fermer" v-close-popup />-->
<!--      </q-card-actions>-->
<!--    </q-card>-->
<!--  </q-dialog>-->

  <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="openModal = true; p_task = {}" />

  <br>

  <q-table title="p_tasks" :rows="tasks" :columns="columns" :filter="filter"
           :pagination="pagination" row-key="name">
    <template v-slot:top="props">
      <div class="col-7 q-table__title">Liste des tâches</div>
      <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
      <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
             @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
    </template>
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td key='libelle' :props='props'> {{props.row.libelle}} </q-td>
        <q-td key='description' :props='props'> {{props.row.description}} </q-td>
        <q-td key='employe' :props='props'> {{props.row.employe}} </q-td>
        <q-td key='status' :props='props'>
          <q-badge class="q-pa-sm" :color="getStatus(props.row.status)" >
            {{props.row.status}}
          </q-badge>
        </q-td>
        <q-td key='progress' :props='props'>
          <q-linear-progress size="25px" :value="props.row.progress/100" color="green-3">
            <div class="absolute-full flex flex-center">
              <q-badge color="white" text-color="green-3" :label="props.row.progress +'%'" />
            </div>
          </q-linear-progress>
        </q-td>
<!--        <q-td key='p_projet_id' :props='props'> {{props.row.p_projet_id}} </q-td>-->
        <q-td key='debut' :props='props'> {{props.row.debut}} </q-td>
        <q-td key='fin' :props='props'> {{props.row.fin}} </q-td>
<!--        <q-td key='tuuid' :props='props'> {{props.row.tuuid}} </q-td>-->
        <q-td key="actions" :props="props">
          <q-btn class="q-mr-xs" size="xs" outline color="secondary"
                 v-on:click="assignationModal(props.row)"
                 icon="people"
                 title="assignation"
          />
          <q-btn class="q-mr-xs" size="xs" color="secondary" v-on:click="p_task = props.row; openModal = true;" icon="edit"></q-btn>
          <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_task_delete(props.row.id)" icon="delete"></q-btn>
        </q-td>
      </q-tr>
    </template>
  </q-table>

</template>

<script>
import $httpService from "boot/httpService";
import basemixin from "pages/basemixin";

export default {

  name: 'taskList',

  // props: ['p_tasks', 'p_task'],
  props: {
    tasks: {type: Array, default: () => [], required: false },
    employes: {type: Array, default: () => [], required: false },
    post: { type: Function, required: false },
    update: { type: Function, required: false },
    delete: { type: Function, required: false },
    // openModal: { type: Boolean, default: false, required: false },
  },
  mixins: [basemixin],

  data () {
    return {
      openModal: false,
      openAssign: false,
      p_task: {},
      p_assignation: {},
      p_tasks: [],
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 },
      columns: [
        { name: 'libelle', align: 'left', label: 'libelle', field: 'libelle', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'employe', align: 'left', label: 'employe', field: 'employe', sortable: true },
        { name: 'status', align: 'left', label: 'status', field: 'status', sortable: true },
        { name: 'progress', align: 'left', label: 'Progress', field: 'progress', sortable: true },
        // { name: 'p_projet_id', align: 'left', label: 'p_projet_id', field: 'p_projet_id', sortable: true },
        { name: 'debut', align: 'left', label: 'debut', field: 'debut', sortable: true },
        { name: 'fin', align: 'left', label: 'fin', field: 'fin', sortable: true },
        // { name: 'tuuid', align: 'left', label: 'tuuid', field: 'tuuid', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
    }
  },

  methods: {
    getStatus(status) {
      if(status === 'ECHEC') return 'red';
      if(status === 'STOPPE') return 'red-2';
      if(status === 'ENATTENTE') return 'grey';
      if(status === 'ENCOURS') return 'green-2';
      if(status === 'TERMINE') return 'green';
    },
    p_task_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_task/' + _id)
        .then((response) => {
          // this.p_task_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_task_post () {
      this.showLoading()
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
          this.p_task_get();
          this.showAlert(response, 'secondary');
          this.hideLoading();
        }).catch(() => { this.hideLoading() })
    },
    assignationModal(item) {
      this.openAssign = true;
      this.p_assignation = item;
      this.p_assignation.p_task_id = this.$route.params.id;
      this.p_assignation.p_assigneur_id = this.currentUser().id;
    },
    assignationPost () {
      this.showLoading()
      this.postApi('/api/post/p_assignation', this.p_assignation)
        .then((response) => {
          this.p_assignation = {}
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
  }

}
</script>
