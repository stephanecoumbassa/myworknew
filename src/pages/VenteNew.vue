<template>

  <q-page >
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-12 col-12">

        <q-tabs
          v-model="tab" dense class="text-dark"
          active-color="secondary" indicator-color="secondary" align="justify" narrow-indicator>
          <q-tab name="mails" label="Listes des ventes" />
          <q-tab name="alarms" label="Nouvelle vente" @click="products = [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }]" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>

          <q-tab-panel name="mails">

            <div class="row q-pa-sm print-hide">
              <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
              <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
              <div class="col q-pa-sm"><br><q-btn color="dark" label="filtrer" @click="sales_stats_get()" /></div>
            </div>

            <q-table
              id="printMe" title="Listes de ventes" :grid="grid" :rows="sales_list" :columns="columns"
              :pagination="pagination" :filter="filter" flat>
              <template #top="props">
                <div class="col-4 q-table__title">Liste des ventes</div>&nbsp;&nbsp;&nbsp;
                <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
                <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(sales_list, 'vente')"/>
                <q-btn v-print="'#printMe'" flat round dense icon="print" class="q-ml-md" />
                <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" class="q-ml-md" @click="props.toggleFullscreen" />
              </template>
              <template #body="props">
                <q-tr :props="props">
                  <q-td key="id" :props="props">{{ props.row.id }}</q-td>
                  <q-td key="p_name" :props="props">{{ props.row.p_name }}</q-td>
                  <q-td key="dateposted" :props="props"> {{ dateformat(props.row.dateposted, 3) }}</q-td>
                  <q-td key="quantite_vendu" :props="props"> {{ numerique(parseInt(props.row.quantite_vendu)) }}</q-td>
                  <q-td key="prix_unitaire" :props="props"> {{ numerique(props.row.prix_unitaire) }}</q-td>
                  <q-td key="tva" :props="props"> {{ numerique(props.row.tva) }}</q-td>
                  <q-td key="total" :props="props"> {{ numerique(props.row.total) }}</q-td>
                  <q-td key="a_name" :props="props"> {{ props.row.a_name }} {{ props.row.a_last_name }}</q-td>
                  <q-td key="id_vente" :props="props"> {{ props.row.id_vente }} </q-td>
                  <q-td key="actions" :props="props">
                    <q-btn class="q-ma-xs" size="xs" color="dark" icon="receipt" @click=" factures_get_id(props.row.id_vente); facture_number = props.row.id_vente; facture_status2 = true" />
                  </q-td>
                </q-tr>
              </template>
              <template #item="props">
                <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
                  <q-card :class="props.selected ? 'bg-grey-2' : ''">
                    <q-list v-for="col in props.cols.filter(col => col.name !== 'desc')" :key="col.name" dense>
                      <q-item v-if="col.status !== false">
                        <q-item-section>
                          <q-item-label caption>{{ col.label }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                          <q-item-label >{{ col.value }}</q-item-label>
                          <q-item-label v-if="col.label == 'Actions'">
                            <q-btn class="q-ma-xs" size="xs" color="secondary" icon="visibility" @click="factures_get_id(props.id_vente); factures_get_credit(props.id_vente)" />
                            <q-btn class="q-ma-xs" size="xs" color="red" icon="delete" />
                          </q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-card>
                </div>
              </template>
            </q-table>


          </q-tab-panel>

          <q-tab-panel name="alarms">

            <VenteNewComponent @reload="reload" />

          </q-tab-panel>
        </q-tab-panels>

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture
              name="Facture de vente" :myentreprise="entreprise"
              :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>
      </div>
    </div>
    <br>

  </q-page>

</template>

<script>

import * as _ from 'lodash';
import FactureComponent from '../components/facture_component.vue';
import apimixin from "src/services/apimixin";
import basemixin from './basemixin';
import VenteNewComponent from "components/VenteNewComponent.vue";
export default {
  components: {
    VenteNewComponent,
    'facture': FactureComponent
  },
  mixins: [basemixin, apimixin],
  data () {
    return {
      tab: 'mails',
      filter: '',
      last: 30,
      grid: false,
      facture_status2: false,
      status_download: true,
      facture_number: null,
      versements: [],
      dateposted: '',
      first: '',
      clientId: 1,
      client: 1,
      products: [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }],
      sales_list: [],
      appro_list: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      appro_list2: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true, status: false },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true, status: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'quantite_vendu', align: 'left', label: 'Qte', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', align: 'left', label: 'Prix', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'tva', align: 'left', label: 'TVA', field: 'tva', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'a_name', align: 'left', label: 'Agent', field: 'a_name', sortable: true },
        { name: 'id_vente', align: 'left', label: 'Facture ID', field: 'id_vente', sortable: true },
        { name: 'actions', label: 'Actions', classes: 'print-hide', headerClasses: 'print-hide' }
      ],
      entreprise: {},
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 50
      }
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity /100)), 0);
    }
  },
  created () {
    let date = new Date();
    this.products_get();
    this.sales_get();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    this.dateposted = date.toISOString().slice(0, 16);
  },
  methods: {
    reload($event) {
      console.log($event)
      this.sales_get();
    },
    products_get () {
      this.getApi('/my/get/products').then((res) => {
        var products = res;
        this.getApi('/my/client/s_product_prix/'+this.clientId)
          .then((response) => {
            var products2 = response;
            _.forEach(products, (arr1Item) => {
              const arr2Item = _.find(products2, { 'product_id': arr1Item.id });
              if (arr2Item) {
                arr1Item.priceItem = arr2Item.prix_vente
                arr1Item.price = arr2Item.prix_vente
                arr1Item.sales_price = arr2Item.prix_vente
              }
            });
            this.appro_list = products;
            this.appro_list2 = products;
          })
      })
    },

    sales_get () {
      this.getApi('/my/get/sales')
        .then((response) => {
          this.sales_list = response;
        })
    },
    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      this.getApi('/my/get/sales_stats', params)
        .then((response) => {
          this.sales_list = response;
          this.nbre_vendus = _.sumBy(this.sales_list, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_list, 'montant_vendu');
        })
    },
    factures_get_credit (factureid) {
      this.getApi('/my/get/sales_by_credit?id_vente=' + factureid, { })
        .then((response) => {
          this.versements = response;
        })
    },
    factures_get_id (factureid) {
      this.getApi('/my/get/sales_by_idvente?id_vente=' + factureid, { })
        .then((response) => {
          for (const element of response) {
            element.p = {};
            element.p.id = element.p_id;
            element.p.tva = element.tva;
            element.p.sales_price = element.prix_unitaire;
            element.price = element.prix_unitaire;
            element.p.quantity = element.quantite_vendu;
            element.quantity = element.quantite_vendu;
            element.p.name = element.p_name;
            element.name = element.p_name;
            // response[i].total = response[i].montant_vendu;
          }
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = response[0]['client'] == null ? {} : JSON.parse(response[0]['client']);
          this.status_download = true;
        })
    }
  }
}
</script>

<style>
::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
</style>
