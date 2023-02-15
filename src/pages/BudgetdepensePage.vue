
<template>
    <q-page padding>
   
    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="budgetdepenses" :rows="budgetdepenses" :columns="columns" :filter="filter"
                :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des budgetdepense</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
               <q-td key='montant' :props='props'> {{props.row.montant}} </q-td> 
  <q-td key='mois' :props='props'> {{props.row.mois}} </q-td> 
  <q-td key='annee' :props='props'> {{props.row.annee}} </q-td> 
  <q-td key='description' :props='props'> {{props.row.description}} </q-td> 
 
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="budgetdepense_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
    <q-dialog v-model="medium2">
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6">Ajouter un Budgetdepense</div>
            </q-card-section>
            <q-card-section>
              <q-form  @submit="onSubmit" class="q-gutter-md">
                <div class="row">
                  <div class="col-12">
                    <q-input dense type='number' v-model='budgetdepense.montant' label='montant' /> 
  <q-input dense type='number' v-model='budgetdepense.mois' label='mois' /> 
  <q-input dense type='number' v-model='budgetdepense.annee' label='annee' /> 
  <q-input dense type='textarea' v-model='budgetdepense.description' label='description' /> 
 
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
  import $httpService from '../services/httpService'
  import basemixin from '../services/basemixin'
  export default {
    data () {
      return {
        budgetdepense_id: 1,
        loading1: false,
        red: '#6d1412',
        first: null,
        last: null,
        medium: false,
        medium2: false,
        maximizedToggle: true,
        name: null,
        image: null,
        budgetdepense: {},
        budgetdepenses: [],
        columns: [
             { name: 'montant', align: 'left', label: 'montant', field: 'montant', sortable: true }, 
  { name: 'mois', align: 'left', label: 'mois', field: 'mois', sortable: true }, 
  { name: 'annee', align: 'left', label: 'annee', field: 'annee', sortable: true }, 
  { name: 'description', align: 'left', label: 'description', field: 'description', sortable: true }, 
 
            { name: 'actions', align: 'left', label: 'Actions' }
        ],
        filter: '',
        pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
      }
    },
    mixins: [basemixin],
    created () {
      this.budgetdepense_get()
      const date = new Date()
      this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
      this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
    },
    methods: {
      update_get (props) {
        this.budgetdepense = props
        this.medium2 = true
      },
      setEvent (payload, _name) {
          this.budgetdepense[_name] = payload
      },
      handleFile (_name) {
          this.budgetdepense[_name] = this.$refs[_name].files[0]
      },
      budgetdepense_get () {
        $httpService.getApi('/api/get/budgetdepense')
          .then((response) => {
            this.budgetdepenses = response
          })
      },
      onSubmit () {
          if (this.budgetdepense.id) {
            this.budgetdepense_update()
          } else {
            this.budgetdepense_post()
          }
        },
      budgetdepense_post () {
        this.showLoading()
        $httpService.postApi('/api/post/budgetdepense', this.budgetdepense)
          .then((response) => {
              this.budgetdepense = {}
            this.budgetdepense_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      },
      budgetdepense_update () {
          this.showLoading()
        $httpService.putApi('/api/put/budgetdepense', this.budgetdepense)
          .then((response) => {
            this.budgetdepense_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      },
      budgetdepense_delete (_id) {
          this.showLoading()
        $httpService.deleteApi('/api/delete/budgetdepense/' + _id)
          .then((response) => {
            this.budgetdepense_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      }
    }
  }
</script>
