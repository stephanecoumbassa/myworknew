
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="p_evenements" :rows="p_evenements" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des p_evenement</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key='titre' :props='props'> {{props.row.titre}} </q-td>
              <q-td key='description' :props='props'> {{props.row.description}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_evenement_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_evenement</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense v-model='p_evenement.titre' label='titre' />
                <q-input dense type='textarea' v-model='p_evenement.description' label='description' />

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
      p_evenement_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_evenement: {},
      p_evenements: [],
      columns: [
        { name: 'titre', align: 'left', label: 'titre', field: 'titre', sortable: true },
        { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  mixins: [basemixin],
  created () {
    this.p_evenement_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_evenement = props
      this.medium2 = true
    },
    setEvent (payload, _name) {
      this.p_evenement[_name] = payload
    },
    handleFile (_name) {
      this.p_evenement[_name] = this.$refs[_name].files[0]
    },
    p_evenement_get () {
      $httpService.getApi('/api/get/p_evenement')
        .then((response) => {
          this.p_evenements = response
        })
    },
    onSubmit () {
      if (this.p_evenement.id) {
        this.p_evenement_update()
      } else {
        this.p_evenement_post()
      }
    },
    p_evenement_post () {
      this.showLoading()
      $httpService.postApi('/api/post/p_evenement', this.p_evenement)
        .then((response) => {
          this.p_evenement = {}
          this.p_evenement_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_evenement_update () {
      this.showLoading()
      $httpService.putApi('/api/put/p_evenement', this.p_evenement)
        .then((response) => {
          this.p_evenement_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_evenement_delete (_id) {
      this.showLoading()
      $httpService.deleteApi('/api/delete/p_evenement/' + _id)
        .then((response) => {
          this.p_evenement_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
