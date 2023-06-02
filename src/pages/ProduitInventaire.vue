<template>
  <q-page>

    <div class="row justify-center text-center">

      <div class="col-md-11 col-sm-12 col-xs-12 q-pa-lg text-center">
        <q-card class="my-card text-center justify-center content-center">
          <q-item clickable>
            <q-card-section>
              <h5 class="text-center">Rubrique: Produits - Inventaires</h5>
              <span><q-input v-model="inventaire" placeholder="N°Inventaire" :dense="true" /></span>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>

    <q-dialog v-model="alert">
      <q-card v-if="lists.length>0" style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Inventaire - {{lists[0]['name']}}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-markup-table>
            <thead>
            <tr>
              <!--                            <th class="text-left">ID</th>-->
              <th class="text-right">Nom</th>
              <th class="text-right">Precedent</th>
              <th class="text-right">Actualisé</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in lists" :key="index">
              <!--                            <td class="text-left">{{item.id}}</td>-->
              <td class="text-right">{{item.productname}}</td>
              <td class="text-right">{{item.old}}</td>
              <td class="text-right">{{item.new}}</td>
            </tr>
            <tr v-for="(item, ind) in lists_products" :key="item.product_name+''+ind">
              <!--                            <td class="text-left">{{ind}}</td>-->
              <td class="text-right">{{item.product_name}}</td>
              <td class="text-right">{{item.def}}</td>
              <td class="text-right">{{item.reste}}</td>
            </tr>
            </tbody>
          </q-markup-table>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn v-close-popup flat label="OK" color="primary" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <div class="row justify-center q-pa-md">
      <div class="col-md-11 col-sm-12 col-xs-12 q-mt-md">

        <q-tabs
v-model="tab" dense class="text-dark"
                active-color="secondary" indicator-color="secondary" align="justify" narrow-indicator>
          <q-tab name="mails" label="Listes des inventaires" />
          <q-tab name="alarms" label="Nouvel Inventaire" />
        </q-tabs>

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="mails">

            <div class="row">
              <div class="col-12 q-pa-lg">
                <q-input
v-model="search" class="row" autocomplete type="search"
                         label="Rechercher" @keyup="facture_filter_get(search)" />
              </div>
              <div
v-for="(item, index) in inventaires"
                   :key="index" class="col-lg-3 col-md-6 col-sm-6 col-12 q-pa-md">
                <q-card :class="'q-pa-md '">
                  <div class="text-h6"> {{item.name}} </div>
                  <br> Crée le {{dateformat(item.dateposted)}}
                  <br>
                  <q-card-actions>
                    <q-btn flat icon="edit" @click="edit(item)"></q-btn>
                    <q-btn flat icon="delete" @click="true"></q-btn>
                  </q-card-actions>
                </q-card>
              </div>
            </div>

          </q-tab-panel>

          <q-tab-panel name="alarms">
            <br>
            <q-table title="Produits" :rows="products" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
              <template #top="props">
                <div class="col-7 q-table__title">Liste des produits
                  <div class="row">
                    <q-input v-model="name" dense debounce="300" placeholder="Nom de l'inventaire" /> &nbsp;&nbsp;
                    <q-btn class="q-mr-xs" size="sm" label="créer" color="secondary" @click="check_qty()" />
                  </div>
                  <br>
                </div>
                <div class="row">
                  <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
                  <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                         class="q-ml-md" @click="props.toggleFullscreen" />
                </div>
              </template>
              <template #body="props">
                <q-tr :key="props.row.id" :props="props" v-bind="props.row.qty = 0">
                  <q-td key="id" :props="props"> {{props.row.id}} </q-td>
                  <q-td key="photo" :props="props">
                    <img v-if="props.row.photos" :src="uploadurl+'/'+entreprise.id+'/product/'+JSON.parse(props.row.photos)[0]['name']" style="width: 50px; height: 50px; object-fit: cover"/>
                  </q-td>
                  <q-td key="name" :props="props"> {{props.row.name}} </q-td>
                  <q-td key="domainname" :props="props"> {{props.row.domainname}} </q-td>
                  <q-td key="parent_categorie_name" :props="props"> {{props.row.parent_categorie_name}} </q-td>
                  <q-td key="amount" :props="props"> {{numerique(props.row.reste)}} </q-td>
                  <q-td key="def" :props="props"> <q-input v-model="props.row.def" type="number" label="Quantité" :value="0" filled :dense="true" /> </q-td>
                  <q-td key="difference" :props="props" :class="alerte(props.row)">
                    {{parseInt(props.row.def) - parseInt(props.row.reste)}}
                  </q-td>
                  <q-td key="actions" :props="props">
                    <!--                <q-btn class="q-mr-xs" size="sm" color="secondary" v-on:click="update_get(props.row)" icon="verified" />-->
                  </q-td>
                </q-tr>
              </template>
            </q-table>
            <br>
          </q-tab-panel>

        </q-tab-panels>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin';
// import AreachartComponent from '../components/areachart';
export default {
  name: 'ProduitInventairePage',
  mixins: [basemixin],
  data () {
    return {
      tab: 'mails',
      product_id: 1,
      alert: false,
      loading1: false,
      red: '#6d1412',
      first: null,
      inventaire: null,
      inventaires: [],
      lists: [],
      lists_products: [],
      name: null,
      last: null,
      entreprise: {},
      search: '',
      maximizedToggle: true,
      products: [],
      product: { description: '', stock: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'photo', align: 'left', label: 'photo' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'domainname', align: 'left', label: 'Domaine', field: 'domainname', sortable: true },
        { name: 'parent_categorie_name', align: 'left', label: 'Categorie', field: 'parent_categorie_name', sortable: true },
        { name: 'amount', align: 'left', label: 'Stock Calculé', field: 'amount', sortable: true },
        { name: 'def', label: 'Stock Réel', field: 'def' },
        { name: 'difference', label: 'Diff' },
        { name: 'actions', label: 'Actions' }
      ],
      data: [],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  mounted () {
    this.products_get();
    this.inventaires_get();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
  },
  methods: {
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    check_qty() {
      if (confirm("Voulez vous appliquer l'inventaire ?")) {
        $httpService.postWithParams('/my/inventaire/products', { products: this.products, name: this.name })
          .then((response) => {
            this.$q.notify({
              color: 'positive', position: 'top', message: response['msg'], icon: 'report_problem'
            });
            this.products_get();
          });
      }
    },
    alerte(item) {
      if (item.reste > item.def) {
        return 'bg-red-1';
      } else if (item.reste === item.def) {
        return 'bg-green-1';
      } else if (item.reste < item.def) {
        return 'bg-green-5';
      }
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
          for (let i = 0; i < this.products.length; i++) {
            this.products[i].def = this.products[i].reste;
          }
        })
    },
    inventaires_get () {
      $httpService.getWithParams('/my/get/inventaire')
        .then((response) => {
          this.inventaires = response;
        })
    },
    inventaires_by_id (_inventory) {
      $httpService.getWithParams('/my/get/inventaire/' + _inventory)
        .then((response) => {
          this.lists = response;
          for (let i = 0; i < this.lists.length; i++) {
            if (this.lists[i].data) {
              this.lists_products = JSON.parse(this.lists[i].data);
              console.log(this.lists_products);
            }
          }
        })
    },
    edit (item) {
      console.log(item);
      this.alert = true;
      this.inventaires_by_id(item.inventory_id);
    }
  }
}
</script>

<style>
</style>
