<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">
<!--      <div class="col-10">-->
      <div class="col-lg-10 col-12">

        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1000px; max-width: 100%;" id="facture" :flat="true">
            <q-card-section contenteditable="true">
              <div class="row">
                <div class="col-6 alignleft">
                  <img :src="'https://www.affairez.com/apistock/public/assets/uploads/magasin/'+entreprise.logo"
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
              <q-form  @submit="onSubmit" class="">
                <br>
                <div class="row q-pa-sm" v-for="(product, index) in products" :key="index">
                  <div :class="'row '+colorize(product.retour)" >
                    <q-select class="col-2 no-margin" v-model="product.product_id" use-input map-options emit-value readonly disable disabled
                              option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                    <div class="col-1">
                      {{product.quantite_vendu}} X {{product.prix_unitaire}}<br>
                      {{product.quantite_vendu * product.prix_unitaire - product.remise_totale}} CFA
                    </div>
                    <q-input class="col-1 offset-1 row q-pl-sm" autocomplete type="number" v-model="product.retour" label="Qt?? retourn??e" />
                    <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.prix_unitaire" label="Prix" />
                    <q-input class="col-2 row q-pl-sm" autocomplete type="number" :value="product.retour * product.prix_unitaire - product.remise_totale" label="total" />
                    <q-input class="col-2 row q-pl-sm" autocomplete v-model="product.motif" label="Motif" />
                    <div class="col-1 row q-pl-xs print-hide">
                      <br>
                      <q-btn flat size="sm" color="secondary" v-on:click="retour_update(product)" icon="edit" />
                      <!--                    <q-btn flat size="sm" color="negative" v-on:click="retour_delete(product.id, 'Erreur Saisie', product.code_ap)" icon="remove" />-->
                    </div>
                  </div>
                </div>
                <div class="row q-pa-sm print-hide" v-if="status_download">
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
                <!--                    <q-btn class="no-padding" size="xs" v-if="fac.id" v-on:click="credit_update(fac)">???</q-btn>-->
                <!--                    <q-btn size="xs" v-if="!fac.id" v-on:click="credit_add(fac)">???</q-btn>-->
                <!--                  </div>-->
                <!--                </div>-->

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal print-hide q-ma-lg q-pa-lg" v-if="status_download">
              <q-btn flat label="Telecharger" v-on:click="download()" />
              <q-btn flat label="Imprimer" v-on:click="imprimer()" />
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <div class="row">
          <div class="col-12">
            <h6 class="text-h6">Gestions de retour</h6>
          </div>
          <div class="col-12 q-pa-lg">
            <q-input class="row" autocomplete type="search" v-model="search"
                     v-on:keyup="facture_filter_get(search)" label="Rechercher les N?? factures" />
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 q-pa-md"
               v-for="item in factures" :key="item.facture" @click="get_facture_id(item.facture); factures_get_credit(item.facture)">
            <q-card :class="'q-pa-md '+colorize(item.restant)">
              Facure N??
              {{item.facture}}<br>
              <span class="float-right" v-if="item.restant > 0">{{numerique(item.restant)}} fcfa<br></span>
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
            items: data,
            facture: { type: 'vente' },
            value: 'Facebook',
            name: '',
            fullWidth: false,
            medium: false,
            medium2: false,
            status_download: true,
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
            factures_init: [],
            factures_details: [],
            facture_id: null,
            products: [],
            sales_list: [],
            products_list: [],
            date: '',
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
    computed: {
        total() {
            // return this.products.reduce((product, item) => product + (item.montant_vendu - item.remise_totale), 0);
            return this.products.reduce((product, item) => product + (item.prix_unitaire * item.quantite_vendu + (item.tva * item.prix_unitaire * item.quantite_vendu) - item.remise_totale), 0);
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
            const val1 = this.factures_init.filter((x) => { return x.facture.toString().includes(this.search); });
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
        retour_update(product) {
            console.log(product);
            if (confirm('Voulez vous mettre ?? jour')) {
                product.p_id = product.product_id;
                $httpService.postWithParams('/my/put/retour', product)
                    .then((response) => {
                        this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
                        this.factures_get_id();
                    })
            }
        },
        retour_delete(id, motif, codeAp) {
            // console.log({ id: id, motif: motif, code_ap: codeAp, total: this.total });
            if (confirm('Voulez vous supprimer ?')) {
                $httpService.postWithParams('/my/delete/retour', { id: id, motif: motif, code_ap: codeAp })
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
