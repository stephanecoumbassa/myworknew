<template>
  <div class="q-pa-md q-ma-md">
    <!--grid-->
    <q-btn label="Ajouter" class="q-ma-md" size="sm" icon="add" color="secondary" v-on:click="medium = true" />

    <q-table title="Treats" :data="data" :columns="columns" row-key="name" class="q-pa-md q-ma-md"
             :selected="selected" :pagination="pagination">
      <template v-slot:top="props">
        <div class="col-4 q-table__title">Liste des utilisateurs</div>
        <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen" class="q-ml-md" />
      </template>
      <template v-slot:body="props">
        <!--        {{props.row}}-->
        <!--        {{props.row.type_users_id}}-->
        <q-tr :props="props">
          <q-td key="id" :props="props"> {{props.row.id}} </q-td>
          <q-td key="name" :props="props"> {{props.row.name}} </q-td>
          <q-td key="last_name" :props="props"> {{props.row.last_name}} </q-td>
          <q-td key="email" :props="props"> {{props.row.email}} </q-td>
          <q-td key="telephone" :props="props"> +{{props.row.telephone_code}} {{props.row.telephone}} </q-td>
          <q-td key="type_users_id" :props="props"> {{props.row.type_users_id}} {{props.row.id}} </q-td>
          <q-td key="actions" :props="props">
            <q-btn class="q-mr-xs" size="xs" color="secondary" v-on:click="update(props.row)" icon="edit" />
            <q-btn class="q-mr-xs" size="xs" color="dark" icon="lock" />
            <!--<q-btn v-if="role == 1" class="q-mr-xs" size="xs" color="red" icon="delete"></q-btn>-->
          </q-td>
        </q-tr>
      </template>
    </q-table>
    <!--    <q-page-sticky position="bottom-right" :offset="[18, 18]">-->
    <!--    </q-page-sticky>-->
    <q-dialog v-model="medium">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Nouvel utilisateur</div>
        </q-card-section>

        <q-card-section>
          <div class="row justify-center">
            <!--      <div class="col-2">1</div>-->
            <div class="col-10">

              <q-form  @submit="onSubmit" @reset="onReset" class="q-gutter-md"  >
                <q-select filled v-model="model" :options="options" label="Type d'utilisateur" option-value="id" stack-label input-debounce="0"
                          option-label="name"></q-select>
                <q-input autocomplete v-model="nom" label="Nom *" hint="Nom"
                         lazy-rules :rules="[ val => val && val.length > 0 || 'Please type something']" />
                <q-input autocomplete  v-model="prenom" label="Prenom *" hint="Prenom"
                         lazy-rules :rules="[ val => val && val.length > 0 || 'Please type something']" />
                <q-input type="text" v-model="telephone_code"  label="indicatif *" hint="Indicatif"></q-input>
                <q-input type="text" v-model="telephone"  label="telephone *" hint="Telephone"></q-input>
                <q-input type="email" v-model="email"  label="Email *" hint="Email"></q-input>
                <q-input type="password" v-model="password"
                         lazy-rules :rules="[ val => val && val.length >= 4 || 'Mot de passe doit etre 4 caracteres minimum']" label="Mot de passe *" hint="Email"></q-input>

                <div>
                  <q-btn label="Valider" type="submit" color="primary"/>
                  <q-btn label="Annuler" type="reset" color="red" flat class="q-ml-sm" />
                </div>
              </q-form>

            </div>
            <!--      <div class="col-2">1</div>-->
          </div>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Fermer" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </div>
</template>

<script>
import $httpService from '../boot/httpService';
export default {
  name: 'UtilisateurPage',
  data () {
        return {
            selected: [],
            options: [],
            nom: null,
            prenom: null,
            password: null,
            adresse: null,
            image: 'hhghjj',
            pays: null,
            email: null,
            telephone_code: null,
            telephone: null,
            model: null,
            filter: '',
            medium: false,
            loading: false,
            visibleColumns: ['email', 'phoneNumber', 'type'],
            data_status: false,
            pagination: {
                sortBy: 'name',
                descending: false,
                page: 1,
                rowsPerPage: 10
            },
            columns: [
                { name: 'id', required: true, label: 'ID', align: 'right', field: 'id', sortable: true },
                { name: 'name', align: 'right', label: 'Nom', field: 'name', sortable: true },
                { name: 'last_name', align: 'right', required: true, label: 'Prenom', field: 'last_name', sortable: true },
                { name: 'email', align: 'right', label: 'Email', field: 'email', sortable: true },
                { name: 'telephone', align: 'right', label: 'Telephone', field: 'telephone', sortable: true },
                { name: 'type_users_id', align: 'right', label: 'Type', field: 'type_users_id', sortable: true },
                { name: 'actions', align: 'right', label: 'Actions' }
            ],
            data: []
        }
    },
    mounted () {
        // get initial data from server (1st page)
        // this.loadData();
    },
    created () {
        this.loadData();
        this.loadData2();
    },
    watch: {
    },
    methods: {
        loadData () {
            $httpService.getWithParams('/my/get/users')
                .then((response) => {
                    this.data = response;
                    this.data_status = true;
                })
                .catch(() => {
                    this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
                });
        },
        onSubmit () {
            if (this.accept !== true) {
                this.user_register();
            } else {
                this.$q.notify({
                    color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted', splitterModel: 20, model: null
                });
            }
        },
        onReset () {
            this.name = null;
            this.age = null;
            this.accept = false;
        },

        loadData2 () {
            $httpService.getWithParams('/api/s_type_users')
                .then((response) => {
                    this.options = response;
                })
                .catch(() => {
                    this.$q.notify({
                        color: 'negative', position: 'top', message: 'Connection impossible', icon: 'report_problem'
                    });
                });
        },
        user_register () {
            var params = {
                name: this.nom,
                lastname: this.prenom,
                telephone: this.telephone,
                telephone_code: this.telephone_code,
                email: this.email,
                password: this.password,
                type: this.model
            };
            $httpService.postWithParams('/my/inscription', params)
                .then((response) => {
                    this.$q.notify({
                        color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
                    });
                    this.loadData();
                })
                .catch(() => {
                    this.$q.notify({
                        color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
                    });
                });
        },
        update(params) {
            $httpService.putWithParams('/my/put/user', params)
                .then((response) => {
                    this.$q.notify({
                        color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
                    });
                    this.loadData();
                })
                .catch(() => {
                    this.$q.notify({
                        color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
                    });
                });
        },
        delete(_id) {
            var params = { id: _id };
            $httpService.postWithParams('/my/delete/user', params)
                .then((response) => {
                    this.$q.notify({
                        color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
                    });
                    this.loadData();
                })
                .catch(() => {
                    this.$q.notify({
                        color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
                    });
                });
        }
    }

}
</script>

<style>
</style>
