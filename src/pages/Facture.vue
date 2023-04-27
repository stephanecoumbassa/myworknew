<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
      <div class="col-lg-11 col-12">


        <div class="row">
          <div class="col-12 q-pa-lg">
            <q-input class="row" autocomplete type="search" v-model="search"
                     v-on:keyup="facture_filter_get(search)" label="Rechercher" />
          </div>
          <div class="col-12">

<!--            <div id="printMe" style="background:red;">-->
<!--              <p>葫芦娃，葫芦娃</p>-->
<!--              <p>一根藤上七朵花 </p>-->
<!--              <p>小小树藤是我家 啦啦啦啦 </p>-->
<!--              <p>叮当当咚咚当当　浇不大</p>-->
<!--              <p> 叮当当咚咚当当 是我家</p>-->
<!--              <p> 啦啦啦啦</p>-->
<!--              <p>...</p>-->
<!--            </div>-->
<!--            <button v-print="'#printMe'">Print local range</button>-->

            <q-table id="printMe" title="Listes de ventes" :grid="grid" :rows="factures" :columns="columns_facture"
                     :pagination="pagination" :filter="filter" dense>
              <template v-slot:top="props">
                <div class="col-4 q-table__title">Liste des factures</div>&nbsp;&nbsp;&nbsp;
<!--                <q-input dense debounce="300" type="search" icon="search" v-model="filter" placeholder="Rechercher" />-->
                <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" @click="json2csv(factures, 'vente')"/>
                <q-btn flat round dense icon="print" v-print="'#printMe'" class="q-ml-md" />
                <q-btn flat round dense icon="grid_on" @click="grid = !grid" class="q-ml-md" />
                <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen" class="q-ml-md" />
              </template>
              <template v-slot:body="props">
                 <q-tr :props="props">
                  <q-td key="facture" :props="props">{{ props.row.facture }}</q-td>
                  <q-td key="dateposted" :props="props">{{ props.row.dateposted }}</q-td>
                  <q-td key="fullname" :props="props">{{ props.row.fullname }}</q-td>
                  <q-td key="total" :props="props">{{ props.row.total }}</q-td>
                  <q-td key="versement" :props="props">{{ props.row.versement }}</q-td>
                  <q-td key="reste" :props="props">{{ props.row.total - props.row.versement }}</q-td>
                  <q-td key="actions" :props="props" class="print-hide">
                    <q-btn flat icon="edit" @click="fullWidth = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                    <q-btn flat icon="receipt" @click="facture_status2 = true; get_facture_id(props.row.facture); factures_get_credit(props.row.facture)"></q-btn>
                  </q-td>
                </q-tr>
              </template>

            </q-table>
          </div>

        </div>

        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1200px; max-width: 100%;" id="facture" :flat="true">
            <q-card-section>
              <div class="row">
                <div class="col-5 alignleft">
<!--                  <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>-->
<!--                  <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>-->
                  <img src="~assets/fmmi-logo.jpeg" style="width: 100px; height: 100px; object-fit: cover"/>
                  <div>{{entreprise.name}}</div>
                  <div>{{entreprise.telephone}}</div>
                  <div>{{entreprise.email}}</div>
                </div>
                <div class="col-3 text-center">
                  <!--                                    <div><q-input stack-label v-model="dateposted" type="datetime-local" label="Date"></q-input></div>-->
                  <q-input bottom-slots stack-label v-model="dateposted" type="datetime-local" :dense="true">
                    <template v-slot:after>
                      <q-btn round dense flat icon="send" @click="sales_dateposted(products[0])" />
                    </template>
                  </q-input>
                </div>
                <div class="col-4 text-right float-right">
                  <div>Facture #: {{facture_number}}</div>
                  <div>Creation: {{date}}</div>
                  <div><q-icon name="business" />Entreprise Corporation</div>
                  <div v-if="client"><q-icon name="face" />{{client.name}} {{client.last_name}}</div>
                  <div v-if="client"><q-icon name="phone" />+ {{client.telephone_code}} {{client.telephone}}</div>
                  <div v-if="client"><q-icon name="email" /> {{client.email}}</div>
                </div>
              </div>
            </q-card-section>

            <q-card-section>
              <q-form  @submit="onSubmit" class="">
                <br>
                <div class="row no-margin no-padding" style="height: 47px">
                  <div class="col-4 q-pa-sm">Nom</div>
                  <div class="col-1 q-pa-sm">Qte</div>
                  <div class="col-2 q-pa-sm">Prix</div>
                  <div class="col-2 q-pa-sm">TVA</div>
                  <div class="col-2 q-pa-sm">Total</div>
                </div>
                <div class="row q-pa-sm" v-for="(product, index) in products" :key="index">
                  <q-select outlined class="col-4 no-margin text-no-wrap truncate" v-model="product.product_id" use-input map-options emit-value
                            option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                  <q-input class="col-1 row q-pl-sm" autocomplete type="number" v-model="product.quantite_vendu" label="Quantité" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.prix_unitaire" label="Prix" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.tva" label="tva" />
                  <q-input class="col-2 row q-pl-sm" autocomplete type="number" label="total" :set="product.total = (product.quantite_vendu*product.prix_unitaire) + (product.quantite_vendu*product.prix_unitaire * product.tva) /100"
                           :model-value="product.total" />
                  <div class="col-1 row q-pl-xs print-hide">
                    <q-btn flat size="sm" color="secondary" v-on:click="sales_update(product)" icon="edit" />
                    <q-btn flat size="sm" color="negative" v-on:click="sales_delete(product.id, 'Erreur Saisie', product.code_ap)" icon="remove" />
                  </div>
                </div>
                <div class="row q-pa-sm print-hide" v-if="status_download">
                  <div class="col-3 row q-pa-sm">
                    <h6 class="bg-red-1 q-pa-sm" v-if="total - array_somme(versements, 'montant') >= 0">
                      <small>Reste=</small> {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA
                    </h6>
                    <h6 class="no-padding bg-red-1" v-if="total - array_somme(versements, 'montant') < 0">
                      <small>Vous devez</small> - {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA
                    </h6>
                  </div>
                  <div class="col-3 offset-6 row q-pa-sm">
                    <h6 class="text-right no-padding">{{numerique(Math.round(total)) }} CFA </h6>
                  </div>
                </div>
                <div v-if="status_download" class="print-hide">
                  <div class="col-12">
                    <div class="text-h6">Gestion des versements</div>
                    &nbsp;<q-btn outline color="grey" size="xs" v-on:click="versements.pop()">-</q-btn>
                    &nbsp;<q-btn outline color="primary" size="xs" v-on:click="versements.push({montant: 0})" icon="add">
                      <q-tooltip anchor="top middle" self="bottom middle" :offset="[10, 10]">Ajouter un versement</q-tooltip>
                    </q-btn>
                  </div>
                  <div class="row" v-for="fac in versements" v-bind:key="fac.id">
                    <q-input class="col-1 q-ma-sm" :dense="true" label="Date Echéance" type="date" stack-label v-model="fac.echeance"  />
                    <q-input class="col-1 q-ma-sm" :dense="true" label="Montant Echéance" stack-label type="number" v-model="fac.montant_echeance" />
                    <q-input class="col-1 q-ma-sm" :dense="true" label="Date Vers" type="date" stack-label v-model="fac.date"  />
                    <q-input class="col-1 q-ma-sm" :dense="true" label="Montant Vers" stack-label type="number" v-model="fac.montant" />
                    <div class="col-1">
                      <br>
                      <q-btn outline color="secondary" size="sm" v-if="fac.id" v-on:click="credit_update(fac)">✎</q-btn>
                      <q-btn outline size="sm" v-if="!fac.id" v-on:click="credit_add(fac)">✅</q-btn>
                    </div>
                  </div>
                </div>

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal print-hide q-ma-lg q-pa-lg" v-if="status_download">
              <q-btn color="red-4" label="Supprimer la facture" v-on:click="factures_delete()" />
              <!--              <q-btn color="dark" flat label="Telecharger" v-on:click="download()" />-->
              <!--              <q-btn color="dark" flat label="Imprimer" v-on:click="imprimer()" />-->
              <q-btn color="dark" flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="facture_status2" position="top" facture_filter_getstyle="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture name="Facture de devis"
                     :entreprise="entreprise" :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import basemixin from './basemixin';

const data = [{ 'name': 'Facebook' }, { 'name': 'Google' }, { 'name': 'Twitter' }];
import $httpService from '../boot/httpService';
import FactureComponent from '../components/facture_component.vue';
export default {
  name: 'FacturePage',
  mixins: [basemixin],
  data () {
    return {
      items: data,
      facture: { type: 'vente' },
      value: 'Facebook',
      name: '',
      filter: '',
      fullWidth: false,
      medium: false,
      medium2: false,
      status_download: true,
      facture_status2: false,
      agent: null,
      client: {},
      entreprise: {},
      fournisseur: null,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      search: null,
      buy: null,
      versements: [],
      categories: [],
      users: [],
      factures: [],
      grid: false,
      factures_init: [],
      factures_details: [],
      facture_id: null,
      products: [],
      sales_list: [],
      products_list: [],
      date: '',
      dateposted: '',
      appro_list: [{ p: { sell_price: 0, id: null, quantity: 1 }, quantity: 1 }],
      product: { description: '' },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true },
        { name: 'dateposted', align: 'left', label: 'date', field: 'dateposted', sortable: true },
        { name: 'quantite_vendu', align: 'left', label: 'qte', field: 'quantite_vendu', sortable: true },
        { name: 'montant_vendu', align: 'left', label: 'qte', field: 'montant_vendu', sortable: true },
        { name: 'prix_unitaire', align: 'left', label: 'prix', field: 'prix_unitaire', sortable: true },
        { name: 'remise_totale', align: 'left', label: 'remise.', field: 'remise_totale', sortable: true },
        { name: 'benefice_vente', align: 'left', label: 'benefice', field: 'benefice_vente', sortable: true },
        { name: 'a_name', align: 'left', label: 'Agent', field: 'a_name', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      columns_facture: [
        { name: 'facture', align: 'left', label: 'Facture', field: 'facture', sortable: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'fullname', align: 'left', label: 'Client', field: 'fullname', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'versement', align: 'left', label: 'Versement', field: 'versement', sortable: true },
        { name: 'reste', align: 'left', label: 'reste', field: 'reste', sortable: true },
        { name: 'actions', align: 'left', classes: 'print-hide', headerClasses: 'print-hide', label: 'Actions' }
      ],
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
      data: []
    }
  },
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.products_get();
    this.factures_get();
    this.shop_get();
  },
  components: {
    'facture': FactureComponent
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.montant_vendu - item.remise_totale), 0);
      // return this.products.reduce((product, item) => product + (item.total ), 0);
      return this.products.reduce((product, item) => product + (item.total ), 0);
    }
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'fas fa-check-circle',
          message: 'Submitted'
        })
      }
    },
    facture_filter_get() {
      const val1 = this.factures_init.filter((x) => {
        return x.facture.toLowerCase().includes(this.search)
          || x.dateposted.toString().includes(this.search)
          || x.fullname.toLowerCase().includes(this.search.toLowerCase())
          || x.montant_vendu.includes(this.search.toLowerCase())
          ;
      });
      this.factures = val1;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign (index) {
      this.products[index].p.quantity = 1;
    },
    sales_post() {
      if (confirm('Voulez vous ajouter')) {
        let params = {
          'agent': this.agent,
          'fournisseur': this.fournisseur,
          'products': this.products,
          'id_vente': this.facture_number
        };
        $httpService.postWithParams('/my/post/sales', params)
          .then((response) => {
            this.products_get();
            this.appro_get();
            this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
          })
      }
    },
    factures_get () {
      $httpService.getWithParams('/my/get/factures_id')
        .then((response) => {
          this.factures = response;
          this.factures_init = response;
          for (let i = 0; i < this.factures.length; i++) {
            if (this.factures[i].client) {
              this.factures[i].fullname = JSON.parse(this.factures[i].client)['fullname'];
            }
          }
        })
    },
    get_facture_id (_id) {
      this.facture_id = _id;
      this.factures_get_id();
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/sales_by_facture', { id_vente: this.facture_id })
        .then((response) => {
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = JSON.parse(response[0]['client']);
          this.dateposted = this.products[0].date;
          this.date = this.dateformat(this.products[0]['dateposted'], 4);
          this.dateposted = new Date(this.products[0]['dateposted']).toISOString().slice(0, 16);

          for (let i = 0; i < response.length; i++) {
            response[i].price = response[i].prix_unitaire;
            response[i].quantity = response[i].quantite_vendu;
            // response[i].total = response[i].montant_vendu;
          }
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/sales_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_add(facture) {
      facture.factureid = this.facture_id;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/post/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    },
    credit_update(facture) {
      facture.factureid = this.facture_id;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/put/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    sales_update(product) {
      if (confirm('Voulez vous mettre à jour')) {
        product.p_id = product.product_id;
        $httpService.postWithParams('/my/put/sales', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_dateposted(product) {
      product.dateposted = this.dateposted;
      if (confirm('Voulez vous modifier la date de la facture')) {
        $httpService.postWithParams('/my/dateposted/sales', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'positive', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    sales_delete(id, motif, codeAp) {
      // console.log({ id: id, motif: motif, code_ap: codeAp, total: this.total });
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.postWithParams('/my/delete/sales', { id: id, motif: motif, code_ap: codeAp })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    factures_delete() {
      if (confirm('Voulez vous supprimer la facture?')) {
        $httpService.postWithParams('/my/delete/sales_all', { factureid: this.facture_number })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_credit();
            this.factures_get_id();
            this.factures_get();
          })
      }
    },
    imprimer() {
      window.print();
    },
    colorize (value) {
      if (value > 0) {
        return 'bg-red-1';
      }
    },
    specialities_add () {
      this.products.push({ p: { sell_price: 0, id: null, quantity: 1 } });
    },
    specialities_delete () {
      this.products.pop();
    },
    filterFn(val, update) {
      this.value = null;
      if (val === '') {
        update(() => {
          this.items = data
        });
        return
      }
      update(() => {
        const needle = val.toLowerCase();
        this.items = data.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
      })
    }
  }
}
</script>

<style>
</style>
