<template>

  <q-card style="width: 1300px; max-width: 100%;" id="facture" :flat="true">
    <q-form  @submit="onSubmit" class="q-gutter-md">

      <q-card-section >
        <div class="row" >
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 alignleft hidden-xs mobile-hide">
            <!--                      <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 100px; height: 100px; object-fit: cover"/>-->
            <!--                      <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 100px; height: 100px; object-fit: cover"/>-->
            <img src="~assets/fmmi-logo.jpeg" style="width: 100px; height: 100px; object-fit: cover"/>
            <div>{{entreprise.name}}</div>
            <div>{{entreprise.telephone}}</div>
            <div>{{entreprise.email}}</div>
            <div>BL: <input v-model="bl" style="border: 0; border-bottom: 1px dashed grey" /> </div>
            <div>BC: <input v-model="bc" style="border: 0; border-bottom: 1px dashed grey" /> </div>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 content-center text-center print-only">
            <!--                                            <q-input stack-label v-model="dateposted" type="date" label="Date"></q-input>-->
            <!--                      <vue-qr class="hidden" :size="100" :text="JSON.stringify(products)" :callback="qr_get" qid="testid" />-->
            <!--                      <vue-qr :size="100" :text="JSON.stringify(products)" />-->
          </div>
          <!--                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right float-right" style="min-width: 200px">-->
          <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 text-right float-right" style="min-width: 300px">
            <div class="float-right q-mb-sm print-hide" style="width: 50%; position:relative;">
              <q-input stack-label v-model="dateposted" type="datetime-local" label="Date" :dense="true"></q-input>
              <q-select class="print-hide col-md-6 col-sm-12" filled map-options emit-value v-if="status_download" :dense="true"
                        v-model="client" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                        input-debounce="0" :option-label="'fullname'"
                        @update:model-value="assign_client(client)"
                        :rules="[val => !!val || 'Ce champs est requis']" />
            </div>
            <div class="row float-right q-mt-sm hidden-sm hidden-xs mobile-hide">
              <!--                        <div class="col-12">Facture #: {{facture_number}}</div>-->
              <div class="col-12">Facture #: <input v-model="facture_number" style="border: 0; border-bottom: 1px dashed grey" /></div>
              <div class="col-12">Creation: {{date}}</div>
              <div class="col-12">Entreprise Corporation</div>
              <div class="col-12" v-if="myclient.id">{{myclient.fullname}}</div>
              <div class="col-12" v-if="myclient.id">{{'+ '+myclient.telephone_code}} {{myclient.telephone}}</div>
              <div class="col-12" v-if="myclient.id">{{myclient.email}}</div>
            </div>

          </div>
        </div>
      </q-card-section>

      <q-card-section class="no-margin">
        <div class="row no-margin no-padding mobile-hide hidden-sm hidden-xs" style="height: 47px">
          <div class="col-4 q-pa-sm">Nom</div>
          <div class="col-1 q-pa-sm">Qte</div>
          <div class="col-2 q-pa-sm">Tva</div>
          <div class="col-2 q-pa-sm">Prix Uni</div>
          <div class="col-2 q-pa-sm">Total</div>
        </div>
        <div class="row q-mb-lg" v-for="(product, index) in products" :key="index">
          <q-select class="col-md-4 col-12 q-pa-sm truncate text-wrap" v-model="product.p" :options="appro_list" option-value="id" use-input @filter="filterFn"
                    option-label="name" @focusout="assign(index)" @input="assign(index)" :dense="true" />
          <q-input class="col-md-1 col-2 q-pa-sm" :dense="true" type="number" v-model="product.quantity" minlength="1"
                   @input="assign(index)" @focusout="getVal(index, product.quantity)" aria-valuemin="0" hint="qty" />
          <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.p.tva" hint="tva" />
          <q-input class="col-md-2 col-3 q-pa-sm" :dense="true" type="number" v-model="product.p.sales_price" hint="prix" />
          <q-input class="col-md-2 col-4 q-pa-sm" :dense="true" type="number"
                   :model-value="(product.p.sales_price * product.quantity) + (product.p.sales_price * product.quantity * product.p.tva)/100" />
          <div class="col-1"><br>
            <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-if="status_download" v-on:click="delete_product(index)" />
            &nbsp;
            <q-btn round color="dark" size="xs" icon="description" class="print-hide"  v-on:click="product.showdesc = !product.showdesc" />
          </div>
          <div class="col-12 q-pa-sm bg-grey-2" v-if="product.showdesc">
            <q-input v-model="product.details" filled autogrow />
          </div>
        </div>
        <div class="row no-padding q-mt-xs q-mb-lg">
          <div class="col-3 q-pa-lg" v-if="status_download">
            <q-checkbox v-model="credit" label="Credit" color="red" />
          </div>
          <div class="offset-lg-6 col-md-3 col-12 q-pa-sm">
            <q-input v-if="credit" :dense="true" type="number" v-model="avance" label="Avance"/><br>
            <h6 class="no-margin no-padding q-mb-lg">{{ numerique(Math.round(total)) }} FCFA</h6>
          </div>
        </div>

        <div v-if="medium2 && status_download" class="row print-hide q-pa-lg">
          <div class="col-12">
            Liste des versement
            &nbsp;<q-btn size="xs" v-on:click="versements.pop()">-</q-btn>
            &nbsp;<q-btn size="xs" v-on:click="versements.push({montant: 0})">+</q-btn>
          </div>
          <div class="row" v-for="fac in versements" v-bind:key="fac.id">
            <q-input class="col-3 q-ma-sm" :dense="true" label="Date" type="date" stack-label v-model="fac.date"  />
            <q-input class="col-3 q-ma-sm" :dense="true" label="Montant" stack-label type="number" v-model="fac.montant" />
            <q-btn class="no-padding" size="xs" v-if="fac.id" v-on:click="credit_update(fac)">✎</q-btn>
            <q-btn size="xs" v-if="!fac.id" v-on:click="credit_add(fac)">✅</q-btn>
          </div>
        </div>
        <!--                <div class="row q-pa-lg" v-if="validate_status">-->
        <div class="row q-pa-lg q-mt-4" v-if="status_download" style="margin-top: 30px">
          <q-btn class="print-hide" round color="positive" size="sm" icon="add" v-on:click="specialities_add" />&nbsp;&nbsp;
          <q-btn class="print-hide" color="secondary" label="Valider" size="sm" icon="save" type="submit"  />&nbsp;&nbsp;
          <q-btn icon="receipt" color="grey-10" outline label="Facture" v-on:click="get_facture_id(facture_number); facture_status2 = true" />
        </div>
      </q-card-section>

    </q-form>
  </q-card>

</template>

<script>
import vue3JsonExcel from "vue3-json-excel";
import FactureComponent from "components/facture_component.vue";
import basemixin from "pages/basemixin";
import apimixin from "src/services/apimixin";
import * as _ from "lodash";

export default {
  name: 'VenteNewComponent',
  emits: ['reload'],
  data () {
    return {
      tab: 'mails',
      filter: '',
      fitst: 1,
      last: 30,
      name: null,
      grid: false,
      facture_status2: false,
      status_download: true,
      validate_status: true,
      fullWidth: false,
      medium: false,
      medium2: false,
      credit: false,
      solde: true,
      avance: 0,
      agent: null,
      fournisseur: null,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      buy: null,
      categories: [],
      versements: [],
      date: '',
      dateposted: '',
      bc: '',
      bl: '',
      first: '',
      clientId: 1,
      client: 1,
      client2: {},
      myclient: {},
      image: '',
      users: [],
      clients: [],
      products: [{ p: { id: 1, prodcat: 'Select. un produit', name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 }],
      sales_list: [],
      products_list: [],
      appro_list: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      appro_list2: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      product: { description: '' },
      data: [],
      entreprise: {}
    }
  },
  components: {
    'downloadExcel': vue3JsonExcel,
    'facture': FactureComponent
  },
  mixins: [basemixin, apimixin],
  created () {
    let date = new Date();
    this.date = this.dateformat(date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate(), 4);
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.shop_get();
    this.clients_get();
    this.products_get();
    // this.sales_get();
    this.factures_number_get();
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    this.dateposted = date.toISOString().slice(0, 16);
  },
  computed: {
    total() {
      return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity /100)), 0);
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
    shop_get() {
      this.getApi('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    assign (index) {
      if (this.products[index].p.reste <= 0) {
        this.products.splice(index, 1);
        this.$q.notify({ color: 'dark', position: 'top', message: 'Le produit est en rupture de stock' });
      } else if (this.products[index].p.reste < this.products[index].quantity) {
        this.products[index].quantity = this.products[index].p.reste;
        this.$q.notify({ color: 'dark', position: 'top', message: 'il reste ' + this.products[index].p.reste + ' en stock, Veuillez ne pas depasser la quantité en stock' });
      } else if (this.products[index].quantity <= 0) {
        this.products[index].quantity = 1;
        this.$q.notify({ color: 'dark', position: 'top', message: 'Veuillez rentrer un nombre positif' });
      }
      this.products[index].p.tva = 0;
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
    qr_get(dataUrl) {
      this.image = dataUrl;
    },
    clients_get () {
      this.getApi('/my/get/client')
        .then((response) => {
          this.clients = response;
          this.client = this.clients[0];
          this.myclient = this.clients[0];
        })
        .catch(() => {
          this.$q.notify({ color: 'negative', position: 'top', message: 'Connection impossible' });
        });
    },
    update_show(item) {
      this.medium2 = true;
      this.product = item;
    },
    sales_post() {
      this.$emit('reload', true);
      return false
      let params = { agent: this.agent,
        products: this.products,
        id_vente: this.facture_number,
        clientid: this.myclient.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total,
        bl: this.bl,
        bc: this.bc,
        dateposted: this.dateposted
      };
      if (confirm('Voulez vous ajouter')) {
        this.postApi('/my/post/sales', params)
          .then((response) => {
            var status = response['statut'];
            if (status == !0) {
              this.postWithParams('/my/post/qr_code', {
                id: response['id'], typerubrique: 3, file: this.image
              });
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.validate_status = false;
              // this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    sales_put() {
      if (confirm('Voulez vous modifier')) {
        this.putApi('/my/put/sales', this.product).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          // this.sales_get();
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
          })
      })
    },
    factures_number_get () {
      this.getApi('/my/get/facture_number')
        .then((response) => {
          this.facture_number = response['nb'];
        })
    },
    sales_get () {
      this.getApi('/my/get/sales')
        .then((response) => {
          this.sales_list = response;
        })
    },
    specialities_add () {
      this.products.push({ p: { id: 0, name: 'Selectionner un produit', tva: 0, sell_price: 0 }, quantity: 1 });
    },
    specialities_delete () {
      this.products.pop();
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
      this.products = this.products.filter((x) => {
        return x.p.id !== this.products[i].p.id;
      });
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
    factures_get_credit (factureid) {
      this.getApi('/my/get/sales_by_credit?id_vente=' + factureid, { })
        .then((response) => {
          this.versements = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      // this.medium2 = true;
      this.factures_get_id(_id);
    },
    factures_get_id (factureid) {
      this.getApi('/my/get/sales_by_idvente?id_vente=' + factureid, { })
        .then((response) => {
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            response[i].p.id = response[i].p_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].prix_unitaire;
            response[i].price = response[i].prix_unitaire;
            response[i].p.quantity = response[i].quantite_vendu;
            response[i].quantity = response[i].quantite_vendu;
            response[i].p.name = response[i].p_name;
            response[i].name = response[i].p_name;
            // response[i].total = response[i].montant_vendu;
          }
          this.factures_details = response;
          this.products = response;
          this.facture_number = this.products[0].id_vente;
          this.client = response[0]['client'] == null ? {} : JSON.parse(response[0]['client']);
          this.status_download = true;
        })
    }
  }
  // setup () {
  //   return {}
  // }
}
</script>
