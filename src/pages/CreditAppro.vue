<template>
  <q-page padding>
    <div class="row justify-center">
      <div class="col-lg-10 col-12 q-mt-md">

        <br><br>
        <q-table title="Produits" :rows="credits" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des approvisionnements et des credits <br>
              <q-btn color="red"> Impayés:
                {{ numerique( array_somme(credits, 'total') - array_somme(credits, 'credit') ) }}
              </q-btn>
            </div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id"> {{props.row.id_ap}} </q-td>
              <q-td key="name"> {{props.row.fournisseur_user}} </q-td>
              <q-td key="total"> {{ numerique(props.row.total) }} </q-td>
              <q-td key="credit"> {{ numerique(props.row.credit) }} </q-td>
              <q-td key="reste"> {{ numerique(props.row.total - props.row.credit) }} </q-td>
              <q-td key="actions">
                <q-btn
                  class="q-mr-xs" size="xs" color="dark" icon="visibility"
                  @click="get_facture_id(props.row.id_ap); factures_get_credit(props.row.id_ap)" />
                <!-- <q-btn class="q-mr-xs" size="xs" color="secondary" v-on:click="photo_get(props.row)" icon="photo" />-->
                <!-- <q-btn v-if="role == 1" class="q-mr-xs" size="xs" color="red" icon="delete"></q-btn>-->
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>

      <q-dialog v-model="fullWidth" position="top">
        <q-card id="facture" class="q-ma-sm q-pa-sm" :flat="true" style="width: 1000px; max-width: 100%;">
          <q-card-section contenteditable="true" class="mobile-hide">
            <div class="row mobile-hide">
              <div class="col-6 alignleft">
                <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>
                <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>
                <div>{{entreprise.name}}</div>
                <div>{{entreprise.telephone}}</div>
                <div>{{entreprise.email}}</div>
              </div>
              <div class="col-6 text-right float-right">
                <div>Facture #: {{facture_number}}</div>
                <div>Creation: {{date}}</div>
                <div><q-icon name="business" />Entreprise Corporation</div>
                <div><q-icon name="face" />{{fournisseur.name}} {{fournisseur.last_name}}</div>
                <div><q-icon name="phone" />+ {{fournisseur.telephone_code}} {{fournisseur.telephone}}</div>
                <div><q-icon name="email" /> {{fournisseur.email}}</div>
              </div>
            </div>
          </q-card-section>

          <q-card-section>
            <q-form  class="" @submit="onSubmit">
              <br>
              <div v-for="(product, index) in products" :key="index" class="row q-pa-sm">
                <q-select
                  v-model="product.product_id" class="col-sm-12 col-xs-12 col-3 row q-pl-sm" map-options emit-value readonly
                  option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                <q-input v-model="product.amount" class="col-sm-3 col-xs-3 col-2 row q-pl-sm" readonly type="number" label="Quantité" />
                <q-input v-model="product.buying_price" class="col-sm-3 col-xs-3 col-2 row q-pl-sm" readonly type="number" label="Prix Achat" />
                <q-input v-model="product.sell_price" class="col-sm-3 col-xs-3 col-2 q-pl-sm" readonly type="number" label="Prix Vente" />
                <!-- <q-input class="col-2 no-margin" autocomplete type="number" v-model="product.montant_vendu" label="Prix de Vente" />-->
                <q-input class="col-sm-3 col-xs-3 col-2 row q-pl-sm" readonly type="number" :value="product.amount * product.buying_price" label="total" />
              </div>

              <div class="row q-ma-sm q-pa-sm" style="min-height: 20px">
                <div class="text-subtitle2 col-sm-5 col-xs-5 col-3 no-padding"><small>Reste= {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} CFA</small></div>
                <div class="text-subtitle2 offset-md-5 text-right col-4 no-padding">{{numerique(total)}}CFA</div>
                <br>
                <br>
              </div>

              Liste des versement
              &nbsp;<q-btn size="xs" @click="versements.pop()">-</q-btn>
              &nbsp;<q-btn size="xs" @click="versements.push({montant: 0})">+</q-btn>
              <div v-for="fac in versements" :key="fac.id" class="row">
                <q-input v-model="fac.date" class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label  />
                <q-input v-model="fac.montant" class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" />
                <q-btn v-if="fac.id" class="col-1 q-ma-sm" size="xs" @click="credit_update(fac)">✎</q-btn>
                <q-btn v-if="!fac.id" class="col-1 q-ma-sm" size="xs" @click="credit_add(fac)">✅</q-btn>
              </div>
              <!--                                <q-btn class="print-hide" label="Valider" icon="save" type="submit" color="secondary"/>-->
            </q-form>
          </q-card-section>

          <q-card-actions align="right" class="bg-white text-teal print-hide">
            <q-btn v-close-popup flat label="Fermer" />
            <q-btn flat label="Imprimer" @click="imprimer()" />
          </q-card-actions>
        </q-card>
      </q-dialog>

    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
// import * as _ from 'lodash';
// import VueQr from 'vue-qr';
// eslint-disable-next-line
//   import HelloComponent from '../components/hello';
import basemixin from './basemixin'
// import AreachartComponent from '../components/areachart'

export default {
  components: {
    // areachart: AreachartComponent,
    // VueQr
  },
  mixins: [basemixin],
  data () {
    return {
      fullWidth: false,
      products: [],
      credits: [{ details: {} }],
      fournisseur: {},
      entreprise: {},
      facture_number: null,
      date: '',
      versements: [],
      factures_details: [],
      facture_id: null,

      columns: [
        { name: 'id_ap', align: 'left', label: 'Facture Id', field: 'id_ap', sortable: true },
        { name: 'fournisseur_user', align: 'left', label: 'Four. Id', field: 'fournisseur_user', sortable: true },
        { name: 'total', align: 'left', label: 'Total', field: 'total', sortable: true },
        { name: 'credit', align: 'left', label: 'Payés', field: 'credit', sortable: true },
        { name: 'reste', align: 'left', label: 'Reste (Impayés)', field: 'reste', sortable: true },
        { name: 'actions', label: 'Actions', align: 'right' }
      ],
      filter: '',
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10
      }
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.buying_price * item.amount + (item.tva * item.buying_price * item.amount)), 0);
    }
  },
  created () {
    this.products_get();
    this.credit_get();
    this.shop_get();
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
  },
  methods: {
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    credit_get () {
      $httpService.getWithParams('/my/get/credit_appro')
        .then((response) => {
          this.credits = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.facture_id = _id;
      this.factures_get_id();
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/appro_by_facture', { id_ap: this.facture_id })
        .then((response) => {
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_ap;
          this.fournisseur = JSON.parse(response[0]['fournisseur']);
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/appro_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    credit_update(facture) {
      if (confirm('Voulez vous modifier')) {
        facture.factureid = this.facture_number;
        facture.vente = 'achat';
        $httpService.postWithParams('/my/put/credit', facture)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_credit();
          })
      }
    },
    credit_add(facture) {
      if (confirm('Voulez vous ajouter')) {
        facture.factureid = this.facture_number;
        facture.vente = 'achat';
        $httpService.postWithParams('/my/post/credit', facture)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_credit();
          })
      }
    }
  }
}
</script>

<style>
</style>
