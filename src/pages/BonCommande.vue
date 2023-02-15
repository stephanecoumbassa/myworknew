<template>
  <q-page>
    <div class="row justify-center">
      <div class="col-11"><br>
        <span class="text-h6">Bon de commande</span><br>
        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1000px; max-width: 100%;" id="facture" :flat="true">

            <q-card-section>
              <q-form  @submit="onSubmit" class="q-gutter-md">
                <div class="row print-hide q-mb-lg">

                  <div class="col-3 q-ma-md">
                    <q-checkbox :dense="true" color="teal" v-model="tva_checked" label="Tva Pour tous les champs" />
                  </div>
                  <div class="col-2">
                    <q-input :dense="true" type="number" v-model="tva" label="TVA en décimal" />
                  </div>
                  <div class="col-4">
                    <q-select filled map-options emit-value v-model="fournisseur"  :options="users" label="Fournisseur" :option-value="JSON.stringify(fournisseur)" stack-label input-debounce="0"
                              option-label="name" :dense="true" @input="assign_fournisseur(fournisseur)" :rules="[val => !!val || 'Ce champs est requis']" />
                  </div>
                </div>
                <div class="row" >
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 alignleft">
                    <img :src="'https://www.affairez.com/apistock/public/assets/uploads/magasin/'+entreprise.logo"
                         style="width: 100px; height: 100px; object-fit: cover"/>
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

                <div class="row" v-for="(product, index) in products" :key="index">
                  <q-select class="col-4 q-pa-sm" v-model="product.product_id"  :options="products_list" label="Produits" :dense="true"
                            option-value="id" input-debounce="0" @input="assign(index, product.p)" use-input @filter="filterFn" map-options
                            option-label="prodcat" :rules="[val => !!val || 'Field is required']" />
                  <q-input padding :dense="true" class="col-2 q-pa-sm" type="number" v-model="product.amount" label="Quantité" :rules="[val => !!val || 'Field is required']"/>
                  <q-input padding :dense="true" class="col-2 q-pa-sm" type="number" v-model="product.buying_price" label="Prix d'achat"  :rules="[val => !!val || 'Field is required']"/>
                  <q-input padding :dense="true" class="col-3 q-pa-sm" type="number" :value="(product.buying_price * product.amount)+((product.buying_price * product.amount) * product.tva/100)" label="total"  :rules="[val => !!val || 'Field is required']"/>
                  <div class="col-1"><br>
                    <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-on:click="delete_product(index)" />
                  </div>
                </div>
                <div class="row no-padding q-mt-xs q-mb-lg" style="height: 70px">
                  <div class="col-3">
                    <q-checkbox v-model="credit" label="Credit" color="red" />
                  </div>
                  <div class="offset-6 col-3 q-pa-sm">
                    <q-input v-if="credit" :dense="true" type="number" v-model="avance" label="Avance"/><br>
                    <h6 class="no-margin no-padding q-mb-lg">{{ numerique(Math.round(total)) }} FCFA</h6>
                  </div>
                </div>

                <div class="row" v-if="validate_status">
                  <q-btn round color="positive" size="sm" icon="add" class="print-hide" v-on:click="specialities_add" />&nbsp;&nbsp;
                  <q-btn class="print-hide" size="sm" label="Valider" icon="save" type="submit" color="secondary"/>
                </div>

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn flat label="Fermer" v-close-popup />
              <q-btn flat label="Telecharger" v-on:click="download()" />
              <q-btn class="print-hide" flat label="Imprimer" v-on:click="imprimer()" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="medium2"  >
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6">Modifier un approvisinnement</div>
            </q-card-section>

            <q-card-section>
              <q-form  @submit="onSubmit" class="q-gutter-md">
                <q-select filled map-options emit-value v-model="product.f_id"  :options="users" label="Fournisseur" option-value="id" option-label="name"  :rules="[val => !!val || 'Field is required']" />
                <q-select class="col-2 row q-pl-sm"  v-model="product.p_id" map-options emit-value option-value="id" option-label="name" stack-label input-debounce="0" :options="products_list" />
                <q-input padding class="col-2 q-pa-sm" type="number" v-model="product.amount" label="Quantité" :rules="[val => !!val || 'Field is required']" />
                <q-input padding class="col-2 q-pa-sm" type="number" v-model="product.p_buying_price" label="Prix d'achat" :rules="[val => !!val || 'Field is required']" />
                <q-input padding class="col-2 q-pa-sm" type="number" v-model="product.tva" label="tva" />
                <q-input padding class="col-2 q-pa-sm" type="number" v-model="product.p_sell_price" label="Prix de vente" />
              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn label="Modifier" icon="edit" type="submit" color="secondary" v-on:click="command_update(product)" />
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-btn label="Ajouter" class="q-mb-sm" size="sm" icon="add" color="secondary" @click="fullWidth = true" />
        <div class="row q-pa-xs">
          <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col q-pa-sm"><br><q-btn color="primary" label="filtrer" v-on:click="appro_stats_get()" /></div>
        </div>

        <div class="row">
          <div class="col-12 q-pa-lg">
            <q-input class="row" autocomplete type="search" v-model="search"
                     v-on:keyup="facture_filter_get(search)" label="Rechercher" />
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12 q-pa-md"
               v-for="item in bons" :key="item.id_vente" @click="fullWidth = true; add_status = false; bon_get_by(item.id_ap)">
            <q-card class="q-pa-lg">
              Devis N°
              {{item.id_ap}}<br><br>
              <span class="float-right" v-if="item.name">
                {{item.name}} {{item.last_name}}<br>
                <!--                {{dateformat(item.date_posted, 2)}}-->
              </span>
              <br>
            </q-card>
          </div>
        </div>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin';
import * as _ from 'lodash';
export default {
  name: 'BonPage',
  data () {
    return {
      status: '',
      entreprise: {},
      facture_number: null,
      date: '',
      validate_status: true,
      tva_checked: false,
      avance: 0,
      first: 1,
      last: 30,
      name: null,
      credit: false,
      fullWidth: false,
      medium: false,
      medium2: false,
      agent: null,
      search: null,
      fournisseur: null,
      fournisseur2: {},
      product_id: null,
      quantity_id: null,
      sell: null,
      tva: 0,
      buy: null,
      categories: [],
      bons: [],
      users: [],
      products: [{ p: { sell_price: 0, id: null, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: this.tva }, quantity: 1, buy: 0, sell: 0, tva: this.tva }],
      commands_list: [],
      products_list: [],
      products_list2: [],
      product: { description: '' },
      filter: '',
      data: []
    }
  },
  mixins: [basemixin],
  components: {
    // 'downloadExcel': JsonExcel
  },
  created () {
    this.products = [{ p: { sell_price: 0, id: null, name: 'Selectionner un produit', tva: this.tva, quantity: 1, buying_price: 0 }, quantity: 1, buying_price: 0, sell: 0, tva: this.tva }];
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.users_get();
    this.products_get();
    this.commands_get();
    this.shop_get();
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.buying_price * item.amount + (item.buying_price * item.amount * item.tva / 100)), 0);
    }
  },
  methods: {
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
      // console.log(this.products[index]);
      // console.log(this.products[index].product_id);
      this.products[index].a = p;
      this.products[index].id = this.products[index].product_id.id;
      this.products[index].buy_price = this.products[index].product_id.price;
      this.products[index].amount = 1;
      this.products[index].tva = this.products[index].product_id.tva;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    bon_get_by (idvente) {
      $httpService.getWithParams('/my/get/bon/' + idvente)
        .then((response) => {
          this.products = response;
          this.facture_number = idvente;
          this.fournisseur = JSON.parse(response[0]['fournisseur']);
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
        let params = { agent: this.agent, fournisseur: this.fournisseur2.id, avance: this.avance, credit: this.credit, total: this.total, products: this.products };
        $httpService.postWithParams('/my/post/bon', params)
          .then((response) => {
            this.commands_get();
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
        $httpService.postWithParams('/my/put/bon', product)
          .then((response) => {
            this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
            this.commands_get();
          })
      }
    },
    command_delete(id, motif, codeAp) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.postWithParams('/my/delete/bon', { id: id, motif: motif, code_ap: codeAp })
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
      $httpService.getWithParams('/my/get/bon')
        .then((response) => {
          this.bons = response;
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
    facture_filter_get() {
      const val1 = this.sales_init.filter((x) => {
        return x.id_vente.toString().includes(this.search) || x.name.toString().includes(this.search) ||
          x.last_name.toString().includes(this.search);
      });
      this.sales_list = val1;
    },
    specialities_add () {
      this.products.push({ p: { sell_price: 0, id: null, name: 'Selectionner un produit' }, tva: this.tva, quantity: 1, buy: 0, sell: 0 });
    },
    specialities_delete () {
      this.products.pop();
    },
    imprimer() {
      window.print();
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
            // return v.name.toLocaleLowerCase().indexOf(needle) > -1
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1
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
