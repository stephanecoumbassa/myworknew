<template>

  <q-card id="facture" style="width: 1300px; max-width: 100%;" :flat="true">
    <q-form  class="q-gutter-md" @submit="onSubmit">

      <q-card-section class="no-margin q-pt-lg q-mt-lg">
        <div class="row q-pt-lg">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 alignleft hidden-xs mobile-hide">
            <q-input v-model="bl" label="BC" dense outlined />
            <q-input v-model="bc" label="BL" dense outlined />
            <q-input v-model="condition" label="Texte Condition de paiement" dense outlined />
            <q-input v-model="delai" type="date" stack-label label="Délai de livraison" dense outlined />
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 content-center text-center print-only">
          </div>
          <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right float-right" style="min-width: 300px">
            <div class="float-right q-mb-sm print-hide" style="width: 50%; position:relative;">
              <q-input v-model="dateposted" stack-label type="datetime-local" label="Date" :dense="true"></q-input>
              <q-select
                v-if="status_download" v-model="projetid" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                :dense="true" :options="p_projets" label="Projets" :option-value="'id'" :option-label="'titre'"
                input-debounce="0" />
              <q-select
                v-if="status_download" v-model="client" class="print-hide col-md-6 col-sm-12" :dense="true" filled map-options
                emit-value :options="clients" label="Clients" option-value="id" option-label="fullname"
                :rules="[val => !!val || 'Ce champs est requis']"
                @update:model-value="assign_client(client)" />
            </div>
            <div class="row float-right q-mt-sm hidden-sm hidden-xs mobile-hide">
            </div>

          </div>
        </div>
      </q-card-section>

      <q-card-section class="no-margin q-mt-lg q-pt-lg">
        <br>
        <br>
        <div class="row no-margin no-padding mobile-hide hidden-sm hidden-xs" style="height: 47px">
          <div class="col-4 q-pa-sm">Nom</div>
          <div class="col-1 q-pa-sm">Qte</div>
          <div class="col-2 q-pa-sm">Tva</div>
          <div class="col-2 q-pa-sm">Prix Uni</div>
          <div class="col-2 q-pa-sm">Total</div>
        </div>
        <div v-for="(prod, index) in products" :key="index" class="row q-mb-lg">
          <q-select
            v-model="prod.product_id" class="col-md-4 col-12 q-pa-sm truncate text-wrap" :options="appro_list"
            option-value="id" option-label="name" use-input :dense="true"
            @filter="filterFn" @update:model-value="assign(index)" />
          <q-input
            v-model="prod.quantity" class="col-md-1 col-2 q-pa-sm" :dense="true" type="number" minlength="1"
            aria-valuemin="0" hint="qty" @input="assign(index)" @focusout="getVal(index, prod.quantity)" />
          <q-input v-model="tva" class="col-2 q-pa-sm" :dense="true" type="number" hint="tva" />
          <q-input v-model="prod.p.sales_price" class="col-md-2 col-3 q-pa-sm" :dense="true" type="number" hint="prix" />
          <q-input
            class="col-md-2 col-4 q-pa-sm" :dense="true" type="number"
            :model-value="(prod.p.sales_price * prod.quantity) + (prod.p.sales_price * prod.quantity * tva)/100" />
          <div class="col-1"><br>
            <q-btn v-if="status_download" round color="negative" size="xs" icon="remove" class="print-hide" @click="delete_product(index)" />
            &nbsp;
            <q-btn round color="dark" size="xs" icon="description" class="print-hide"  @click="prod.showdesc = !prod.showdesc" />
          </div>
          <div v-if="prod.showdesc" class="col-12 q-pa-sm bg-grey-2">
            <q-input v-model="prod.details" filled autogrow />
          </div>
        </div>
        <div class="row no-padding q-mt-xs q-mb-lg">
          <div v-if="status_download" class="col-2">
            <q-checkbox v-model="credit" label="Payé par échéance" color="primary" />
          </div>
          <div v-if="status_download" class="col-2">
            <q-input
              v-model="creditNumber" type="number" label="Nombre d'échéances" color="primary" dense
              @change="changeNumber(creditNumber)" />
          </div>
        </div>

        <div v-if="credit">
          <div v-for="fac in versements" :key="fac.id" class="row">
            <q-input v-model="fac.echeance" filled class="col-1 q-ma-sm" dense label="Date Echéance" type="date" stack-label  />
            <q-input
              v-model="fac.pourcentage" filled class="col-1 q-ma-sm" dense label="Pourcentage" type="number"
              stack-label
              @update:model-value="fac.montant_echeance=(total * fac.pourcentage)/100" />
            <q-input
              v-model="fac.montant_echeance" filled class="col-1 q-ma-sm" dense label="Montant Echéance" stack-label
              type="number" />
            &nbsp;
            &nbsp;
            <q-select
              v-model="fac.paiement" class="col-1 q-ma-sm" dense label="Type paiement" stack-label
              :options="['virement', 'cheque', 'espece']" />
            <q-input v-model="fac.date" class="col-1 q-ma-sm" dense label="Date Vers" type="date" stack-label  />
            <q-input v-model="fac.montant" class="col-1 q-ma-sm" dense label="Montant Vers" stack-label type="number" />
            <q-input v-model="fac.numero" class="col-1 q-ma-sm" dense label="N°Chèque/Virement" />
            <q-input v-model="fac.banque" class="col-1 q-ma-sm" dense label="Banque" />
            <q-input v-model="fac.emission" class="col-1 q-ma-sm" dense label="Date Emission" type="date" stack-label  />
          </div>

        </div>

        <br>
        <br>
        <br>
        <div v-if="status_download" class="row q-pa-sm q-mt-4" style="margin-top: 30px">
          <q-btn class="print-hide" round color="positive" size="sm" icon="add" @click="specialities_add" />&nbsp;&nbsp;
          <q-btn class="print-hide" color="secondary" label="Valider" size="sm" icon="save" type="submit"  />&nbsp;&nbsp;
          <q-btn icon="receipt" color="grey-10" outline label="Facture" @click="get_facture_id(facture_number); facture_status2 = true" />
        </div>
      </q-card-section>

    </q-form>
  </q-card>

</template>

<script>
// import vue3JsonExcel from "vue3-json-excel";
// import FactureComponent from "components/facture_component.vue";
import basemixin from "pages/basemixin";
import apimixin from "src/services/apimixin";
import * as _ from "lodash";
import $httpService from "boot/httpService";
import {ClientApi} from "src/services/api/ClientApi";

export default {
  name: 'VenteNewComponent',
  components: {
    // 'downloadExcel': vue3JsonExcel,
    // 'facture': FactureComponent
  },
  mixins: [basemixin, apimixin],
  emits: ['reload'],
  data () {
    return {
      tva: 18,
      facture_status2: false,
      status_download: true,
      validate_status: true,
      fullWidth: false,
      credit: false,
      creditNumber: 0,
      avance: 0,
      agent: null,
      facture_number: null,
      versements: [],
      p_projets: [],
      dateposted: '',
      bc: '',
      bl: '',
      delai: '',
      condition: '',
      clientId: null,
      client: null,
      projetid: null,
      myclient: {},
      image: '',
      clients: [],
      products: [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }],
      appro_list: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      appro_list2: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
    }
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity + (this.tva * item.p.sales_price * item.quantity /100)), 0);
    }
  },
  created () {
    let date = new Date();
    this.clients_get();
    this.products_get();
    this.p_projet_get();
    this.factures_number_get();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    this.dateposted = date.toISOString().slice(0, 16);
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
    changeNumber(__nb) {
      this.versements = [];
      for (let i = 0; i < __nb; i++) {
        this.versements.push({})
      }
    },
    assign (index) {
      this.products[index].p = this.products[index].product_id
      this.products[index].p.sales_price = this.products[index].product_id.price
      this.products[index].productid = this.products[index].product_id.id
      // this.products[index].product_id = this.products[index].product_id.id
      // if (this.products[index].p.reste <= 0) {
      //   this.products.splice(index, 1);
      //   this.$q.notify({ color: 'dark', position: 'top', message: 'Le produit est en rupture de stock' });
      // } else if (this.products[index].p.reste < this.products[index].quantity) {
      //   this.products[index].quantity = this.products[index].p.reste;
      //   this.$q.notify({ color: 'dark', position: 'top', message: 'il reste ' + this.products[index].p.reste + ' en stock, Veuillez ne pas depasser la quantité en stock' });
      // } else if (this.products[index].quantity <= 0) {
      //   this.products[index].quantity = 1;
      //   this.$q.notify({ color: 'dark', position: 'top', message: 'Veuillez rentrer un nombre positif' });
      // }
      // this.products[index].p.tva = 0;
    },
    assign_client (client) {
      this.clientId = this.client;
      this.myclient = this.clients.filter(x => x.id === client )[0]
      this.tva = 18;
      if(this.myclient.exonere === 1) {
        this.tva = 0;
      }
      this.products_get();
    },
    p_projet_get () {
      $httpService.getApi('/my/get/p_projet')
        .then((response) => {
          this.p_projets = response
        })
    },
    async clients_get () {
      this.clients = await ClientApi.get()
    },
    sales_post() {
      let params = { agent: this.agent,
        products: this.products,
        id_vente: this.facture_number,
        clientid: this.myclient.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total,
        tva: this.tva,
        bl: this.bl,
        bc: this.bc,
        condition: this.condition,
        delai: this.delai,
        projetid: this.projetid,
        dateposted: this.dateposted,
        versements: this.versements
      };
      if (confirm('Voulez vous ajouter')) {
        this.postApi('/my/post/sales', params)
          .then((response) => {
            var status = response['statut'];
            if (status == !0) {
              this.postApi('/my/post/qr_code', {
                id: response['id'], typerubrique: 3, file: this.image
              });
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.validate_status = false;
              this.$emit('reload', true);
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    // sales_put() {
    //   if (confirm('Voulez vous modifier')) {
    //     this.putApi('/my/put/sales', this.product).then((response) => {
    //       this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
    //       // this.sales_get();
    //     })
    //   }
    // },
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
    factures_number_get () {
      this.getApi('/my/get/facture_number')
        .then((response) => {
          this.facture_number = response['nb'];
        })
    },
    specialities_add () {
      // this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
      this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
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
      this.products.splice(i, 1);
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.appro_list = this.appro_list2.filter(
          (v) => {
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1;
          }
        );
      })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.factures_get_id(_id);
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
