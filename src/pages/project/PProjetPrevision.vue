<template>
  <q-page>

    <div class="row justify-center text-center q-pa-sm">

      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <div class="text-center text-h6">Prévision par mois ou sur une période donnée</div>
              <br>
              <div class="row">
                <div class="col-4 q-pa-sm">
                  <q-input v-model="date" type="month" placeholder="Mois" :dense="true" hint="Mois" @change="p_projet_previson_get(date)" />
                </div>
              </div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>


    <div class="row justify-center q-pa-sm">
      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md">

        <q-table id="printMe" title="Produits" :rows="p_projections" :columns="columns" :pagination="pagination" :filter="filter" row-key="name" dense>
          <template #top="props">
            <div class="col-7 q-table__title">Liste des projets
            </div>
            <div class="row q-pa-lg">
              <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(p_projections, 'projection')"/>
              <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md" />
              <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" class="q-ml-md" @click="props.toggleFullscreen" />
            </div>
            <br>
          </template>
          <template #body="props">
            <q-tr :key="props.row.id" :props="props" v-bind="props.row.qty = 0">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="titre" :props="props" style="min-width: 150px" >
                {{props.row.titre}} <br>
                Debut: {{props.row.datedebut}} <br>
                Fin: {{props.row.datefin}} <br>
              </q-td>
              <q-td key="retard" :props="props">
                <q-btn
v-if="props.row.date_prevision < props.row.date_effective" outline size="sm"
                       style="color: #bd3156" label="Mauvais" />
                <q-btn v-if="props.row.date_prevision >= props.row.date_effective" outline size="sm" style="color: #31bd8c" label="Bon" />
              </q-td>
              <q-td key="prix_unitaire" :props="props"> {{props.row.prix_unitaire}} </q-td>
              <q-td key="montant_ht" :props="props"> {{props.row.montant_ht}} </q-td>
              <q-td key="qte" :props="props"> {{props.row.qte}} </q-td>
              <q-td key="dejalivre" :props="props"> {{props.row.livree}} </q-td>
              <q-td key="reste" :props="props"> {{props.row.qte - props.row.livree}} </q-td>
              <q-td key="qte_prevision" :props="props"> <q-input v-model="props.row.qte_prevision" dense type="number" style="width: 70px" /> </q-td>
              <q-td key="date_prevision" :props="props">
                <q-input v-model="props.row.date_prevision" dense type="date" style="width:100px" />
              </q-td>
              <q-td key="qte_effective" :props="props"> <q-input v-model="props.row.qte_effective" dense type="number" style="width: 70px"  /> </q-td>
              <q-td key="date_effective" :props="props" class="no-margin no-padding">
                <q-input v-model="props.row.date_effective" dense type="date" style="width: 100px" />
              </q-td>
              <q-td key="actions" :props="props">
                <q-btn
class="q-mr-xs" size="sm" color="danger" icon="delete_forever" outline
                       @click="p_projet_previson_remove(props.row.id)"  />
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>


    <div class="row justify-center text-center q-pa-sm">

      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="q-pa-lg text-right">

          <!--          <div class="text-right text-h6">Actions</div>-->
          <!--          <br>-->
          <div class="row">
            <div class="col-4 q-pa-sm"></div>
            <div class="col-4 q-pa-sm"></div>
            <div class="col-4 q-pa-sm">
              <q-btn size="md" icon="done_all" color="primary" type="button" label="Valider" @click="previson_post(p_projections)" />
              <!--              <q-input type="year" v-model="date" placeholder="Year" :dense="true" @change="stock_get()" hint="Année" />-->
            </div>
          </div>

        </q-card>
      </div>

    </div>


    <br>

  </q-page>
</template>

<script>
import $httpService from 'boot/httpService';
import basemixin from '../basemixin';
import { v4 as uuidv4 } from 'uuid';
export default {
  name: 'PProjetPrevisionPage',
  mixins: [basemixin],
  data () {
    return {
      products: [],
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'titre', align: 'left', label: 'titre' },
        { name: 'retard', field: 'retard', label: 'Retard' },
        { name: 'prix_unitaire', field: 'prix_unitaire', label: 'P.Unit' },
        { name: 'montant_ht', field: 'montant_ht', label: 'HT' },
        { name: 'qte', align: 'left', label: 'Qté', field: 'qte', sortable: true },
        { name: 'dejalivre', field: 'dejalivre', label: 'Deja Liv' },
        { name: 'reste', field: 'reste', label: 'Reste' },
        { name: 'qte_prevision', field: 'qte_prevision', label: 'Qté prév' },
        { name: 'date_prevision', field: 'dateprevision', label: 'Dte prév' },
        { name: 'qte_effective', field: 'qte_effective', label: 'Qté Eff.' },
        { name: 'date_effective', field: 'date_effective', label: 'Date Eff.' },
        { name: 'actions', label: 'Actions' }
      ],
      p_projets: [],
      p_projections: [],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  mounted () {
    this.stock_get();
    this.p_projet_get();
    this.date = this.year + '-'+this.month;
    this.p_projet_previson_get(this.date);
  },
  methods: {
    p_projet_get () {
      $httpService.getApi('/my/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },
    p_projet_previson_remove(_id) {
      this.p_projections = this.p_projections.filter(x => {
        return x.id !== _id
      })
    },
    p_projet_previson_get (date) {
      this.year = date.split('-')[0];
      this.month = date.split('-')[1];
      $httpService.getApi('/my/get/p_projet_previson?mois='+this.month+'&annee='+this.year)
        .then((response) => {
          this.p_projections = response['data'];
          this.$q.notify(response['msg']);
        })
    },
    stock_get () {
      $httpService.getWithParams('/my/get/stock/' + this.date)
        .then((response) => {
          this.products = response;
        })
    },
    previson_post(stock) {
      stock.forEach((x) => {
        if(!x.puuid) {
          x.puuid = uuidv4();
        }
        if(x.project_id) {
          x.project_id = x.project_id;
        } else {
          x.project_id = x.id;
        }
        x.annee = this.year;
        x.mois = this.month;
        x.dejalivre = Number(x.livree);
      });
      if (confirm("Voulez vous valider ?")) {
        $httpService.postWithParams('/my/post/p_projet_previson', stock)
          .then((response) => {
            this.$q.notify({
              color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
            });
            this.p_projet_previson_get(this.date);
          });
      }
    },
  }
}
</script>

<style>
</style>
