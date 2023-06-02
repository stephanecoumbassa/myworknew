
<template>
  <q-page padding>

    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" @click="medium2 = true" />
        <br><br>
        <q-table title="employes" :data="employes" :columns="columns" :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des employe</div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen"></q-btn>
          </template>
          <template #body="props">
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
                  <q-item v-close-popup clickable>
                    <q-item-section>
                      <q-item-label @click="update_get(props.row)"> Modifier</q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-item clickable @click="employe_delete(props.row.id)">
                    <q-item-label>Supprimer</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Absences</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Conges</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Salaire</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Prime</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Documents</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Notes</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
                    <q-item-label>Postes</q-item-label>
                  </q-item>
                  <q-item v-close-popup clickable @click="medium3 = true">
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
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">

              <div class="col-6 q-pa-md">
                <q-input v-model='employe.lastname' type='text' label='Nom'/>
                <q-input v-model='employe.firstname' type='text' label='Prenom'/>
                <q-input v-model='employe.telephone' type='text' label='Telephone'/>
                <q-input v-model='employe.indicatif' type='text' label='Indicatif'/>
                <q-input v-model='employe.adress' type='textarea' label='Adresse'/>
                <q-input v-model='employe.sex' type='text' label='Sexe'/>
                <q-input v-model='employe.cnps' type='text' label='CNPS'/>
                <q-input v-model='employe.matricule' type='text' label='Matricule'/>
                <q-input v-model='employe.status_matrimonial' type='text' label='Status Matrimonial'/>
                <q-input v-model='employe.enfants' type='text' label='Nbre Enfants'/>
                <q-input v-model='employe.password' type='password' label='password'/>
                <q-input v-model='employe.cni' type='text' label='CNI'/>
                <q-input v-model='employe.rib' type='text' label='RIB'/>

              </div>

              <div class="col-6 q-pa-md">
                <q-input v-model='employe.pays' type='text' label='pays'/>
                <q-input v-model='employe.ville' type='text' label='ville'/>
                <q-input v-model='employe.datenaissance' type='date' stack-label label='datenaissance'/>
                <q-input v-model='employe.photo' type='file' stack-label label='photo'/>
                <q-input v-model='employe.departement' type='text' label='departement'/>
                <q-input v-model='employe.fonction' type='text' label='fonction'/>
                <q-input v-model='employe.contrat' type='text' label='contrat'/>
                <q-input v-model='employe.dateentree' type='date' stack-label label='dateentree'/>
                <q-input v-model='employe.datesortie' type='date' stack-label label='datesortie'/>
                <q-input v-model='employe.salairebase' type='number' label='salairebase'/>
                <q-input v-model='employe.heuresup' type='text' label='heuresup'/>
                <q-input v-model='employe.superviseur' type='number' label='superviseur'/>
                <q-input v-model='employe.contacturgence' type='text' label='contacturgence'/>
                <q-input v-model='employe.embauche' type='date' stack-label label='embauche'/>
              </div>

            </div>
          </q-form>
        </q-card-section>
        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn color="teal" label="Valider" @click="employe_post()" />
          <q-btn color="teal" label="Modifier" @click="employe_update()" />
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
  name: "EmployePage",
  components: { },
  mixins: [basemixin],
  data () {
    return {
      loading1: false,
      first: null,
      last: null,
      medium2: false,
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
