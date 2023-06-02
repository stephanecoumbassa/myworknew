<template>
  <q-page>
    <div class="row justify-center q-pa-md">
      <div class="col-lg-12 col-12"><br>
        <span class="text-h6">Achats</span><br>

        <q-tabs
          v-model="tab" dense class="text-dark"
          active-color="secondary" indicator-color="secondary" align="justify" narrow-indicator>
          <q-tab name="mails" label="Listes des achats" />
          <q-tab name="alarms" label="Nouvel achat" @click="products = [{ p: { sell_price: 0, id: null, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0 }, quantity: 1, buy: 0, sell: 0, tva: 0}]" />
        </q-tabs>
        <q-separator />

        <q-tab-panels v-model="tab" animated>

          <q-tab-panel name="mails">

            <div class="row q-pa-xs">
              <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
              <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
              <div class="col q-pa-sm"><br><q-btn color="secondary" label="filtrer" @click="appro_stats_get()" /></div>
            </div>

            <q-table
title="Liste de commandes"
                     :rows="commands_list" :columns="columns" :pagination="pagination" :filter="filter">
              <template #top="props">
                <div class="col-6 q-table__title">Liste des approvisionnements</div>
                <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
                <download-excel name="achats.xls" :json-data="commands_list">
                  <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
                </download-excel>
                <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                       class="q-ml-md" @click="props.toggleFullscreen" />
              </template>
              <template #body="props">
                <q-tr :props="props">
                  <q-td key="id" :props="props">{{ props.row.id }}</q-td>
                  <q-td key="p_name" :props="props">{{ props.row.p_name }}</q-td>
                  <q-td key="id_ap" :props="props">{{ props.row.id_ap }}</q-td>
                  <q-td key="dateposted" :props="props"> {{ dateformat(props.row.dateposted, 3) }}</q-td>
                  <q-td key="amount" :props="props"> {{ numerique(parseInt(props.row.amount)) }}</q-td>
                  <q-td key="p_buying_price" :props="props"> {{ numerique(props.row.p_buying_price) }}</q-td>
                  <q-td key="tva" :props="props"> {{ numerique(props.row.tva) }}</q-td>
                  <q-td key="total_achat" :props="props"> {{ numerique(props.row.total) }}</q-td>
                  <q-td key="f_name" :props="props"> {{ props.row.f_name }} {{ props.row.lastname }}</q-td>
                  <q-td key="f_last_name" :props="props"> {{ props.row.f_last_name }}</q-td>
<!--                  <q-td key="total_achat" :props="props"> {{ numerique(props.row.total_achat) }}</q-td>-->
                  <q-td key="actions" :props="props">
                    <q-btn class="q-ma-xs" size="xs" color="dark" icon="receipt" @click=" get_facture_id(props.row.id_ap); facture_number = props.row.id_ap; facture_status2 = true" />
                    <!--                          <q-btn class="q-ma-sm" size="xs" color="secondary" v-on:click="update_show(props.row)" icon="edit" />-->
                    <!--                          <q-btn class="q-ma-sm" size="xs" color="red" v-on:click="command_delete(props.row.id, '', props.row.id_ap)" icon="delete" />-->
                  </q-td>
                </q-tr>
              </template>
            </q-table>

          </q-tab-panel>

          <q-tab-panel name="alarms">

            <q-card id="facture" style="width: 1300px; max-width: 100%;" :flat="true">

              <q-card-section>
                <q-form  class="q-gutter-md" @submit="onSubmit">
                  <div class="row print-hide q-mb-lg">
                    <div class="col-md-4 col-12 q-ma-md">
                      <q-input
v-model="dateposted" stack-label type="datetime-local"
                               label="Date de la facture" :dense="true" @change="change_dateposted()"></q-input>
                    </div>
                    <div class="col-md-4 col-12 q-ma-md" style="margin-top: 10px">
                      <q-select
v-if="users" v-model="fournisseur" filled map-options emit-value :options="users" label="Fournisseur" :option-value="JSON.stringify(fournisseur)" input-debounce="0"
                                option-label="name" :dense="true" :rules="[val => !!val || 'Ce champs est requis']" @input="assign_fournisseur(fournisseur)" />
                    </div>
                  </div>
                  <div class="row mobile-hide hidden-sm hidden-xs">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 alignleft">
                      <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>
                      <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>
                      <div>{{entreprise.name}}</div>
                      <div>{{entreprise.telephone}}</div>
                      <div>{{entreprise.email}}</div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 content-center text-center print-only" print-only style="height: 150px">
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-4 text-right float-right" style="min-width: 200px" contenteditable="true">
                      <div>Facture #: {{facture_number}}</div>
                      <div>Creation: {{date}}</div>
                      <div><q-icon name="business" /> Entreprise Corporation</div>
                      <div><q-icon name="face" /> {{fournisseur2.fullname}}</div>
                      <div><q-icon name="phone" /> + {{fournisseur2.telephone_code}} {{fournisseur2.telephone}}</div>
                      <div><q-icon name="email" /> {{fournisseur2.email}}</div>
                    </div>
                  </div>
                  <div class="row mobile-hide hidden-sm hidden-xs" style="height: 47px">
                    <div class="col-3 q-pa-sm">Nom</div>
                    <div class="col-2 q-pa-sm">Qte</div>
                    <div class="col-2 q-pa-sm">Achat</div>
                    <div class="col-2 q-pa-smt">Tva</div>
                    <div class="col-2 q-pa-sm text-right">Total</div>
                  </div>
                  <div v-for="(product, index) in products" :key="index" class="row">
                    <q-select
v-model="product.p" class="col-md-3 col-12 q-pa-sm truncate text-wrap" :options="products_list" :dense="true" stack-label
                              option-value="id" input-debounce="0"
                              use-input option-label="name"
                              :set="product.id = product.p.id" :rules="[val => !!val || 'Field is required']" @input="assign(index, product.p)"
                              @update:model-value="assign(index, product.p)" @filter="filterFn"  @input-value="assign(index, product.p)" />
                    <q-input v-model="product.quantity" :dense="true" class="col-md-2 col-2 q-pa-sm" type="number" :rules="[val => !!val || 'Field is required']"/>
                    <q-input v-model="product.buy" :dense="true" class="col-md-2 col-3 q-pa-sm" type="number" :rules="[val => !!val || 'Field is required']"/>
                    <q-input v-model="product.tva" :dense="true" class="col-md-2 col-2 q-pa-sm" type="number" />
                    <div class="col-md-2 text-right">
                      <br>
                      <span class="text-h6">{{(product.buy * product.quantity)+((product.buy * product.quantity) * product.tva/100)}}</span>
                    </div>
                    <div class="col-1 text-right"><br>
                      <q-btn round color="negative" size="xs" icon="remove" class="print-hide" @click="delete_product(index)" />
                    </div>
                  </div>
                  <div class="row no-padding q-mt-xs q-mb-lg">
                    <div class="col-3">
                      <q-checkbox v-model="credit" label="Credit" color="red" />
                    </div>
                    <div class="offset-md-5 col-md-3 col-12 q-pa-sx" style="text-align: right">
                      <q-input v-if="credit" v-model="avance" :dense="true" type="number" label="Avance"/><br>
                      <div class="text-h6 no-margin no-padding q-mb-lg">{{ numerique(Math.round(total)) }} FCFA</div>
                    </div>
                  </div>

                  <div class="row">
                    <q-btn v-if="validate_status" size="xs" round color="positive" icon="add" class="print-hide" @click="specialities_add" />&nbsp;&nbsp;
                    <q-btn v-if="validate_status" size="xs" class="print-hide" label="Valider" icon="save" type="submit" color="secondary"/>&nbsp;&nbsp;
                    <q-btn icon="receipt" size="xs" color="grey-10" outline label="Facture" @click="get_facture_id(facture_number); facture_status2 = true" />

                  </div>

                </q-form>
              </q-card-section>

            </q-card>

          </q-tab-panel>

        </q-tab-panels>

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture-component
name="Facture d'approvisionnement"
                               :myentreprise="entreprise" :client="fournisseur" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin';
import FactureComponent from '../components/facture_component.vue';
// import JsonExcel from 'vue-json-excel';
import vue3JsonExcel from "vue3-json-excel"

import * as _ from 'lodash';

export default {
  name: 'CommandePage',
  components: {
    'downloadExcel': vue3JsonExcel,
    FactureComponent
  },
  mixins: [basemixin],
  data () {
    return {
      tab: 'mails',
      status: '',
      entreprise: {},
      facture_number: null,
      date: '',
      dateposted: '',
      validate_status: true,
      tva_checked: false,
      facture_status2: false,
      avance: 0,
      first: 1,
      last: 30,
      name: null,
      credit: false,
      fullWidth: false,
      medium: false,
      medium2: false,
      agent: null,
      fournisseur: { id: 1, name: 'Choisissez un fournisseur' },
      fournisseur2: { id: 1 },
      product_id: null,
      quantity_id: null,
      sell: null,
      tva: 0,
      buy: null,
      categories: [],
      users: [],
      products: [{ p: { sell_price: 0, id: null, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: this.tva }, quantity: 1, buy: 0, sell: 0, tva: this.tva }],
      commands_list: [],
      products_list: [],
      products_list2: [],
      product: { description: '' },
      filter: '',
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-secondary text-white' },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-secondary text-white' },
        { name: 'id_ap', align: 'id_ap', label: 'Facture ID', field: 'id_ap', sortable: true },
        { name: 'dateposted', align: 'left', label: 'Date', field: 'dateposted', sortable: true },
        { name: 'amount', align: 'left', label: 'Quantite', field: 'amount', sortable: true },
        { name: 'p_buying_price', align: 'left', label: 'Prix Achat', field: 'p_buying_price', sortable: true },
        { name: 'tva', align: 'left', label: 'TVA', field: 'tva', sortable: true },
        { name: 'total_achat', align: 'left', label: 'total', field: 'total_achat', sortable: true },
        { name: 'f_name', align: 'left', label: 'Nom Four.', field: 'f_name', sortable: true },
        { name: 'f_last_name', align: 'left', label: 'Prenom Four.', field: 'f_last_name', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
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
      return this.products.reduce((product, item) => product + (item.buy * item.quantity + (item.buy * item.quantity * item.tva / 100)), 0);
    }
  },
  mounted () {
    this.products = [{ p: { sell_price: 0, id: null, name: 'Selectionner un produit', tva: this.tva }, quantity: 1, buy: 0, sell: 0, tva: this.tva }];
    var date = new Date();
    this.date = this.dateformat(new Date(this.get_dateposted()), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.users_get();
    this.products_get();
    this.commands_get();
    this.shop_get();
    this.dateposted = this.get_dateposted();
  },
  methods: {
    change_dateposted() {
      this.date = this.dateformat(new Date(this.dateposted), 4);
    },
    onSubmit () {
      if (this.accept !== true) {
        this.command_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    assign_fournisseur (fournisseur) {
      this.fournisseur2.id = fournisseur.id;
      this.fournisseur2.fullname = fournisseur.fullname;
      this.fournisseur2.email = fournisseur.email;
      this.fournisseur2.telephone = fournisseur.telephone;
      this.fournisseur2.telephone_code = fournisseur.telephone_code;
    },
    assign (index, p) {
      this.products[index].a = p;
      this.products[index].id = this.products[index].p.id;
      this.products[index].buy = this.products[index].p.buy_price;
      this.products[index].tva = this.products[index].p.tva;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    update_show(item) {
      this.medium2 = true;
      this.product = item;
      this.product.buying_price = item.p_buying_price;
      this.product.sell_price = item.p_sell_price;
    },
    command_post() {
      if (confirm('Voulez vous ajouter')) {
        let params = { agent: this.agent, fournisseur: this.fournisseur2.id, avance: this.avance, dateposted: this.dateposted, credit: this.credit, total: this.total, products: this.products };
        $httpService.postWithParams('/my/post/commands', params)
          .then((response) => {
            this.commands_get();
            // factureid
            let params2 = response.data;
            params2.factureid = response.factureid
            $httpService.postWithParams('/my/put/commands_credit', params2)
              .then(() => {
                window.location.reload()
              })

            this.$q.notify({ color: 'secondary', position: 'top-right', message: response.msg });
            this.validate_status = false;
            this.facture_number = response['factureid'];
          })
      }
    },
    command_update(product) {
      if (confirm('Voulez vous modifier')) {
        product.buying_price = product.p_buying_price;
        product.sell_price = product.p_sell_price;
        $httpService.postWithParams('/my/put/appro', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.commands_get();
          })
      }
    },
    command_delete(id, motif, codeAp) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.postWithParams('/my/delete/appro', { id: id, motif: motif, code_ap: codeAp })
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.commands_get();
          })
      }
    },
    users_get () {
      $httpService.getWithParams('/my/get/fournisseur')
        .then((response) => {
          this.users = response;
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
          this.products_list2 = response;
        })
    },
    commands_get () {
      $httpService.getWithParams('/my/get/commands')
        .then((response) => {
          this.commands_list = response;
        })
    },
    appro_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/appro_stats', params)
        .then((response) => {
          this.commands_list = response;
          this.nbre_achetes = _.sumBy(this.commands_list, 'amount');
          this.montant_achetes = _.sumBy(this.commands_list, 'buying_price');
        })
    },
    categories_put (id, name) {
      $httpService.putWithParams('/api/s_product_categories/' + id, { name: name })
        .then((response) => {
          this.categories_get();
          this.$q.notify({
            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
          });
        })
    },
    categories_delete (id) {
      $httpService.deleteWithParams('/api/s_product_categories/' + id)
        .then(() => {
          this.categories_get();
          this.$q.notify({
            color: 'green', position: 'top', message: 'Supprimé', icon: 'report_problem'
          });
        })
    },
    specialities_add () {
      this.products.push({ p: { sell_price: 0, id: null, name: 'Selectionner un produit' }, tva: this.tva, quantity: 1, buy: 0, sell: 0 });
    },
    specialities_delete () {
      this.products.pop();
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.facture_number = _id;
      this.factures_get_id();
    },
    factures_get_id () {
      $httpService.getWithParams('/my/get/appro_by_facture?id_ap=' + this.facture_number)
        .then((response) => {
          // this.factures_details = response;
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            response[i].p.id = response[i].product_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].buying_price;
            response[i].price = response[i].buying_price;
            response[i].p.quantity = response[i].amount;
            response[i].quantity = response[i].amount;
            response[i].p.name = response[i].p_name;
            response[i].name = response[i].p_name;
            // response[i].total = response[i].montant_vendu;
          }
          this.products = response;
          this.facture_number = this.products[0].id_ap;
          this.fournisseur = JSON.parse(response[0]['fournisseur']);
        })
    },
    delete_product(i) {
      this.products = this.products.filter((x) => {
        return x.id !== this.products[i].id;
      });
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.products_list = this.products_list2.filter(
          (v) => {
            return v.name.toLocaleLowerCase().indexOf(needle) > -1
            // return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1
          }
        );
      })
    }
  }
}
</script>

<style>
/*::-webkit-scrollbar {*/
/*    display: none; !* Chrome Safari *!*/
/*}*/
</style>
