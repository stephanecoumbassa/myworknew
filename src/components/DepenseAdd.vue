<template>
  <q-card flat style="width: 700px; max-width: 80vw;">
    <q-card-section>
      <div class="text-h6">Ajouter une depense</div>
    </q-card-section>

    <q-card-section>
      <div class="row justify-center">
        <div class="col-10">
          <q-form  class="q-gutter-md" @submit="onSubmit" @reset="onReset"  >
            <q-input
v-model="mydepense.name" outlined dense autocomplete label="Titre *" hint="Titre"
                     lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
            <q-input v-model="mydepense.description" outlined dense autocomplet type="textarea" label="Description *" />
            <q-input v-model="mydepense.price" outlined dense  autocomplete label="Prix Prestation *" />
            <q-input
v-model="mydepense.date" outlined dense autocomplete type="date" label="Date *"
                     lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />
            <q-input v-model="mydepense.code_comptable" outlined dense type="text"  label="code comptable"></q-input>
            <q-input v-model="mydepense.client" outlined dense type="text"  label="Beneficiaire *"></q-input>
            <q-input v-model="mydepense.telephone" outlined dense type="text"  label="Telephone *"></q-input>
            <q-input v-model="mydepense.email" outlined dense type="email"  label="Email *"></q-input>
<!--            {{depense.projetid}}-->
            <q-select
v-model="mydepense.projetid" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                      :dense="true" :options="p_projets" label="Projets" :option-value="'id'" :option-label="'titre'"
                      input-debounce="0" />
            <div>
              <q-btn v-if="status_update" label="Modifier" type="button" color="secondary" @click="depense_update()"/>
              <q-btn v-if="!status_update" label="Valider"  type="submit" color="secondary"/>
            </div>
          </q-form>

        </div>
      </div>
    </q-card-section>

    <q-card-actions align="right" class="bg-white text-teal">
      <q-btn v-close-popup flat label="Fermer" />
    </q-card-actions>
  </q-card>
</template>

<script>
import $httpService from '../boot/httpService';
// import vue3JsonExcel from 'vue3-json-excel';
import basemixin from '../pages/basemixin'
export default {
  name: 'DepenseAdd',
  components: {
  },
  mixins: [basemixin],
  props: {
    depense: {type: String, default: ()  => {}}
  },
  emits: ['reload'],
  data () {
    return {
      mydepense: {},
      date: '2020-03-10',
      name: null,
      status_update: false,
      data_status: false,
      data: [],
      p_projets: [],
    }
  },
  created () {
    var today = new Date();
    this.mydepense = this.depense;
    this.mydepense.date = this.formatDate(today);
    this.date = this.formatDate(today);
    this.p_projet_get();
    this.loadData();
  },
  methods: {
    p_projet_get () {
      $httpService.getApi('/my/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },
    loadData () {
      $httpService.getWithParams('/my/get/depenses')
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
        this.depense_register();
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
    depense_register () {
      $httpService.postWithParams('/my/post/depenses', this.depense)
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
          this.$emit('reload', true);
          this.loadData();
        }).catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    },
    depense_update () {
      $httpService.postWithParams('/my/put/depenses', this.depense)
        .then((response) => {
          this.$q.notify({
            color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
          });
          this.$emit('reload', true);
          this.loadData();
        }).catch(() => {
          this.$q.notify({
            color: 'negative', position: 'top', message: 'Loading failed', icon: 'report_problem'
          });
        });
    }
  }
}
</script>
