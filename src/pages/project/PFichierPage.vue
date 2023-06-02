
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table
title="p_fichiers" :rows="p_fichiers" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des p_fichier</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key='name' :props='props'> {{props.row.name}} </q-td>
              <q-td key='url' :props='props'> {{props.row.url}} </q-td>
              <q-td key='taille' :props='props'> {{props.row.taille}} </q-td>
              <q-td key='p_projet_id' :props='props'> {{props.row.p_projet_id}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="edit" @click="update_get(props.row)"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" icon="delete" @click="p_fichier_delete(props.row.id)"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_fichier</div>
        </q-card-section>
        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-12">
                <q-input v-model='p_fichier.name' dense label='name' />
                <q-input v-model='p_fichier.url' dense label='url' />
                <q-input v-model='p_fichier.taille' dense type='number' label='taille' />
                <q-input v-model='p_fichier.p_projet_id' dense type='number' label='p_projet_id' />

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
      p_fichier: {},
      p_fichiers: [],
      columns: [
        { name: 'name', align: 'left', label: 'name', field: 'name', sortable: true },
        { name: 'url', align: 'left', label: 'url', field: 'url', sortable: true },
        { name: 'taille', align: 'left', label: 'taille', field: 'taille', sortable: true },
        { name: 'p_projet_id', align: 'left', label: 'p_projet_id', field: 'p_projet_id', sortable: true },
        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  created () {
    this.p_fichier_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_fichier = props
      this.medium2 = true
    },
    p_fichier_get () {
      $httpService.getApi('/api/get/p_fichier')
        .then((response) => {
          this.p_fichiers = response
        })
    },
    onSubmit () {
      if (this.p_fichier.id) {
        this.p_fichier_update()
      } else {
        this.p_fichier_post()
      }
    },
    p_fichier_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_fichier', this.p_fichier)
        .then((response) => {
          this.p_fichier = {}
          this.p_fichier_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_fichier_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_fichier', this.p_fichier)
        .then((response) => {
          this.p_fichier_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_fichier_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_fichier/' + _id)
        .then((response) => {
          this.p_fichier_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
