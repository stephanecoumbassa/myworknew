<template>
  <q-page padding>
    <div class="row justify-center">

      <!--      <div class="col-12 q-mt-md">-->
      <div class="col-lg-10 col-12">

        <br><br>
        <q-table title="Produits" :rows="credits" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des ventes et des credits<br>
              <q-btn color="red"> Impayés
                {{ numerique( array_somme(credits, 'total') - array_somme(credits, 'credit') ) }}
              </q-btn>
            </div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id"> {{props.row.id_vente}} </q-td>
              <q-td key="name"> {{props.row.client_id}} </q-td>
              <q-td key="total"> {{ numerique(props.row.total) }} </q-td>
              <q-td key="credit"> {{ numerique(props.row.credit) }} </q-td>
              <q-td key="reste"> {{ numerique(props.row.total - props.row.credit) }} </q-td>
              <q-td key="actions">
                <!--                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="visibility" />-->
                <q-btn class="q-mr-xs" size="xs" color="primary" icon="visibility"
                       v-on:click="get_facture_id(props.row.id_vente); factures_get_credit(props.row.id_vente)" />
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="fullWidth" position="top">
        <q-card style="width: 1200px; max-width: 100%;" id="facture" :flat="true">
          <q-card-section contenteditable="true">
            <div class="row">
              <div class="col-6 alignleft mobile-hide">
                <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>
                <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>
                <div>{{entreprise.name}}</div>
                <div>{{entreprise.telephone}}</div>
                <div>{{entreprise.email}}</div>
              </div>
              <div class="col-6 text-right float-right mobile-hide">
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
<!--              {{products}}-->
              <div class="row q-pa-sm" v-for="(product, index) in products" :key="index">
<!--                {{product.total}}-->
                <q-select class="col-sm-4 col-xs-12 col-3 no-margin" v-model="product.product_id" use-input map-options emit-value readonly
                          option-value="id" option-label="name" stack-label input-debounce="0" label="produits" :options="products_list" />
                <q-input class="col-sm-2 col-xs-3 col-2 row q-pl-sm" readonly :model-value="numerique(product.quantite_vendu)" label="Quantité" />
                <q-input class="col-sm-3 col-xs-3 col-2 row q-pl-sm" readonly :model-value="numerique(product.prix_unitaire)" label="Prix" />
<!--                <q-input class="col-2 row q-pl-sm" readonly :model-value="numerique(product.remise_totale)" label="remise" />-->
                <q-input class="col-sm-3 col-xs-3 col-3 row q-pl-sm" readonly :model-value="numerique(product.total)" label="total" />
                <!--                <div class="col-1 row q-pl-xs">-->
                <!--                  <br>-->
                <!--                  <q-btn flat size="sm" color="secondary" v-on:click="sales_update(product)" icon="edit" />-->
                <!--                  <q-btn flat size="sm" color="negative" v-on:click="sales_delete(product.id, 'Erreur Saisie', product.code_ap)" icon="remove" />-->
                <!--                </div>-->
              </div>
              <div class="row q-mt-xs" style="min-height: 20px">
                <h6 class="col-3 no-padding"><small>Reste=</small> {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA</h6>
                <h6 class="offset-5 text-right col-4 no-padding">{{numerique(Math.round(total)) }} FCFA </h6>
              </div>
              Liste des versement
              &nbsp;<q-btn size="xs" v-on:click="versements.pop()">-</q-btn>
              &nbsp;<q-btn size="xs" v-on:click="versements.push({montant: 0})">+</q-btn>
              <div class="row" v-for="fac in versements" v-bind:key="fac.id">
                <q-input class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label v-model="fac.date"  />
                <q-input class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" v-model="fac.montant" />
                <div class="col-3">
                  <br>
                  <q-btn size="xs" color="teal" v-if="fac.id" v-on:click="credit_update(fac)">✎</q-btn>
                  <q-btn size="xs" v-if="!fac.id" v-on:click="credit_add(fac)">✅</q-btn>
                </div>
              </div>
            </q-form>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal">
            <q-btn flat label="Fermer" v-close-popup />
            <q-btn flat label="Imprimer" v-on:click="imprimer()" />
          </q-card-actions>
        </q-card>
      </q-dialog>

    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin'

export default {
  data () {
    return {
      product_id: 1,
      loading1: false,
      red: '#6d1412',
      medium: false,
      medium2: false,
      fullWidth: false,
      facture_number: null,
      entreprise: {},
      client: {},
      products_list: [],
      maximizedToggle: true,
      name: null,
      image: null,
      productid: null,
      categories: [],
      users: [],
      products: [],
      versements: [],
      credits: [{ details: {} }],
      product: { description: '' },
      columns: [
        { name: 'id_ap', align: 'left', label: 'Facture Id', field: 'id_ap', sortable: true },
        { name: 'Client', align: 'left', label: 'Client Id', field: 'client_id', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'credit', align: 'left', label: 'Payés', field: 'credit', sortable: true },
        { name: 'reste', align: 'left', label: 'Reste', field: 'reste', sortable: true },
        { name: 'actions', label: 'Actions', align: 'right' }
      ],
      filter: '',
      date: '',
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
      return this.products.reduce((product, item) => product + (item.total), 0);
    }
  },
  components: {
    // areachart: AreachartComponent,
    // VueQr
  },
  mixins: [basemixin],
  created () {
    this.products_get();
    this.credit_get();
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    update_get(props) {
      this.product = props;
      this.product.categories = props.product_categories_id;
      this.product.description = props.product_desc;
      this.product_id = props.id;
      this.medium2 = true;
    },
    alerte(item) {
      if (item.amount <= item.alert_threshold) {
        return 'bg-blue-grey-3';
      }
    },
    credit_get () {
      $httpService.getWithParams('/my/get/credit_vente')
        .then((response) => {
          this.credits = response;
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.facture_id = _id;
      this.factures_get_id();
    },
    factures_get () {
      $httpService.getWithParams('/my/get/factures_id')
        .then((response) => {
          this.factures = response;
          this.factures_init = response;
        })
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/sales_by_facture', { id_vente: this.facture_id })
        .then((response) => {
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = JSON.parse(response[0]['client']);
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/sales_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_add(facture) {
      facture.factureid = this.facture_number;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/post/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    },
    credit_update(facture) {
      facture.factureid = this.facture_number;
      facture.vente = 'vente';
      $httpService.postWithParams('/my/put/credit', facture)
        .then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.factures_get_credit();
        })
    }
  }
}
</script>

<style>
</style>
