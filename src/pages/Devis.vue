<template>
  <q-page>
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-10 col-12">

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture name="Facture de devis" type="proforma"
                     :entreprise="entreprise" :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

        <q-dialog v-model="fullWidth" position="top">
          <q-card style="width: 1000px; max-width: 100%;" id="facture" :flat="true">
            <q-card-section contenteditable="true">
              <div class="row">
                <div class="col-6 alignleft">
                  <!--                  <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>-->
                  <!--                  <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>-->
                  <img src="~assets/fmmi-logo.jpeg" style="width: 100px; height: 100px; object-fit: cover"/>
                  <!--  <div>{{entreprise.name}}</div>-->
                  <div>{{entreprise.telephone}}</div>
                  <div>{{entreprise.email}}</div>
                  <div>BL: <input v-model="bl" style="border: 0; border-bottom: 1px dashed grey" /> </div>
                  <div>BC: <input v-model="bc" style="border: 0; border-bottom: 1px dashed grey" /> </div>
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
                <div>

                  <div class="row q-pa-sm" v-for="(product, index) in products" :key="index">
                    <q-select class="col-4 no-margin" v-model="product.product_id" use-input map-options emit-value :dense="true" @filter="filterFn"
                              option-value="id" option-label="name" stack-label input-debounce="0" label="produits" :options="products_list" />
                    <q-input class="col-1 row q-pl-sm" autocomplete type="number" v-model="product.quantite_vendu" label="Quantité" :dense="true" />
                    <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.prix_unitaire" label="Prix" :dense="true" />
                    <q-input class="col-2 row q-pl-sm" autocomplete type="number" v-model="product.tva" label="TVA" :dense="true" />
                    <q-input class="col-2 q-pl-sm" :dense="true" type="number" :label="calculTotal(product.prix_unitaire, product.quantite_vendu, product.tva)" disable disabled="" />
                    <div class="col-1 row q-pl-xs">
                      <br>
                      <q-btn flat size="sm" color="secondary" v-on:click="devis_put(product)" icon="edit" />
                      <q-btn flat size="sm" color="negative" v-on:click="devis_delete(product.id)" icon="remove" />
                    </div>
                  </div>
                </div>

                <div>
                  <div class="row q-pa-sm" v-for="(product, index) in products2" :key="index">
                    <q-select class="col-4 q-pa-sm" v-model="product.p" :options="products_list" option-value="id" use-input @filter="filterFn"
                              input-debounce="0" option-label="prodcat" @focusout="assign2(index)" @input="assign2(index)" :dense="true" />
                    <q-input class="col-1 q-pa-sm" :dense="true" type="number" v-model="product.quantity" @focusout="getVal(index, product.quantity)" label="Quantité" />
                    <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.p.sales_price" label="Prix Unitaire" />
                    <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.p.tva" label="TVA" />
                    <q-input class="col-2 q-pa-sm" :dense="true" type="number" :label="calculTotal(product.p.sales_price, product.quantity, product.p.tva)" disable disabled="" />
                    <div class="col-1"><br>
                      <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-on:click="delete_product(index)" />
                    </div>
                  </div>
                </div>

                <div class="row q-mt-xs" style="min-height: 20px">
                  <h6 class="col-3 no-padding"> </h6>
                  <h6 class="offset-5 text-right col-4 no-padding">{{ total}} FCFA </h6>
                  <!--                  <h6 class="offset-5 text-right col-4 no-padding">{{numerique(Math.round(total)) }} FCFA </h6>-->
                </div>

                <div class="row" v-if="validate_status">
                  <q-btn class="print-hide" round color="positive" size="xs" icon="add" v-on:click="specialities_add2()" />&nbsp;&nbsp;
                  <q-btn v-if="add_status" class="print-hide" label="Valider" size="xs" icon="save" type="submit" color="secondary" />&nbsp;&nbsp;
                  <q-btn v-if="!add_status" class="print-hide" label="Convertir en Vente" size="xs" type="button" color="teal-9"
                         v-on:click="convertir_post()" />
                </div>

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn flat label="Fermer" v-close-popup />
              <q-btn flat label="Imprimer" v-on:click="imprimer()" />
            </q-card-actions>
          </q-card>
        </q-dialog>


        <q-dialog v-model="fileStatus">
          <q-card style="width: 600px" class="q-pa-lg">
            <filescomponent type="devis" :typeid="devisId" folder="devis" />
          </q-card>
        </q-dialog>

<!--        <q-dialog v-model="fileStatus">-->
<!--          <q-card style="width: 500px; max-width: 100%;" id="fichiers" :flat="true">-->
<!--            <q-card-section>-->
<!--              <form method="post" enctype="multipart/form-data">-->
<!--&lt;!&ndash;                <q-input type="file" id="doc" name="doc" @change="changePhoto" />&ndash;&gt;-->
<!--                <q-input dense v-model="fileTitre" label="titre" />-->
<!--                <br>-->
<!--                <input type="file" ref="doc" id="doc" name="doc" @change="changePhoto" />-->
<!--                <br>-->
<!--                <br>-->
<!--                <q-btn  color="secondary" class="full-width" size="sm" label="Envoyer" @click="sendFile()" />-->
<!--              </form>-->
<!--            </q-card-section>-->
<!--            <q-card-section>-->
<!--              <q-list bordered padding class="rounded-borders">-->
<!--                <q-item clickable v-ripple v-for="(myfile, key) in files" :key="key">-->
<!--                  <q-item-section avatar top>-->
<!--                    <q-avatar color="primary" text-color="white">{{myfile.extension}}</q-avatar>-->
<!--                  </q-item-section>-->

<!--                  <q-item-section>-->
<!--                    <q-item-label lines="1">{{myfile.titre}}</q-item-label>-->
<!--                    <q-item-label caption>{{myfile.date}}</q-item-label>-->
<!--                  </q-item-section>-->

<!--                  <q-item-section side>-->
<!--                    <a target="_blank" :href="'https://fmmi.ci/apistock/public/assets/uploads/devis/'+myfile.file">-->
<!--                      <q-icon name="visibility" color="primary" />-->
<!--                    </a>-->
<!--                  </q-item-section>-->
<!--                </q-item>-->
<!--              </q-list>-->
<!--            </q-card-section>-->
<!--          </q-card>-->
<!--        </q-dialog>-->

        <q-btn class="q-mb-sm" size="sm" label="Ajouter" icon="add" color="secondary"
               @click="fullWidth = true; validate_status = true; add_status = true; sales_list = []" /><br>

        <div class="row">
          <div class="col-12 q-pa-lg">
            <q-input class="row" autocomplete type="search" v-model="search"
                     v-on:keyup="facture_filter_get(search)" label="Rechercher" />
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-12 q-pa-md"
               v-for="(item, index) in sales_list" :key="index">
            <q-card class="q-pa-lg">
              <q-card-section>
                Devis N° {{item.id_vente}}<br>
                <div v-if="item.name">
                  {{item.name}} {{item.last_name}}<br>
                  {{dateformat(item.date_posted, 2)}}
                </div>
              </q-card-section>
              <q-card-actions>
<!--                <q-btn flat icon="image" @click="fileStatus=true; devisId=item.id_vente; files_get(item.id_vente)"></q-btn>-->
                <q-btn flat icon="image" @click="fileStatus=true; devisId=item.id_vente;"></q-btn>
                <q-btn flat icon="edit" @click="fullWidth = true; add_status = false; devis_get_by(item.id_vente)"></q-btn>
                <q-btn flat icon="receipt" @click="facture_status2 = true; devis_get_by(item.id_vente)"></q-btn>
              </q-card-actions>
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
import FactureComponent from '../components/facture_component.vue';
import axios from "axios";
import {postApi} from "src/services/apiService";
import Filescomponent from "components/filescomponent.vue";
export default {
  name: 'DevisPage',
  data () {
    return {
      filter: '',
      fitst: 1,
      last: 30,
      name: null,
      search: null,
      grid: false,
      fileStatus: false,
      validate_status: true,
      add_status: true,
      fullWidth: false,
      medium: false,
      medium2: false,
      credit: false,
      solde: true,
      facture_status2: false,
      avance: 0,
      agent: null,
      fournisseur: null,
      product_id: null,
      facture_number: '',
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      formData: {},
      fileTitre: '',
      files: [],
      date: '',
      devisId: 1,
      client: 1,
      client2: { id: null },
      image: '',
      bc: '',
      bl: '',
      users: [],
      clients: [],
      // products: [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }],
      products: [],
      products2: [],
      sales_list: [],
      sales_init: [],
      products_list: [],
      products_list2: [],
      appro_list: [{ p: { sell_price: 0 } }],
      appro_list2: [{ p: { sell_price: 0 } }],
      product: { description: '' },
      data: [],
      entreprise: {}
    }
  },
  components: {
    Filescomponent,
    'facture': FactureComponent
  },
  mixins: [basemixin],
  created () {
    this.formData = new FormData();
    var date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth()), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.shop_get();
    this.clients_get();
    this.products_get();
    this.sales_get();
  },
  computed: {
    total() {
      return this.products2.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity /100)), 0);
    }
  },
  methods: {
    calculTotal(qte, prix, tva) {
      return (qte * prix) + (qte * prix * tva)/100
    },
    onSubmit () {
      if (this.accept !== true) {
        this.sales_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    facture_filter_get() {
      const val1 = this.sales_init.filter((x) => {
        return x.id_vente.toLowerCase().includes(this.search.toLowerCase())
          || x.fullname.toLowerCase().includes(this.search.toLowerCase())
          // || x.last_name.toLowerCase().toString().includes(this.search.toLowerCase());
      });
      this.sales_list = val1;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign (product, index) {
      this.products[index].quantite_vendu = 1;
      this.products[index].prix_unitaire = this.products[index].p.sales_price;
      this.products[index].remise_totale = 0;
    },
    // assign2 (product, index) { },
    assign_client (client) {
      this.client2.id = client.id;
      this.client2.fullname = client.fullname;
      this.client2.email = client.email;
      this.client2.telephone = client.telephone;
      this.client2.telephone_code = client.telephone_code;
    },
    qr_get(dataUrl) {
      this.image = dataUrl;
    },
    clients_get () {
      $httpService.getWithParams('/my/get/client')
        .then((response) => {
          this.clients = response;
          this.client = this.clients[0];
          this.client2 = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    update_show(item) {
      this.medium2 = true;
      this.product = item;
    },

    convertir_post() {
      let params = { agent: this.agent,
        products: this.products,
        id_vente: this.facture_number,
        clientid: this.client2.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total
      };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/devis_convert', params)
          .then((response) => {
            var status = response['statut'];
            if (status == !0) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },

    sales_post() {
      let params = { agent: this.agent, products: this.products2, clientid: this.client2.id, credit: this.credit, avance: this.avance, total: this.total, bl: this.bl, bc: this.bc };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/devis', params)
          .then((response) => {
            var status = response['status'];
            if (status == 1) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem', timeout: 10000
              });
              window.location.reload()
              this.devis_get_by(this.facture_number);
              // this.facture_number = response['factureid'];
              this.validate_status = false;
              this.sales_get();
            } else {
              window.location.reload()
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },

    devis_put(devis) {
      if (confirm('Voulez vous modifier ?')) {
        $httpService.postWithParams('/my/put/devis', devis).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.sales_get();
        })
      }
    },

    devis_delete(_id) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.deleteWithParams('/my/delete/devis/'+_id).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.sales_get();
        })
      }
    },

    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products_list = response;
          this.products_list2 = response;
        })
    },

    sales_get () {
      $httpService.getWithParams('/my/get/devis')
        .then((response) => {
          this.sales_init = response;
          this.sales_list = response;
        })
    },

    devis_get_by (idvente) {
      $httpService.getWithParams('/my/get/devis/' + idvente)
        .then((response) => {
          this.products = response;
          this.facture_number = idvente;
          this.client = JSON.parse(response[0]['client']);

          for (let i = 0; i < response.length; i++) {
            response[i].price = response[i].prix_unitaire;
            response[i].quantity = response[i].quantite_vendu;
            // response[i].name = response[i].p_name;
            response[i].total = response[i].montant_vendu;
          }
        })
    },

    sales_stats_get() {
      let params = { 'first': this.first, 'last': this.last, 'magasin_id': 1 };
      $httpService.getWithParams('/my/get/sales_stats', params)
        .then((response) => {
          this.sales_list = response;
          this.nbre_vendus = _.sumBy(this.sales_list, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_list, 'montant_vendu');
        })
    },


    specialities_add () {
      this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
    },
    specialities_add2 () {
      this.products2.push({ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
    },
    specialities_delete () {
      this.products.pop();
    },
    imprimer() {
      window.print();
    },
    getVal(index, val) {
      this.products[index].quantity = parseInt(val);
      this.products[index].p.quantity = parseInt(val);

      this.$nextTick(() => {
        this.products[index].quantity = parseInt(val);
        this.products[index].p.quantity = parseInt(val);
      })
    },

    changePhoto($event) {
      const file = $event.target.files[0];
      this.formData.append('doc', file);
    },

    sendFile() {
      this.formData.append('devis_id', this.devisId);
      this.formData.append('titre', this.fileTitre);
      // postApi('/my/post/devis_file', this.formData).then((response) => {
      //   console.log(response.data);
      // })
      axios.post('https://fmmi.ci/apistock/api/post/devis_file', this.formData)
        .then((response) => {
          this.showAlert(response.data)
          this.files_get(this.devisId);
          this.fileTitre = '';
          this.formData = {};
        })
    },

    files_get (_devisId) {
      $httpService.getWithParams('/api/get/devis_file/'+_devisId)
        .then((response) => {
          this.files = response;
        })
    },

    startDownload() {
      confirm('Voulez-vous generer');
      return false;
    },
    delete_product(i) {
      this.products2 = this.products2.filter((x) => {
        return x.p.id !== this.products2[i].p.id;
      });
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.products_list = this.products_list2.filter(
          (v) => {
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1
          }
        );
      })
    }
  }
}
</script>

<style scoped>
::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
.no-border {
  border: 0px solid grey;
}
</style>
