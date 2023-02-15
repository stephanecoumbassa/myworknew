
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="p_employes" :rows="p_employes" :columns="columns" :filter="filter"
                 :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des employés</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key='nom' :props='props'> {{props.row.nom}} </q-td>
              <q-td key='prenom' :props='props'> {{props.row.prenom}} </q-td>
              <q-td key='telephone' :props='props'> {{props.row.telephone}} </q-td>
              <q-td key='email' :props='props'> {{props.row.email}} </q-td>
              <q-td key='cni' :props='props'> {{props.row.cni}} </q-td>
              <q-td key='photo' :props='props'> <img v-if='props.row.photo' width='80' height='80' :src="uploadurl+'/p_employe/'+props.row.photo" :alt='props.row.photo' /> </q-td>
              <q-td key='whatsapp' :props='props'> {{props.row.whatsapp}} </q-td>
              <q-td key='adresse' :props='props'> {{props.row.adresse}} </q-td>
              <q-td key='datenaissance' :props='props'> {{props.row.datenaissance}} </q-td>
              <q-td key='genre' :props='props'> {{props.row.genre}} </q-td>
              <q-td key='banquerib' :props='props'> {{props.row.banquerib}} </q-td>
              <q-td key='banquename' :props='props'> {{props.row.banquename}} </q-td>
              <q-td key='fonction' :props='props'> {{props.row.fonction}} </q-td>
              <q-td key='datearrivee' :props='props'> {{props.row.datearrivee}} </q-td>
              <q-td key='datefin' :props='props'> {{props.row.datefin}} </q-td>
              <q-td key='experience' :props='props'> {{props.row.experience}} </q-td>
              <q-td key='education' :props='props'> {{props.row.education}} </q-td>
              <q-td key='euuid' :props='props'> {{props.row.euuid}} </q-td>

              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="p_employe_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un P_employe</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-12">
                <q-input dense v-model='p_employe.nom' label='nom' />
                <q-input dense v-model='p_employe.prenom' label='prenom' />
                <q-input dense v-model='p_employe.telephone' label='telephone' />
                <q-input dense v-model='p_employe.email' label='email' />
                <q-input dense v-model='p_employe.cni' label='cni' />
                <upload v-model='p_employe.photo' title='photo' @blurevent="setEvent($event, 'photo')" />
                <q-input dense v-model='p_employe.whatsapp' label='whatsapp' />
                <q-input dense type='textarea' v-model='p_employe.adresse' label='adresse' />
                <q-input dense type='date' v-model='p_employe.datenaissance' label='datenaissance' />
                <q-input dense v-model='p_employe.genre' label='genre' />
                <q-input dense v-model='p_employe.banquerib' label='banquerib' />
                <q-input dense v-model='p_employe.banquename' label='banquename' />
                <q-input dense v-model='p_employe.fonction' label='fonction' />
                <q-input dense type='date' v-model='p_employe.datearrivee' label='datearrivee' />
                <q-input dense type='date' v-model='p_employe.datefin' label='datefin' />
                <q-input dense type='textarea' v-model='p_employe.experience' label='experience' />
                <q-input dense type='textarea' v-model='p_employe.education' label='education' />
                <q-input dense v-model='p_employe.euuid' label='euuid' />

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
      p_employe_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      p_employe: {},
      p_employes: [],
      columns: [
        { name: 'nom', align: 'left', label: 'nom', field: 'nom', sortable: true },
        { name: 'prenom', align: 'left', label: 'prenom', field: 'prenom', sortable: true },
        { name: 'telephone', align: 'left', label: 'telephone', field: 'telephone', sortable: true },
        { name: 'email', align: 'left', label: 'email', field: 'email', sortable: true },
        { name: 'cni', align: 'left', label: 'cni', field: 'cni', sortable: true },
        { name: 'photo', align: 'left', label: 'photo', field: 'photo', sortable: true },
        { name: 'whatsapp', align: 'left', label: 'whatsapp', field: 'whatsapp', sortable: true },
        { name: 'adresse', align: 'left', label: 'adresse', field: 'adresse', sortable: true },
        { name: 'datenaissance', align: 'left', label: 'datenaissance', field: 'datenaissance', sortable: true },
        { name: 'genre', align: 'left', label: 'genre', field: 'genre', sortable: true },
        { name: 'banquerib', align: 'left', label: 'banquerib', field: 'banquerib', sortable: true },
        { name: 'banquename', align: 'left', label: 'banquename', field: 'banquename', sortable: true },
        { name: 'fonction', align: 'left', label: 'fonction', field: 'fonction', sortable: true },
        { name: 'datearrivee', align: 'left', label: 'datearrivee', field: 'datearrivee', sortable: true },
        { name: 'datefin', align: 'left', label: 'datefin', field: 'datefin', sortable: true },
        { name: 'experience', align: 'left', label: 'experience', field: 'experience', sortable: true },
        { name: 'education', align: 'left', label: 'education', field: 'education', sortable: true },
        { name: 'euuid', align: 'left', label: 'euuid', field: 'euuid', sortable: true },

        { name: 'actions', align: 'left', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 20 }
    }
  },
  mixins: [basemixin],
  created () {
    this.p_employe_get()
    const date = new Date()
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
  },
  methods: {
    update_get (props) {
      this.p_employe = props
      this.medium2 = true
    },
    setEvent (payload, _name) {
      this.p_employe[_name] = payload
    },
    handleFile (_name) {
      this.p_employe[_name] = this.$refs[_name].files[0]
    },
    p_employe_get () {
      $httpService.getApi('/api/get/p_employe')
        .then((response) => {
          this.p_employes = response
        })
    },
    onSubmit () {
      if (this.p_employe.id) {
        this.p_employe_update()
      } else {
        this.p_employe_post()
      }
    },
    p_employe_post () {
      this.showLoading()
      $httpService.postWithParams('/api/post/p_employe', this.p_employe)
        .then((response) => {
          this.p_employe = {}
          this.p_employe_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_employe_update () {
      this.showLoading()
      $httpService.putWithParams('/api/put/p_employe', this.p_employe)
        .then((response) => {
          this.p_employe_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    },
    p_employe_delete (_id) {
      this.showLoading()
      $httpService.deleteWithParams('/api/delete/p_employe/' + _id)
        .then((response) => {
          this.p_employe_get()
          this.showAlert(response.msg, 'secondary')
          this.hideLoading()
        }).catch(() => { this.hideLoading() })
    }
  }
}
</script>
