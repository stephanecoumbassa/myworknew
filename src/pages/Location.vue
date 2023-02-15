<template>

  <q-page >
    <br>
    <div class="row justify-center q-ma-md">
      <div class="col-lg-11 col-12">

        <q-btn color="dark" label="Listes des locations" v-on:click="listes_status = true; add_status = false; update_status = false" /> &nbsp;&nbsp;
        <q-btn color="dark" label="Ajouter" v-on:click="listes_status = false; add_status = true; update_status = false; products_list = [{price: 0, tva: 0, quantity: 1}]" />

        <!--    AJOUTER    -->
        <q-card style="width: 1200px; max-width: 100%;" id="facture" :flat="true" v-if="add_status">
          <br>
          <q-form  @submit="onSubmit" class="q-gutter-md">

            <div class="row q-ma-md">
              <div class="col-6 q-pa-md">
                <q-select class="print-hide col-md-6 col-sm-12" filled map-options emit-value v-if="status_download"
                          v-model="client" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                          input-debounce="0" :option-label="'fullname'" @input="assign_client(client)" :rules="[val => !!val || 'Ce champs est requis']" />
                <q-input filled type="number" label="Caution en Francs CFA" v-model="caution" />
              </div>
              <div class="col-6 q-pa-md">
                <q-input filled stack-label type="date" label="Date de début" v-model="date_start" /><br>
                <q-input filled stack-label type="date" label="Date de fin" v-model="date_end" />
              </div>
            </div>

            <q-card-section>
              <div class="row no-margin no-padding mobile-hide" style="height: 47px">
                <div class="col-3 q-pa-sm">Nom</div>
                <div class="col-1 q-pa-sm">Qte</div>
                <div class="col-2 q-pa-sm">Tva</div>
                <div class="col-3 q-pa-sm">Prix Uni</div>
                <div class="col-3 q-pa-sm">Total</div>
              </div>
              <div class="row q-mb-lg" v-for="(product, index) in products_list" :key="index">
                <q-select class="col-sm-12 col-xs-12 col-3 q-pa-sm text-wrap" v-model="product.product_id" :options="products" option-value="id"
                          hint="nom" emit-value map-options option-label="name" :dense="true" />
                <q-input hint="qte" class="col-sm-2 col-xs-2 col-1 q-pa-sm" :dense="true" type="number" v-model="product.quantity" />
                <q-input hint="tva" class="col-sm-2 col-xs-2 col-2 q-pa-sm" :dense="true" type="number" v-model="product.tva" />
                <q-input hint="pu" class="col-sm-3 col-xs-3 col-2 q-pa-sm" :dense="true" type="number" v-model="product.price" />
                <q-input hint="total" class="col-sm-4 col-xs-4 col-3 q-pa-sm" :dense="true" type="number" :value="product.price * product.quantity" />
                <div class="col-1"><br>
                  <q-btn round color="negative" size="xs" icon="remove" class="print-hide" v-if="status_download" v-on:click="delete_product(index)" />
                </div>
              </div>
              <div class="row no-padding q-mt-xs q-mb-lg" style="height: 70px">
                <div class="col-3 q-pa-lg" v-if="status_download">
                  <q-checkbox v-model="credit" label="Credit" color="red" />
                </div>
                <div class="offset-6 col-3 q-pa-sm">
                  <q-input v-if="credit" :dense="true" type="number" v-model="avance" label="Avance"/><br>
                  <h6 class="no-margin no-padding q-mb-lg">{{ numerique(Math.round(total)) }} FCFA</h6>
                </div>
              </div>

              <div v-if="medium2 && status_download" class="print-hide q-pa-lg">
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
              <div class="row q-pa-lg" v-if="status_download">
                <q-btn class="print-hide" round color="positive" size="sm" icon="add" v-on:click="specialities_add" />&nbsp;&nbsp;
                <q-btn class="print-hide" label="Valider" size="sm" icon="save" type="submit" color="secondary" v-if="!products_list[0].id" />&nbsp;&nbsp;
                <q-btn class="print-hide" label="Modifier" size="sm" icon="save" type="button" color="secondary"
                       v-if="products_list[0].id" v-on:click="location_update()" />&nbsp;&nbsp;
                <q-btn icon="receipt" color="grey-10" outline label="Facture" v-on:click="get_facture_id(facture_number); facture_status2 = true" />

              </div>
            </q-card-section>

          </q-form>
        </q-card>

        <!--    MODIFIER    -->
        <q-card style="width: 1200px; max-width: 100%;" :flat="true" v-if="update_status">
          <br>
          <q-form  @submit="onSubmit" class="q-gutter-md">

            <div class="row q-ma-md">
              <div class="col-11 q-pa-md">
                <div class="text-h6">Modifier la facture</div>
              </div>
              <div class="col-6 q-pa-md">
                <q-select class="print-hide col-md-6 col-sm-12" filled map-options emit-value v-if="status_download"
                          v-model="client" :options="clients" label="Clients" :option-value="JSON.stringify(client)"
                          input-debounce="0" :option-label="'fullname'" @input="assign_client(client)" :rules="[val => !!val || 'Ce champs est requis']" />
                <q-input filled type="number" label="Caution en Francs CFA" v-model="caution" />
                <br>
                <!--                                <q-input filled type="textarea" label="Description" v-model="caution" />-->
              </div>
              <div class="col-6 q-pa-md">
                <q-input filled stack-label type="date" label="Date de début" v-model="date_start" /><br>
                <q-input filled stack-label type="date" label="Date de fin" v-model="date_end" />
              </div>
            </div>

            <q-card-section>
              <div class="row no-margin no-padding" style="height: 47px">
                <div class="col-3 q-pa-sm">Nom</div>
                <div class="col-1 q-pa-sm">Qte</div>
                <!--                <div class="col-2 q-pa-sm">Tva</div>-->
                <div class="col-2 q-pa-sm">Prix Uni</div>
                <div class="col-3 q-pa-sm">Total</div>
                <div class="col-1 q-pa-sm">Qte Retour</div>
                <div class="col-1 q-pa-sm">Actions</div>
              </div>
              <div class="row q-mb-lg" v-for="(product, index) in products_list" :key="index" style="height: 47px">
                <q-select class="col-3 q-pa-sm text-wrap" v-model="product.product_id" :options="products" option-value="id"
                          emit-value map-options option-label="name" :dense="true" />
                <q-input class="col-1 q-pa-sm" :dense="true" type="number" v-model="product.quantity" />
                <!--                <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.tva" />-->
                <q-input class="col-2 q-pa-sm" :dense="true" type="number" v-model="product.price" />
                <q-input class="col-3 q-pa-sm" :dense="true" type="number" :value="product.price * product.quantity" />
                <q-input class="col-1 q-pa-sm" :dense="true" type="number" v-model="product.quantity_back" />
                <div class="col-1"><br>
                  <q-btn round class="print-hide" size="xs" icon="edit" type="button" color="secondary" v-on:click="location_update(product)" /> &nbsp;
                  <q-btn round class="print-hide" color="negative" size="xs" icon="remove" v-if="status_download" v-on:click="delete_product(index)" />
                </div>
              </div>

              <div class="row q-pa-lg" v-if="status_download">
                <!--                <q-btn class="print-hide" round color="positive" size="xs" icon="add" v-on:click="specialities_add" />&nbsp;&nbsp;-->
                <q-btn icon="receipt" color="grey-10" outline label="Facture" v-on:click="get_facture_id(facture_number); facture_status2 = true" />
              </div>
            </q-card-section>

          </q-form>
        </q-card>

        <div class="row q-pa-sm print-hide" v-if="listes_status">
          <div class="col q-pa-sm"><q-input v-model="first" type="date" hint="date ddebut" /></div>
          <div class="col q-pa-sm"><q-input v-model="last" type="date" hint="date fin" /></div>
          <div class="col q-pa-sm"><br><q-btn color="dark" label="filtrer" v-on:click="sales_stats_get()" /></div>
        </div>

        <q-table title="Listes de locations" :grid="grid" :rows="sales_list" :columns="columns"
                 :pagination="pagination" :filter="filter" v-if="listes_status">
          <template v-slot:top="props">
            <div class="col-4 q-table__title">Liste des locations</div>&nbsp;&nbsp;&nbsp;
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <download-excel name="vente.xls" :data="sales_list">
              <q-btn flat round dense icon="far fa-file-excel" class="q-ml-md" />
            </download-excel>
            <q-btn flat round dense icon="grid_on" @click="grid = !grid" class="q-ml-md" />
            <q-btn flat r ound dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'" @click="props.toggleFullscreen" class="q-ml-md" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">{{ props.row.id }}</q-td>
              <q-td key="p_name" :props="props">{{ props.row.name }}</q-td>
              <q-td key="date_start" :props="props"> {{ dateformat(props.row.date_start, 3) }}</q-td>
              <q-td key="date_end" :props="props"> {{ dateformat(props.row.date_end, 3) }}</q-td>
              <q-td key="quantite_vendu" :props="props"> {{ numerique(parseInt(props.row.quantity)) }}</q-td>
              <q-td key="prix_unitaire" :props="props"> {{ numerique(props.row.price) }}</q-td>
              <q-td key="a_name" :props="props"> {{ props.row.a_name }} {{ props.row.a_last_name }}</q-td>
              <q-td key="id_vente" :props="props"> {{ props.row.id_location }} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-ma-xs" size="xs" color="dark" icon="edit" v-on:click="get_facture_update(props.row.id_location); facture_number = props.row.id_location;" />
                <q-btn class="q-ma-xs" size="xs" color="dark" icon="receipt" v-on:click=" get_facture_id(props.row.id_location); facture_number = props.row.id_location; facture_status2 = true" />
                <q-btn class="q-ma-xs" size="xs" color="dark" label="Retour" icon="reply" v-on:click=" get_facture_id(props.row.id_location); facture_number = props.row.id_location; facture_status2 = true" />
              </q-td>
            </q-tr>
          </template>
          <template v-slot:item="props">
            <div class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition">
              <q-card :class="props.selected ? 'bg-grey-2' : ''">
                <q-list dense v-for="col in props.cols.filter(col => col.name !== 'desc')" :key="col.name">
                  <q-item v-if="col.status !== false">
                    <q-item-section>
                      <q-item-label caption>{{ col.label }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                      <q-item-label >{{ col.value }}</q-item-label>
                      <q-item-label v-if="col.label == 'Actions'">
                        <q-btn class="q-ma-xs" size="xs" color="secondary" v-on:click="get_facture_id(props.id_location); factures_get_credit(props.id_location)" icon="visibility" />
                        <q-btn class="q-ma-xs" size="xs" color="red" icon="delete" />
                      </q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-card>
            </div>
          </template>
        </q-table>


        <q-dialog v-model="facture_status2" position="top" style="max-width: 1000px;">
          <q-card style="max-width: 100%;" :flat="true">
            <facture name="Facture de location"
                     :myentreprise="entreprise" :client="client" :facturenum="facture_number" :products="products_list" />
          </q-card>
        </q-dialog>
      </div>
    </div>
    <br>

  </q-page>

</template>

<script>


import $httpService from '../boot/httpService';
import vue3JsonExcel from 'vue3-json-excel';
import basemixin from './basemixin';
import * as _ from 'lodash';
import FactureComponent from '../components/facture_component.vue';
export default {
  name: 'locationPage',
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
      listes_status: true,
      add_status: false,
      update_status: false,
      fullWidth: false,
      medium: false,
      medium2: false,
      credit: false,
      solde: true,
      avance: 0,
      product_id: null,
      facture_number: null,
      quantity_id: null,
      sell: null,
      buy: null,
      caution: 0,
      description: 0,
      date_start: null,
      date_end: null,
      categories: [],
      versements: [],
      date: '',
      client: 1,
      client2: {},
      myclient: {},
      image: '',
      users: [],
      clients: [],
      products: [{ price: 0, tva: 0, quantity: 1, product_id: 0 }],
      products2: [{ price: 0, tva: 0, quantity: 1 }],
      products_list: [{ id: 0, product_id: 0, name: 'Selectionner un produit', tva: 0, price: 0, quantity: 1 }],
      products_list2: [],
      sales_list: [],
      appro_list: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      appro_list2: [{ p: { sell_price: 0, prodcat: '' }, prodcat: '' }],
      product: { description: '' },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true, status: false },
        { name: 'p_name', align: 'left', label: 'Nom', field: 'p_name', sortable: true, status: true },
        { name: 'date_start', align: 'left', label: 'Date Debut', field: 'date_start', sortable: true },
        { name: 'date_end', align: 'left', label: 'Date Fin', field: 'date_end', sortable: true },
        { name: 'quantite_vendu', align: 'left', label: 'Qte', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', align: 'left', label: 'Prix', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        // { name: 'tva', align: 'left', label: 'TVA', field: 'tva', sortable: true },
        // { name: 'remise_totale', align: 'left', label: 'Remise.', field: 'remise_totale', sortable: true, format: val => `${this.numerique(val)}` },
        // { name: 'a_name', align: 'left', label: 'Agent', field: 'a_name', sortable: true },
        { name: 'id_vente', align: 'left', label: 'Facture ID', field: 'id_vente', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
      entreprise: {},
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 50
      }
    }
  },
  components: {
    'downloadExcel': vue3JsonExcel,
    'facture': FactureComponent
  },
  mixins: [basemixin],
  created () {
    let date = new Date();
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
      return this.products_list.reduce((product, item) => product + (item.price * item.quantity + (item.tva * item.price * item.quantity)), 0);
    }
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.location_post();
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
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    qr_get(dataUrl) {
      this.image = dataUrl;
    },
    clients_get () {
      $httpService.getWithParams('/my/get/client')
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
    location_post() {
      let params = { agent: this.agent,
        products: this.products_list,
        date_start: this.date_start,
        date_end: this.date_end,
        caution: this.caution,
        id_vente: this.facture_number,
        clientid: this.myclient.id,
        credit: this.credit,
        avance: this.avance,
        total: this.total
      };
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/post/location', params)
          .then((response) => {
            var status = response['status'];
            if (status == !0) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.validate_status = false;
              this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    location_update(product) {
      if (confirm('Voulez vous ajouter')) {
        $httpService.postWithParams('/my/put/location', product)
          .then((response) => {
            var status = response['status'];
            if (status == !0) {
              this.$q.notify({
                color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
              });
              this.facture_number = response['factureid'];
              this.sales_get();
            } else {
              this.$q.notify({
                color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
              });
            }
          })
      }
    },
    location_put() {
      if (confirm('Voulez vous modifier')) {
        $httpService.putWithParams('/my/put/location', this.product).then((response) => {
          this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top-right' });
          this.sales_get();
        })
      }
    },
    products_get () {
      $httpService.getWithParams('/my/get/products_location')
        .then((response) => {
          this.products = response;
          this.products2 = response;
        })
    },
    sales_get () {
      $httpService.getWithParams('/my/get/location')
        .then((response) => {
          this.sales_list = response;
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
      this.products_list.push({ id: 0, name: 'Selectionner un produit', tva: 0, price: 0, quantity: 1 });
    },
    specialities_delete () {
      this.products_list.pop();
    },
    // select_index(productid, index) {
    //     // this.products_list[index] = { product_id: productid, tva: 0, price: 0, quantity: 1 };
    // },
    delete_product(i) {
      this.products_list = this.sales_list.filter((x) => {
        return x.product_id !== this.products_list[i].product_id;
      });
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.products = this.products2.filter(
          (v) => {
            return v.prodcat.toLocaleLowerCase().indexOf(needle) > -1;
          }
        );
      })
    },
    factures_get_credit (factureid) {
      $httpService.getWithParams('/my/get/sales_by_credit?id_vente=' + factureid)
        .then((response) => {
          this.versements = response;
        })
    },
    get_facture_id (_id) {
      this.fullWidth = true;
      this.factures_get_id(_id);
    },
    get_facture_update(_id) {
      this.listes_status = false;
      this.update_status = true;
      this.factures_get_id(_id);
    },
    factures_get_id (factureid) {
      $httpService.getWithParams('/my/get/location_by_facture?id_location=' + factureid)
        .then((response) => {
          for (let i = 0; i < response.length; i++) {
            response[i].p = {};
            // response[i].p.id = response[i].product_id;
            response[i].p.tva = response[i].tva;
            response[i].p.sales_price = response[i].price;
            response[i].p.quantity = response[i].quantity;
            response[i].p.name = response[i].name;
            response[i].total = response[i].quantity * response[i].price;
          }
          this.date_start = response[0].date_start;
          this.date_end = response[0].date_end;
          this.caution = response[0].caution;
          this.products_list = response;
          this.facture_number = this.products[0].id_location;
          this.client = response[0]['client'] == null ? {} : JSON.parse(response[0]['client']);
        })
    }
  }
}
</script>

<style>
::-webkit-scrollbar {
  display: none; /* Chrome Safari */
}
</style>
