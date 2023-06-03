<template>
  <q-page>
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-10 col-12">

        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture
              name="Facture de devis" type="proforma"
              :entreprise="entreprise" :client="client" :facturenum="facture_number" :products="products" />
          </q-card>
        </q-dialog>

        <q-dialog v-model="fullWidth" position="top">
          <q-card id="facture" style="width: 1000px; max-width: 100%;" :flat="true">
            <q-card-section contenteditable="true">
              <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6 text-right float-right">
                  <div class="float-right q-mb-sm print-hide" style="width: 50%; position:relative;">
                    <q-input v-model="dateposted" stack-label type="datetime-local" label="Date" :dense="true"></q-input>
                    <q-select
                      v-model="projetid" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                      :dense="true" :options="p_projets" label="Projets" :option-value="'id'" :option-label="'titre'"
                      input-debounce="0" />
                    <q-select
                      v-model="client" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                      :dense="true" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                      input-debounce="0" :option-label="'fullname'"
                      :rules="[val => !!val || 'Ce champs est requis']"
                      @update:model-value="assign_client(client)" />
                  </div>
                  <div class="row float-right q-mt-sm">
                    <div class="col-12">Proforma #:
                      <input v-model="facture_number" style="border: 0; border-bottom: 1px dashed grey" />
                    </div>
                    <div class="col-12">Appel d'offre/DA:
                      <input v-model="bl" style="border: 0; border-bottom: 1px dashed grey" />
                    </div>
                  </div>
                </div>
              </div>
            </q-card-section>

            <q-card-section>
              <q-form  class="" @submit="onSubmit">
                <br>
                <div>
                  <div v-for="(product, index) in products" :key="index" class="row q-pa-sm">
                    <q-select
                      v-model="product.product_id" class="col-4 no-margin" map-options emit-value :dense="true"
                      option-value="id" option-label="name" stack-label input-debounce="0" label="produits"
                      :options="products_list" />
                    <q-input v-model="product.quantite_vendu" class="col-1 row q-pl-sm" autocomplete type="number" label="Quantité" :dense="true" />
                    <q-input v-model="product.prix_unitaire" class="col-2 row q-pl-sm" autocomplete type="number" label="Prix" :dense="true" />
                    <q-input v-model="product.tva" class="col-2 row q-pl-sm" autocomplete type="number" label="TVA" :dense="true" />
                    <q-input class="col-2 q-pl-sm" :dense="true" type="number" :label="calculTotal(product.prix_unitaire, product.quantite_vendu, product.tva)" disable disabled="" />
                    <div class="col-1 row q-pl-xs">
                      <br>
                      <q-btn flat size="sm" color="secondary" icon="edit" @click="devis_put(product)" />
                      <q-btn flat size="sm" color="negative" icon="remove" @click="devis_delete(product.id)" />
                    </div>
                  </div>
                </div>

                <div>
                  <div v-for="(product, index) in products2" :key="index" class="row q-pa-sm">
                    <q-select
                      v-model="product.product_id" class="col-4 q-pa-sm" :options="products_list"
                      option-label="name" option-value="id" use-input input-debounce="0"
                      :dense="true" @filter="filterFn" @update:model-value="assign(index)" />
                    <q-input v-model="product.quantity" class="col-1 q-pa-sm" :dense="true" type="number" label="Quantité" @focusout="getVal(index, product.quantity)" />
                    <q-input v-model="product.p.sales_price" class="col-2 q-pa-sm" :dense="true" type="number" label="Prix Unitaire" />
                    <q-input v-model="product.p.tva" class="col-2 q-pa-sm" :dense="true" type="number" label="TVA" />
                    <q-input class="col-2 q-pa-sm" :dense="true" type="number" :label="calculTotal(product.p.sales_price, product.quantity, product.p.tva)" disable disabled="" />
                    <div class="col-1"><br>
                      <q-btn round color="negative" size="xs" icon="remove" class="print-hide" @click="delete_product(index)" />
                    </div>
                  </div>
                </div>

                <div class="row q-mt-xs" style="min-height: 20px">
                  <h6 class="col-3 no-padding"> </h6>
                  <h6 class="offset-5 text-right col-4 no-padding">{{ total}} FCFA </h6>
                </div>

                <div v-if="validate_status" class="row">
                  <q-btn class="print-hide" round color="positive" size="xs" icon="add" @click="specialities_add2()" />&nbsp;&nbsp;
                  <q-btn v-if="add_status" class="print-hide" label="Valider" size="xs" icon="save" type="submit" color="secondary" />&nbsp;&nbsp;
                  <q-btn
                    v-if="!add_status" class="print-hide" label="Convertir en Vente" size="xs" type="button" color="teal-9"
                    @click="convertir_post()" />
                </div>

              </q-form>
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn v-close-popup flat label="Fermer" />
              <q-btn flat label="Imprimer" @click="imprimer()" />
            </q-card-actions>
          </q-card>
        </q-dialog>

        <q-dialog v-model="fileStatus">
          <q-card style="width: 600px" class="q-pa-lg">
            <filescomponent type="devis" :typeid="devisId" folder="devis" />
          </q-card>
        </q-dialog>

        <q-btn
          class="q-mb-sm" size="sm" label="Ajouter" icon="add" color="secondary"
          @click="fullWidth = true; validate_status = true; add_status = true; sales_list = []" /><br>

        <div class="row">
          <div class="col-12 q-pa-lg">
            <q-input
              v-model="search" class="row" autocomplete type="search"
              label="Rechercher" @keyup="facture_filter_get(search)" />
          </div>
          <div
            v-for="(item, index) in sales_list"
            :key="index" class="col-lg-3 col-md-4 col-sm-6 col-12 q-pa-md">
            <q-card class="q-pa-lg">
              <q-card-section>
                Devis N° {{item.id_vente}}<br>
                <div v-if="item.name">
                  {{item.name}} {{item.last_name}}<br>
                  {{dateformat(item.date_posted, 2)}}
                </div>
              </q-card-section>
              <q-card-actions>
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
import Filescomponent from "components/filescomponent.vue";
import apimixin from "src/services/apimixin";
export default {
  name: 'DevisPage',
  components: {
    Filescomponent,
    'facture': FactureComponent
  },
  mixins: [basemixin, apimixin],
  data () {
    return {
      search: null,
      fileStatus: false,
      validate_status: true,
      add_status: true,
      fullWidth: false,
      credit: false,
      facture_status2: false,
      avance: 0,
      agent: null,
      facture_number: null,
      projetid: null,
      p_projets: [],
      formData: {},
      date: '',
      dateposted: '',
      devisId: 1,
      myclient: {},
      client: 1,
      client2: { id: null },
      bc: '',
      bl: '',
      clients: [],
      products: [],
      products2: [],
      sales_list: [],
      sales_init: [],
      products_list: [],
      products_list2: [],
      appro_list: [{ p: { sell_price: 0 } }],
      appro_list2: [{ p: { sell_price: 0 } }],
      entreprise: {}
    }
  },
  computed: {
    total() {
      return this.products2.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity /100)), 0);
    }
  },
  created () {
    this.formData = new FormData();
    var date = new Date();
    this.clients_get();
    this.products_get();
    this.sales_get();
    this.p_projet_get();
    this.dateposted = date.toISOString().slice(0, 16);
  },
  methods: {

    p_projet_get () {
      $httpService.getApi('/my/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },

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
      });
      this.sales_list = val1;
    },

    assign(index) {
      this.products2[index].p = this.products2[index].product_id
      this.products2[index].p.sales_price = this.products2[index].product_id.price
      this.products2[index].productid = this.products2[index].product_id.id
    },

    assign_client (client) {
      this.clientId = client.id;
      this.myclient.id = client.id;
      this.myclient.fullname = client.fullname;
      this.myclient.email = client.email;
      this.myclient.telephone = client.telephone;
      this.myclient.telephone_code = client.telephone_code;
      this.products_get();
    },
    clients_get () {
      $httpService.getWithParams('/my/get/client')
        .then((response) => {
          this.clients = [{id: 0, name: 'selectionner un client', fullname: 'selectionner un client'}, ...response];
          this.client = this.clients[0];
          this.client2 = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
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
      // let params = { agent: this.agent, products: this.products2, clientid: this.client2.id, credit: this.credit, avance: this.avance, total: this.total, bl: this.bl, bc: this.bc };
      let params = { agent: this.agent, products: this.products2, clientid: this.clientId, credit: this.credit, avance: this.avance, total: this.total, bl: this.bl, bc: this.bc, id_vente: this.facture_number };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/devis', params)
          .then((response) => {
            var status = response['status'];
            if (status == 1) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem', timeout: 10000
              });
              this.devis_get_by(this.facture_number);
              // this.facture_number = response['factureid'];
              this.validate_status = false;
              // this.sales_get();
              window.location.reload()
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
              // window.location.reload()
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
            this.products_list = products;
            this.products_list2 = products;
            console.log(this.products_list);
          })
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
          this.client2 = JSON.parse(response[0]['client']);
          this.myclient = JSON.parse(response[0]['client']);
          this.date = response[0]['date_posted'];
          this.bc = response[0]['bc'];
          this.bl = response[0]['bl'];

          for (let i = 0; i < response.length; i++) {
            response[i].price = response[i].prix_unitaire;
            response[i].quantity = response[i].quantite_vendu;
            // response[i].name = response[i].p_name;
            response[i].total = response[i].montant_vendu;
          }
        })
    },
    specialities_add2 () {
      this.products2.push({ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
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
