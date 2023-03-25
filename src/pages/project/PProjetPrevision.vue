<template>
  <q-page>

    <div class="row justify-center text-center q-pa-md">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
        <q-card class="text-center justify-center content-center">
          <q-item clickable>
            <q-card-section>
              <div class="text-center text-h6">Prévision par mois ou sur une période donnée</div>
              <br>
              <div class="row">
                <div class="col-4 q-pa-sm">
                  <q-input type="month" v-model="date" placeholder="Mois" :dense="true" @change="p_projet_previson_get(date)" hint="Mois" />
                </div>
<!--                <div class="col-4 q-pa-sm"><br>OU</div>-->
<!--                <div class="col-4 q-pa-sm">-->
<!--                  <q-input type="year" v-model="date" placeholder="Year" :dense="true" @change="stock_get()" hint="Année" />-->
<!--                </div>-->
              </div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>


    <div class="row justify-center q-pa-md">
      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md">

        <q-table title="Produits" :rows="p_projections" :columns="columns" :pagination="pagination" :filter="filter" row-key="name" dense>
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des projets
            </div>
            <div class="row">
              <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            </div>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" :key="props.row.id" v-bind="props.row.qty = 0">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="titre" :props="props" style="min-width: 200px" >
                {{props.row.titre}} <br>
                Debut: {{props.row.datedebut}} <br>
                Fin: {{props.row.datefin}} <br>
              </q-td>
              <!--              <q-td key="datedebut" :props="props"> {{props.row.datedebut}} </q-td>-->
              <!--              <q-td key="datefin" :props="props"> {{props.row.datefin}} </q-td>-->
              <q-td key="retard" :props="props">
<!--                <q-btn outline size="sm" v-if="props.row.datefin <= today" style="color: #FF0080" label="Retard" />-->
<!--                <q-btn outline size="sm" v-if="props.row.datefin > today" style="color: #31bd8c" label="Succès" />-->
<!--                {{props.row.date_prevision}}<br>-->
<!--                {{props.row.date_effective}}-->
                <q-btn outline size="sm" v-if="props.row.date_prevision < props.row.date_effective"
                       style="color: #bd3156" label="Mauvais" />
                <q-btn outline size="sm" v-if="props.row.date_prevision >= props.row.date_effective" style="color: #31bd8c" label="Bon" />
              </q-td>
              <q-td key="prix_unitaire" :props="props"> {{props.row.prix_unitaire}} </q-td>
              <q-td key="montant_ht" :props="props"> {{props.row.montant_ht}} </q-td>
              <q-td key="qte" :props="props"> {{props.row.qte}} </q-td>
<!--              <q-td key="dejalivre" :props="props"> {{props.row.dejalivre}} </q-td>-->
              <q-td key="dejalivre" :props="props"> {{props.row.livree}} </q-td>
<!--              <q-td key="reste" :props="props"> {{props.row.reste}} </q-td>-->
              <q-td key="reste" :props="props"> {{props.row.qte - props.row.livree}} </q-td>

              <q-td key="qte_prevision" :props="props"> <q-input dense type="number" v-model="props.row.qte_prevision" style="width: 70px" /> </q-td>
              <q-td key="date_prevision" :props="props"> <q-input dense type="date" v-model="props.row.date_prevision" /> </q-td>
              <q-td key="qte_effective" :props="props"> <q-input dense type="number" v-model="props.row.qte_effective" style="width: 70px"  /> </q-td>
              <q-td key="date_effective" :props="props"> <q-input dense type="date" v-model="props.row.date_effective" /> </q-td>
              <q-td key="status_effective" :props="props"> {{props.row.status_effective}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="sm" color="secondary" v-on:click="stock_post(props.row)" icon="verified" />
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>


    <div class="row justify-center text-center q-pa-md">

      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md text-center">
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
import {p_task_get} from "src/services/api/rh.api";
export default {
  name: 'ProduitStockPage',
  data () {
    return {
      tab: 'mails',
      product_id: 1,
      alert: false,
      loading1: false,
      date: '2023',
      red: '#6d1412',
      first: null,
      inventaire: null,
      inventaires: [],
      lists: [],
      lists_products: [],
      name: null,
      last: null,
      entreprise: {},
      search: '',
      maximizedToggle: true,
      products: [],
      product: { description: '', stock: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'titre', align: 'left', label: 'titre' },
        // { name: 'datedebut', field: 'datedebut', label: 'Début' },
        // { name: 'datefin', field: 'datefin', label: 'Fin' },
        { name: 'retard', field: 'retard', label: 'Retard' },
        { name: 'prix_unitaire', field: 'prix_unitaire', label: 'P.Unit' },
        { name: 'montant_ht', field: 'montant_ht', label: 'HT' },
        { name: 'qte', align: 'left', label: 'Qté', field: 'qte', sortable: true },
        { name: 'dejalivre', field: 'dejalivre', label: 'Deja Livre' },
        { name: 'reste', field: 'reste', label: 'Reste' },
        { name: 'qte_prevision', field: 'qte_prevision', label: 'Qté prévision' },
        { name: 'date_prevision', field: 'dateprevision', label: 'Dte prévision' },
        { name: 'qte_effective', field: 'qte_effective', label: 'Qté Eff.' },
        { name: 'date_effective', field: 'date_effective', label: 'Date Eff.' },
        { name: 'status_effective', field: 'status_effective', label: 'Status Eff.' },
        // { name: 'difference', label: 'Diff' },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
      p_tasks: [],
      p_projets: [],
      p_projections: [],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  mixins: [basemixin],
  mounted () {
    this.stock_get();
    this.p_projet_get();
    this.p_projet_previson_get();
    // this.inventaires_get();
    this.date = this.year + '-'+this.month;
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
  },
  methods: {
    p_projet_get () {
      $httpService.getApi('/api/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },
    p_projet_previson_get (date='2023-03') {
      let year = date.split('-')[0];
      this.year = date.split('-')[0];
      let month = date.split('-')[1];
      this.month = date.split('-')[1];
      $httpService.getApi('/my/get/p_projet_previson?mois='+month+'&annee='+year)
        .then((response) => {
          this.p_projections = response['data'];
          this.$q.notify(response['msg']);
        })
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
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
       x.puuid = uuidv4();
       x.annee = this.year;
       x.mois = this.month;
       x.project_id = x.id;
       x.dejalivre = Number(x.livree);
      })
      if (confirm("Voulez vous valider ?")) {
        $httpService.postWithParams('/my/post/p_projet_previson', stock)
          .then((response) => {
            console.log(response);
            // this.$q.notify({
            //   color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
            // });
            // this.products_get();
          });
      }
    },
  }
}
</script>

<style>
</style>
