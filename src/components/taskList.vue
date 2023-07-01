<template>

  <q-dialog v-model="openModal">
    <q-card style="width: 700px; max-width: 80vw;">
      <q-bar>
        <q-space />
        <q-btn v-close-popup dense flat icon="close">
          <q-tooltip class="bg-white text-primary">Close</q-tooltip>
        </q-btn>
      </q-bar>
      <q-card-section>
        <div v-if="!p_task.id" class="text-h6">Ajouter une tâche</div>
        <div v-if="p_task.id" class="text-h6">Modifier une tâche</div>
      </q-card-section>
      <q-card-section>
        <q-form  class="q-gutter-md" @submit="p_task_post">
          <div class="row">
            <div class="col-12">
              <q-input v-model='p_task.libelle' outlined dense label='libelle' />
              <br>
              <q-input v-model='p_task.description' outlined dense type='textarea' label='description' />
              <br>
              <q-select
                v-model='p_task.status'
                :options="['ENATTENTE', 'ENCOURS', 'TERMINE','STOPPE']" dense outlined label='status' />
              <br>
              <q-select
                v-model="p_task.p_employe_id" dense outlined :options="employes" label="Executant"
                map-options emit-value option-value="id" option-label="nom" />
              <br>
              <q-input v-model='p_task.debut' stack-label outlined dense type='date' label='Date de debut' />
              <br>
              <q-input v-model='p_task.fin' stack-label outlined dense type='date' label='Date de fin' />
              <br>
              <q-input v-model='p_task.livraison' stack-label outlined dense type='date' label='Date de livraison' />
              <br>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <q-btn v-if="!p_task.id" color="primary" label="Valider" type="submit" />
              <q-btn v-if="p_task.id" color="primary" label="Modifier" @click="p_task_update(p_task)" />
            </div>
          </div>
        </q-form>
      </q-card-section>
      <q-card-actions align="right">
        <q-btn v-close-popup flat label="Fermer" />
      </q-card-actions>
    </q-card>
  </q-dialog>

  <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="openModal = true; p_task = {}" />

  <br>

  <q-table
    title="p_tasks" :rows="tasks" :columns="columns" :filter="filter"
    :pagination="pagination" row-key="name">
    <template #top="props">
      <div class="col-7 q-table__title">Liste des tâches</div>
      <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
      <q-btn
        flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
        class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
    </template>
    <template #body="props">
      <q-tr :props="props">
        <q-td key='libelle' :props='props'> {{props.row.libelle}} </q-td>
        <q-td key='description' :props='props'> {{props.row.description}} </q-td>
        <q-td key='employe' :props='props'> {{props.row.employe}} </q-td>
        <q-td key='status' :props='props'>
          <q-badge class="q-pa-sm" :color="getStatus(props.row.status)" >
            {{props.row.status}}
          </q-badge>
        </q-td>
        <q-td key='ponctualite' :props='props'>
          <q-btn v-if="props.row.ponctualite === 'RETARD'" outline color="red" size="sm">{{props.row.ponctualite}} </q-btn>
          <q-btn v-if="props.row.ponctualite === 'OK'" outline color="green" size="sm">{{props.row.ponctualite}} </q-btn>
        </q-td>
        <q-td key='progress' :props='props'>
          <q-linear-progress size="25px" :value="props.row.progress/100" color="green-3">
            <div class="absolute-full flex flex-center">
              <q-badge color="white" text-color="green-3" :label="props.row.progress +'%'" />
            </div>
          </q-linear-progress>
        </q-td>
        <q-td key='debut' :props='props'> {{props.row.debut}} </q-td>
        <q-td key='fin' :props='props'> {{props.row.fin}} </q-td>
        <q-td key="actions" :props="props">
          <q-btn
            class="q-mr-xs" size="xs" outline color="secondary"
            icon="people"
            title="assignation"
            @click="assignationModal(props.row)"
          />
          <q-btn class="q-mr-xs" size="xs" color="secondary" icon="edit" @click="p_task = props.row; openModal = true;"></q-btn>
          <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_task_delete(props.row.id)"></q-btn>
        </q-td>
      </q-tr>
    </template>
  </q-table>

</template>

<script>
import $httpService from "boot/httpService";
import basemixin from "pages/basemixin";

export default {

  name: 'TaskList',
  mixins: [basemixin],
  emits: ['reload'],
  props: {
    tasks: {type: Array, default: () => [], required: false },
    employes: {type: Array, default: () => [], required: false },
  },
  data () {
    return {
      openModal: false,
      openAssign: false,
      p_task: {},
      p_assignation: {},
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 },
      columns: [
        { name: 'libelle', align: 'left', label: 'libelle', field: 'libelle', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },
        { name: 'employe', align: 'left', label: 'employe', field: 'employe', sortable: true },
        { name: 'status', align: 'left', label: 'status', field: 'status', sortable: true },
        { name: 'ponctualite', align: 'left', label: 'Ponctualite', field: 'ponctualite', sortable: true },
        { name: 'progress', align: 'left', label: 'Progress', field: 'progress', sortable: true },
        { name: 'debut', align: 'left', label: 'debut', field: 'debut', sortable: true },
        { name: 'fin', align: 'left', label: 'fin', field: 'fin', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
    }
  },

  methods: {
    getStatus(status) {
      if(status === 'ECHEC') return 'red';
      if(status === 'STOPPE') return 'red-2';
      if(status === 'ENATTENTE') return 'grey';
      if(status === 'ENCOURS') return 'green-3';
      if(status === 'TERMINE') return 'green';
    },
    p_task_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_task/' + _id)
        .then((response) => {
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_task_post () {
      this.p_task.p_projet_id = this.project_id;
      this.p_task.tuuid = this.generateUuid();
      this.showLoading()
      $httpService.postWithParams('/api/post/p_task', this.p_task)
        .then((response) => {
          this.p_task = {}
          this.showAlert(response, 'secondary')
          this.hideLoading()
          this.$emit('reload', true)
        }).catch(() => { this.hideLoading() })
    },
    p_task_update (__task) {
      this.showLoading()
      $httpService.putWithParams('/api/put/p_task', __task)
        .then((response) => {
          this.showAlert(response, 'secondary');
          this.hideLoading();
          this.$emit('reload', true)
        }).catch(() => { this.hideLoading() })
    },
    assignationModal(item) {
      this.openAssign = true;
      this.p_assignation = item;
      this.p_assignation.p_task_id = this.$route.params.id;
      this.p_assignation.p_assigneur_id = this.currentUser().id;
    }
  }

}
</script>
