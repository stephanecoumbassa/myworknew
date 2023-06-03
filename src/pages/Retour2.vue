<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
      <!--      <div class="col-10">-->
      <div class="col-lg-10 col-12">

        <q-dialog v-model="fullWidth" position="top">
          <q-card id="facture" style="width: 1000px; max-width: 100%;" :flat="true">
            <q-card-section contenteditable="true">
              <div class="row">
                <div class="col-6 alignleft">
                  <img
                    :src="'https://www.affairez.com/apistock/public/assets/uploads/magasin/'+entreprise.logo"
                    style="width: 100px; height: 100px; object-fit: cover" />
                  <!--                                  <div>{{entreprise.name}}</div>-->
                  <div>{{entreprise.telephone}}</div>
                  <div>{{entreprise.email}}</div>
                </div>
                <div class="col-6 text-right float-right">
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
              <q-form  class="" @submit="onSubmit">
                <br>
                <div v-for="(prod, index) in products" :key="index" class="row q-pa-sm">
                  <div :class="'row '+colorize(prod.retour)" >
                    <q-select
                      v-model="prod.product_id" class="col-2 no-margin" use-input map-options emit-value readonly disable disabled
                      option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                    <div class="col-1">
                      {{prod.quantite_vendu}} X {{prod.prix_unitaire}}<br>
                      {{prod.quantite_vendu * prod.prix_unitaire - prod.remise_totale}} CFA
                    </div>
                    <q-input v-model="prod.retour" class="col-1 offset-1 row q-pl-sm" autocomplete type="number" label="Qté retournée" />
                    <q-input v-model="prod.prix_unitaire" class="col-2 row q-pl-sm" autocomplete type="number" label="Prix" />
                    <q-input class="col-2 row q-pl-sm" autocomplete type="number" :value="prod.retour * prod.prix_unitaire - prod.remise_totale" label="total" />
                    <q-input v-model="prod.motif" class="col-2 row q-pl-sm" autocomplete label="Motif" />
                    <div class="col-1 row q-pl-xs print-hide">
                      <br>
                      <q-btn flat size="sm" color="secondary" icon="edit" @click="retour_update(prod)" />
                      <q-btn flat size="sm" color="negative" icon="remove" @click="retour_delete(prod.id, 'Erreur Saisie', prod.code_ap)" />
                    </div>
                  </div>
                </div>
                <div v-if="status_download" class="row q-pa-sm print-hide">
                  <!--                  <div class="col-3 row q-pl-xs">-->
                  <!--                    <h6 class="no-padding bg-green-1" v-if="total - array_somme(versements, 'montant') >= 0">-->
                  <!--                      <small>Reste=</small> {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA-->
                  <!--                    </h6>-->
                  <!--                    <h6 class="no-padding bg-red-1" v-if="total - array_somme(versements, 'montant') < 0">-->
                  <!--                      <small>Vous devez</small> - {{numerique( total - parseInt(array_somme(versements, "montant")) ) }} FCFA-->
                  <!--                    </h6>-->
                  <!--                  </div>-->
                  <div class="col-3 row q-pl-xs">
                    <h6 class="text-right no-padding">{{numerique(Math.round(total)) }} CFA </h6>
                  </div>
                </div>
                <!--                <div v-if="status_download" class="print-hide">-->
                <!--                  <div class="col-12">-->
                <!--                    Liste des versement-->
                <!--                    &nbsp;<q-btn size="xs" v-on:click="versements.pop()">-</q-btn>-->
                <!--                    &nbsp;<q-btn size="xs" v-on:click="versements.push({montant: 0})">+</q-btn>-->
                <!--                  </div>-->
                <!--                  <div class="row" v-for="fac in versements" v-bind:key="fac.id">-->
                <!--                    <q-input class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label v-model="fac.date"  />-->
                <!--                    <q-input class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" v-model="fac.montant" />-->
                <!--                    <q-btn class="no-padding" size="xs" v-if="fac.id" v-on:click="credit_update(fac)">✎</q-btn>-->
                <!--                    <q-btn size="xs" v-if="!fac.id" v-on:click="credit_add(fac)">✅</q-btn>-->
                <!--                  </div>-->
                <!--                </div>-->

              </q-form>
            </q-card-section>

            <q-card-actions v-if="status_download" align="right" class="bg-white text-teal print-hide q-ma-lg q-pa-lg">
              <q-btn flat label="Telecharger" @click="download()" />
              <q-btn flat label="Imprimer" @click="imprimer()" />
              <q-btn v-close-popup flat label="Fermer" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <div class="row">
          <div class="col-12">
            <h6 class="text-h6">Gestions de retour</h6>
          </div>
          <div class="col-12 q-pa-lg">
            <q-input
              v-model="search" class="row" autocomplete type="search"
              label="Rechercher les N° factures" @keyup="facture_filter_get(search)" />
          </div>
          <div
            v-for="item in factures"
            :key="item.facture" class="col-lg-3 col-md-6 col-sm-6 col-12 q-pa-md" @click="get_facture_id(item.facture); factures_get_credit(item.facture)">
            <q-card :class="'q-pa-md '+colorize(item.restant)">
              Facure N°
              {{item.facture}}<br>
              <span v-if="item.restant > 0" class="float-right">{{numerique(item.restant)}} fcfa<br></span>
            </q-card>
          </div>
        </div>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import basemixin from './basemixin'

const data = [{ 'name': 'Facebook' }, { 'name': 'Google' }, { 'name': 'Twitter' }];
import $httpService from '../boot/httpService';
import htmlToImage from 'html-to-image';
export default {
  name: 'Retour2Page',
  mixins: [basemixin],
  data () {
    return {
      fullWidth: false,
      status_download: true,
      agent: null,
      client: {},
      entreprise: {},
      fournisseur: null,
      facture_number: null,
      search: null,
      versements: [],
      factures: [],
      factures_init: [],
      factures_details: [],
      facture_id: null,
      products: [],
      products_list: [],
      date: '',
    }
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.montant_vendu - item.remise_totale), 0);
      return this.products.reduce((product, item) => product + (item.prix_unitaire * item.quantite_vendu + (item.tva * item.prix_unitaire * item.quantite_vendu) - item.remise_totale), 0);
    }
  },
  created () {
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.products_get();
    this.factures_get();
    this.shop_get();
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
      const val1 = this.factures_init.filter((x) => { return x.facture.toString().includes(this.search); });
      this.factures = val1;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
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
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
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
        })
    },
    factures_get_credit () {
      $httpService.getWithParams('/my/get/sales_by_credit', { id_vente: this.facture_id })
        .then((response) => {
          this.versements = response;
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
        })
    },
    retour_update(product) {
      console.log(product);
      if (confirm('Voulez vous mettre à jour')) {
        product.p_id = product.product_id;
        $httpService.postWithParams('/my/put/retour', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    retour_delete(id, motif, codeAp) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.postWithParams('/my/delete/retour', { id: id, motif: motif, code_ap: codeAp })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.factures_get_id();
          })
      }
    },
    imprimer() {
      window.print();
    },
    download() {
      this.status_download = false;
      htmlToImage.toJpeg(document.getElementById('facture'), { quality: 0.95 })
        .then((dataUrl) => {
          var link = document.createElement('a');
          link.download = 'factureVente.jpeg';
          link.target = '_blank';
          link.href = dataUrl;
          link.click();
          this.status_download = true;
        }).then(() => { this.status_download = true; });
    },
    colorize (value) {
      if (value > 0) {
        return 'bg-grey-1';
      }
    },
  }
}
</script>

<style>
</style>
