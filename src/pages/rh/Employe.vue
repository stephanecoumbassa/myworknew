
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="employes" :data="employes" :columns="columns" :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des employe</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" :class="alerte(props.row)">
              <q-td key='lastname' :props='props'> {{props.row.lastname}} </q-td>
              <q-td key='firstname' :props='props'> {{props.row.firstname}} </q-td>
              <q-td key='telephone' :props='props'> {{props.row.telephone}} </q-td>
              <q-td key='indicatif' :props='props'> {{props.row.indicatif}} </q-td>
              <q-td key='adress' :props='props'> {{props.row.adress}} </q-td>
              <q-td key='sex' :props='props'> {{props.row.sex}} </q-td>
              <q-td key='cnps' :props='props'> {{props.row.cnps}} </q-td>
              <q-td key='matricule' :props='props'> {{props.row.matricule}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn-dropdown size="xs" color="dark">
                  <q-item clickable v-close-popup>
                    <q-item-section>
                      <q-item-label v-on:click="update_get(props.row)"> Modifier</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item clickable v-on:click="employe_delete(props.row.id)">
                    <q-item-label>Supprimer</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Absences</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Conges</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Salaire</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Prime</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Documents</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Notes</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Postes</q-item-label>
                  </q-item>
                  <q-item clickable v-close-popup @click="medium3 = true">
                    <q-item-label>Courrier</q-item-label>
                  </q-item>
                </q-btn-dropdown>
                <!--                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit" />-->
                <!--                <q-btn class="q-mr-xs" size="xs" color="secondary" v-on:click="employe_delete(props.row.id)" icon="photo" />-->
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <q-dialog v-model="medium2" position="top">
      <q-card style="width: 1000px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Nouvel Employe</div>
        </q-card-section>
        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">

              <div class="col-6 q-pa-md">
                <q-input type='text' v-model='employe.lastname' label='Nom'/>
                <q-input type='text' v-model='employe.firstname' label='Prenom'/>
                <q-input type='text' v-model='employe.telephone' label='Telephone'/>
                <q-input type='text' v-model='employe.indicatif' label='Indicatif'/>
                <q-input type='textarea' v-model='employe.adress' label='Adresse'/>
                <q-input type='text' v-model='employe.sex' label='Sexe'/>
                <q-input type='text' v-model='employe.cnps' label='CNPS'/>
                <q-input type='text' v-model='employe.matricule' label='Matricule'/>
                <q-input type='text' v-model='employe.status_matrimonial' label='Status Matrimonial'/>
                <q-input type='text' v-model='employe.enfants' label='Nbre Enfants'/>
                <q-input type='password' v-model='employe.password' label='password'/>
                <q-input type='text' v-model='employe.cni' label='CNI'/>
                <q-input type='text' v-model='employe.rib' label='RIB'/>

              </div>

              <div class="col-6 q-pa-md">
                <q-input type='text' v-model='employe.pays' label='pays'/>
                <q-input type='text' v-model='employe.ville' label='ville'/>
                <q-input type='date' stack-label v-model='employe.datenaissance' label='datenaissance'/>
                <q-input type='file' stack-label v-model='employe.photo' label='photo'/>
                <q-input type='text' v-model='employe.departement' label='departement'/>
                <q-input type='text' v-model='employe.fonction' label='fonction'/>
                <q-input type='text' v-model='employe.contrat' label='contrat'/>
                <q-input type='date' stack-label v-model='employe.dateentree' label='dateentree'/>
                <q-input type='date' stack-label v-model='employe.datesortie' label='datesortie'/>
                <q-input type='number' v-model='employe.salairebase' label='salairebase'/>
                <q-input type='text' v-model='employe.heuresup' label='heuresup'/>
                <q-input type='number' v-model='employe.superviseur' label='superviseur'/>
                <q-input type='text' v-model='employe.contacturgence' label='contacturgence'/>
                <q-input type='date' stack-label v-model='employe.embauche' label='embauche'/>
              </div>

            </div>
          </q-form>
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn color="teal" label="Valider" v-on:click="employe_post()" />
          <q-btn color="teal" label="Modifier" v-on:click="employe_update()" />
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
  name: "EmployePage",
  data () {
    return {
      employe_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      maximizedToggle: true,
      name: null,
      image: null,
      employe: {},
      employes: [],
      columns: [
        { name: 'lastname', align: 'left', label: 'lastname', field: 'lastname', sortable: true },
        { name: 'firstname', align: 'left', label: 'firstname', field: 'firstname', sortable: true },
        { name: 'telephone', align: 'left', label: 'telephone', field: 'telephone', sortable: true },
        { name: 'indicatif', align: 'left', label: 'indicatif', field: 'indicatif', sortable: true },
        { name: 'adress', align: 'left', label: 'adress', field: 'adress', sortable: true },
        { name: 'sex', align: 'left', label: 'sex', field: 'sex', sortable: true },
        { name: 'cnps', align: 'left', label: 'cnps', field: 'cnps', sortable: true },
        { name: 'matricule', align: 'left', label: 'matricule', field: 'matricule', sortable: true },
        // { name: 'status_matrimonial', align: 'left', label: 'status_matrimonial', field: 'status_matrimonial', sortable: true },
        // { name: 'enfants', align: 'left', label: 'enfants', field: 'enfants', sortable: true },
        // { name: 'password', align: 'left', label: 'password', field: 'password', sortable: true },
        // { name: 'pays', align: 'left', label: 'pays', field: 'pays', sortable: true },
        // { name: 'ville', align: 'left', label: 'ville', field: 'ville', sortable: true },
        // { name: 'datenaissance', align: 'left', label: 'datenaissance', field: 'datenaissance', sortable: true },
        // { name: 'photo', align: 'left', label: 'photo', field: 'photo', sortable: true },
        // { name: 'departement', align: 'left', label: 'departement', field: 'departement', sortable: true },
        // { name: 'fonction', align: 'left', label: 'fonction', field: 'fonction', sortable: true },
        // { name: 'contrat', align: 'left', label: 'contrat', field: 'contrat', sortable: true },
        // { name: 'dateentree', align: 'left', label: 'dateentree', field: 'dateentree', sortable: true },
        // { name: 'datesortie', align: 'left', label: 'datesortie', field: 'datesortie', sortable: true },
        // { name: 'salairebase', align: 'left', label: 'salairebase', field: 'salairebase', sortable: true },
        // { name: 'heuresup', align: 'left', label: 'heuresup', field: 'heuresup', sortable: true },
        // { name: 'superviseur', align: 'left', label: 'superviseur', field: 'superviseur', sortable: true },
        // { name: 'contacturgence', align: 'left', label: 'contacturgence', field: 'contacturgence', sortable: true },
        // { name: 'cni', align: 'left', label: 'cni', field: 'cni', sortable: true },
        // { name: 'rib', align: 'left', label: 'rib', field: 'rib', sortable: true },
        // { name: 'embauche', align: 'left', label: 'embauche', field: 'embauche', sortable: true },
        // { name: 'id_magasin', align: 'left', label: 'id_magasin', field: 'id_magasin', sortable: true }
        { name: 'actions', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
    }
  },
  components: { },
  mixins: [basemixin],
  created () {
    this.employe_get();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.employe_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    update_get(props) {
      this.employe = props;
      this.medium2 = true;
    },
    alerte(item) {
      if (item.amount <= item.alert_threshold) {
        return 'bg-blue-grey-3';
      }
    },
    employe_post() {
      this.loading1 = true;
      $httpService.postWithParams('/my/post/employe', this.employe)
        .then((response) => {
          this.employe_get();
          this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
        }).catch(() => { this.loading1 = false; })
    },
    employe_get () {
      $httpService.getWithParams('/my/get/employe')
        .then((response) => {
          this.employes = response;
        })
    },
    employe_update () {
      $httpService.putWithParams('/my/put/employe', this.employe)
        .then((response) => {
          this.employe_get();
          this.$q.notify({
            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
          });
        })
    },
    employe_delete (_id) {
      $httpService.postWithParams('/my/delete/employe', { id: _id })
        .then(() => {
          this.employe_get();
        })
    }
  }
}
</script>
