<template>
  <q-page padding>

    <div class="row text-center">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md text-center">
        <q-card class="text-center justify-center content-center" flat>
          <q-item>
            <q-card-section>
              <h5 class="text-center">Rubrique: Produits</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/produits/stock')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h6">Stock Initial</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/produits/pertes')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h6">Pertes</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <!--      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-sm">-->
      <!--        <router-link class="item item-link" to="/produits/marques">-->
      <!--          <q-card class="my-card" clickable>-->
      <!--            <q-item clickable>-->
      <!--              <q-card-section>-->
      <!--                <div class="text-h6">Marques</div>-->
      <!--              </q-card-section>-->
      <!--            </q-item>-->
      <!--          </q-card>-->
      <!--        </router-link>-->
      <!--      </div>-->

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/produits/inventaire')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h6">Inventaires</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-md">
        <q-card class="pointer" clickable flat @click="$router.push('/produits/resume')">
          <q-item clickable>
            <q-card-section>
              <div class="text-h6">Mouvements</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>


    <div class="row justify-center">
      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md">
<!--        <q-btn label="Liste des produits désactivés" class="q-mb-lg" size="sm" color="grey-8" />-->
<!--        <br><br>-->

        <q-table id="printMe" title="Produits" :rows="products" :columns="columns" :pagination="pagination" :filter="filter" row-key="name" flat>
          <template #top>
            <div class="col-4 q-table__title">Liste des produits</div>
            <div class="col-4">
              <q-input v-model="filter" dense debounce="300" placeholder="Rechercher" />
            </div>
            <div class="col-4 text-right">
              <q-btn @click="json2csv(products)" round icon="fa fa-file-excel" size="sm" title="excel" class="q-ma-sm" color="secondary"  />
              <q-btn v-print="'#printMe'" round icon="print" size="sm" class="q-ma-sm" color="secondary" />
              <q-btn @click="product = { description: '' }; medium2 = true" id="add" round class="q-ma-sm" size="sm" icon="add" color="secondary" />
            </div>
            <br>
          </template>
          <template #body="props">
            <!--            <q-tr :props="props" :class="alerte(props.row)" :key="props.row.id">-->
            <q-tr :key="props.row.id" :props="props">
              <q-td key="id" :props="props">
                {{props.row.id}}
                <q-icon v-if="props.row.customize" name="dashboard_customize" />
              </q-td>
              <!--              <q-td key="photo" :props="props">-->
              <!--                <img v-if="props.row.photo" :src="uploadurl+'/uploads/products/'+props.row.photo" style="width: 70px; height: 70px; object-fit: cover"/>-->
              <!--              </q-td>-->
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="domainname" :props="props"> {{props.row.domainname}} </q-td>
              <q-td key="parent_categorie_name" :props="props"> {{props.row.parent_categorie_name}} </q-td>
              <q-td key="categorie" :props="props"> {{props.row.categorie}} </q-td>
              <q-td key="marque" :props="props"> {{props.row.marque}} </q-td>
              <q-td key="amount" :props="props"> {{numerique(props.row.reste)}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn outline class="q-mr-xs" size="xs" color="grey-9" label="gest. prix" @click="prix_status = true; product_id = props.row.id; s_product_prix_get(props.row.id)" />
                <q-btn outline class="q-mr-xs" size="xs" color="grey-9" label="stat" @click="stat_status = true; product_id = props.row.id; appro_stats_global(props.row.id); " />
                <q-btn outline class="q-mr-xs" size="xs" color="grey-9" label="vente" @click="vente_status = true; product_id = props.row.id; sales_stats_get(props.row.id); " />
                <q-btn outline class="q-mr-xs" size="xs" color="grey-9" label="achat" @click="appro_status = true; product_id = props.row.id; appro_stats_get(props.row.id);" />
                <q-btn class="q-mr-xs" size="xs" color="grey-9" icon="edit" @click="update_get(props.row)" />
                <!--                <q-btn class="q-mr-xs" size="xs" color="blue-grey-7" v-on:click="photo_get(props.row)" icon="photo" />-->
                <q-btn size="xs" color="red-9" title="Désactivé" icon="toggle_off" @click="product_disable(props.row)" />
                <!--<q-btn v-if="role == 1" class="q-mr-xs" size="xs" color="red" icon="delete"></q-btn>-->
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>
    <br>

    <q-dialog v-model="prix_status">
      <q-card style="width: 900px; max-width: 90vw;">
        <q-card-section>
          <div class="text-h6">gestion de prix par client</div>
        </q-card-section>
        <q-card-section>
          <!--          {{clients}}-->
          <div v-for="price in prices_list" :key="price.id" class="row q-col-gutter-lg q-mt-xs">
            <div class="col-3">
              <q-select
                v-model="price.client_id" class="print-hide col-md-6 col-sm-12" filled map-options emit-value
                :dense="true" :options="clients" label="Clients" option-value="id" :option-label="'fullname'"
                input-debounce="300" />
            </div>
            <div class="col-3">
              <q-input v-model="price.prix_vente" dense label="prix" />
            </div>
            <div class="col-3">
              <q-btn label="Valider" outline @click="s_product_prix_post(price)"/>
            </div>
          </div>
          <div class="row q-mt-lg">
            <q-btn label="+" outline @click="prices_list.push({})" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>


    <q-dialog v-model="medium2">
      <q-card style="width: 900px; max-width: 90vw;">
        <q-card-section>
          <div v-if="!product.id" class="text-h6">Ajouter un produit</div>
          <div v-if="product.id" class="text-h6">Modifier un produit</div>
        </q-card-section>

        <q-card-section>
          <q-form  class="q-gutter-md" @submit="onSubmit">
            <div class="row">
              <div class="col-md-7 col-sm-12">
                <q-input
                  id="name" v-model="product.name" autocomplete label="Nom du produit *" :dense="true" outlined
                  lazy-rules :rules="[ val => val && val.length > 0 || 'champs obligattoire']" />

                <q-select
                  id="type" v-model="product.type" :options="['matiere', 'produit', 'outil']" label="type" map-options emit-value :dense="true"
                  stack-label input-debounce="0" outlined />
                <br>
                <q-select
                  id="domainid" v-model="product.domainid" :options="domains" label="Categorie" map-options emit-value :dense="true"
                  option-value="id" stack-label input-debounce="0" option-label="name" outlined
                  :rules="[ val => val || 'champs obligattoire']" @input="parent_get(product.domainid)" />

                <q-select
                  id="parent_categorie_id" v-model="product.parent_categorie_id" :options="parents" label="Sous-Categorie" map-options emit-value :dense="true"
                  option-value="id" stack-label input-debounce="0" option-label="name" outlined
                  :rules="[ val => val || 'champs obligattoire']" @input="categorie_get(product.parent_categorie_id)" />

                <q-input v-model="product.price" autocomplete type="number" label="Prix de vente par défaut*" outlined :dense="true" hint="" />

                <q-input v-model="product.alert_threshold"  type="number" label="Alert" outlined dense hint="" />
                <q-input v-model="product.reference"  type="text" label="Reference Produit" outlined dense hint="" />

                <q-input v-model="product.youtube" type="text" label="Url Video Youtube *" outlined dense hint="" />

                <div class="row">
                  <q-input v-model="product.largeur" class="col-6 q-pa-xs" type="number" label="Largeur en mm" :dense="true" outlined />
                  <q-input v-model="product.longueur" class="col-6 q-pa-xs" type="number" label="Longueur en mm" :dense="true" outlined />
                  <q-input v-model="product.hauteur" class="col-6 q-pa-xs" type="number" label="Hauteur en mm" :dense="true" outlined />
                  <q-input v-model="product.epaisseur" class="col-6 q-pa-xs" type="number" label="Epaisseur en mm" :dense="true" outlined />
                  <q-input v-model="product.poids" class="col-6 q-pa-xs" type="number" label="Poids en KG" :dense="true" outlined />
                  <q-input v-model="product.diametre" class="col-6 q-pa-xs" type="number" label="Diamètre" :dense="true" outlined />
                </div>

              </div>
              <div class="col-md-5 col-sm-12 content-center text-center">
                <vue-qr :size="200" :text="JSON.stringify(product.id)" :callback="test" qid="testid" />
                <!--                <br>-->
                <!--                <q-uploader-->
                <!--                  v-model="product.photo"-->
                <!--                  class="text-center"-->
                <!--                  :url="apiurl+'/my/photo/products/'+product.id"-->
                <!--                  color="grey"-->
                <!--                  :headers="[{name: 'Authorization', value: 'Bearer '+token}]"-->
                <!--                  :form-fields="[{name: 'id', value: product.id}]"-->
                <!--                  flat-->
                <!--                  bordered-->
                <!--                  style="max-width: 200px; margin: 0 auto"-->
                <!--                />-->
                <!--                <br>-->
                <div class="row q-pa-lg">
                  <div class="col-12">
                    <filescomponent type="product" folder="product" :typeid="product.id" />
                  </div>
                </div>
              </div>
            </div>
            <q-editor v-model="product.description" min-height="5rem" :toolbar="toolbar" />
            <q-btn v-if="!product.id" :loading="loading1" label="Ajouter" type="submit" color="primary"/>
            <q-btn
              v-if="product.id" :loading="loading1" label="Modifier" type="button"
              color="primary" @click="products_update(product)"/>
            <q-btn  label="Annuler" type="reset" color="negative" outline class="q-ml-sm" />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn v-close-popup flat label="Fermer" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="medium">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Gestion des photos</div>
        </q-card-section>

        <q-card-section>
          <hello-component :width="300" :height="300" :typerubrique="1" :idligne="product_id" folder="product" />
          <br>
          <br>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn v-close-popup flat label="Fermer" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="vente_status" transition-show="slide-up" transition-hide="slide-down">

      <q-table
        :rows="sales_stats" :columns="sales_columns" style="width: 800px; max-width: 100%"
        row-key="id" :pagination="pagination">
        <template #top>
          <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
        </template>
        <template #top-left>
          <div class="row">
            <div class="col-5 "><q-input v-model="first"  type="date" label="debut" /></div>
            <div class="col-5"><q-input v-model="last"  type="date" label="fin" /></div>
            <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" @click="sales_stats_get(product_id)"/></div>
          </div>
        </template>
        <template #top-right>
          <q-btn size="sm" :label="'Nb Produits vendus: '+ numerique(nbre_vendus)" /><br>
          <q-btn size="sm" class="q-ml-sm" :label="'total: '+numerique(montant_vendus)+' FCFA'" />
          <!--          <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"-->
          <!--                 @click="props.toggleFullscreen" class="q-ml-md float-right" />-->
        </template>
      </q-table>

    </q-dialog>

    <q-dialog v-model="appro_status" transition-show="slide-up" transition-hide="slide-down">
      <q-table
        :rows="appro_stats" :columns="appro_columns" style="width: 800px; max-width: 100%"
        row-key="id" :pagination="pagination">
        <template #top-left>
          <div class="row">
            <div class="col-5 "><q-input v-model="first"  type="date" label="debut" /></div>
            <div class="col-5"><q-input v-model="last"  type="date" label="fin" /></div>
            <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" @click="appro_stats_get(product_id)" /></div>
          </div>
        </template>
        <template #top-right>
          <q-btn size="sm" :label="'Nbre de produits achetes: '+ numerique(nbre_achetes)" /><br>
          <q-btn size="sm" class="q-ml-sm" :label="'Montant total: '+numerique(montant_achetes)+' FCFA'" />
          <!--          <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"-->
          <!--                 @click="props.toggleFullscreen" class="q-ml-md float-right" />-->
        </template>
      </q-table>
    </q-dialog>

    <q-dialog v-model="stat_status" transition-show="slide-up" transition-hide="slide-down">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 500px">
          <q-card class="my-card">
            <q-card-section>
              <areachart :series="series_vente_sum" title="Montants Vendus en FCFA" titletooltip="vente" />
            </q-card-section>
          </q-card>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 q-pa-sm" style="min-width: 500px">
          <q-card class="my-card">
            <q-card-section>
              <areachart color="#A66172" :series="series_appro_sum" title="Montants Achetes en FCFA" titletooltip="achat" />
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-dialog>


  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
// import VueQr from 'vue-qr';
import vueQr from 'vue-qr/src/packages/vue-qr.vue'
import * as _ from 'lodash';
import HelloComponent from '../components/hello.vue';
import basemixin from './basemixin';
import AreachartComponent from '../components/areachart.vue';
import {ClientApi} from "src/services/api/ClientApi";
import {SProductPrixApi} from "src/services/api/SProductPrixApi";
import {LocalStorage} from "quasar";
import Filescomponent from "components/filescomponent.vue";
import {ProductApi} from "src/services/api/ProductApi";
export default {
  name: 'ProduitName',
  components: {
    Filescomponent,
    areachart: AreachartComponent,
    HelloComponent,
    vueQr
  },
  mixins: [basemixin],
  data () {
    return {
      product_id: 1,
      loading1: false,
      medium: false,
      medium2: false,
      prix_status: false,
      stat_status: false,
      vente_status: false,
      appro_status: false,
      entreprise: {},
      prices_list: [],
      sales_stats: [],
      appro_stats: [],
      nbre_achetes: 0,
      montant_achetes: 0,
      nbre_vendus: 0,
      montant_vendus: 0,
      vente_sum: [],
      appro_sum: [],
      marques: [],
      series_appro_sum: [{ name: 'Montant Achete par mois', data: [] }],
      series_vente_sum: [{ name: 'Montant Vendu par mois', data: [] }],
      image: null,
      parents: [],
      parents2: [],
      categories: [],
      categories2: [],
      categoriesall: [],
      domains: [],
      domains2: [],
      products: [],
      clients: [],
      product: { alert_threshold: 0, description: '', stock: 0, buy_price: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1, customize: 0 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        // { name: 'photo', align: 'left', label: 'photo' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'domainname', align: 'left', label: 'Categorie', field: 'domainname', sortable: true },
        { name: 'parent_categorie_name', align: 'left', label: 'Parent', field: 'parent_categorie_name', sortable: true },
        // { name: 'categorie', align: 'left', label: 'Catégorie', field: 'categorie', sortable: true },
        // { name: 'marque', align: 'left', label: 'Marque', field: 'marque', sortable: true },
        // { name: 'amount', align: 'left', label: 'Qté Reste', field: 'amount', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      sales_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'quantite_vendu', align: 'center', label: 'Qté', field: 'quantite_vendu', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'prix_unitaire', label: 'prix_vente', field: 'prix_unitaire', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'montant_vendu', label: 'Montant Vendu', field: 'montant_vendu', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'dateposted', label: 'Date Vente', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      appro_columns: [
        { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
        { name: 'amount', label: 'Quantite', field: 'amount', align: 'left', sortable: true, format: val => `${this.numerique(val)}` },
        { name: 'p_buying_price', align: 'center', label: 'Prix Achat', field: 'p_buying_price', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'p_sell_price', label: 'Prix Vente', align: 'left', field: 'p_sell_price', format: val => `${this.numerique(val)}`, sortable: true },
        { name: 'dateposted', label: 'Date Achat', align: 'left', field: 'dateposted', sortable: true, format: val => `${this.dateformat(val, 3)}` }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  created () {
    this.products_get();
    this.categories_all();
    this.marques_get();
    this.shop_get();
    ClientApi.get().then((res) => { this.clients = res; console.log(res)})
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.products_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    test(dataUrl) {
      this.image = dataUrl;
    },
    async product_disable(props) {
      const res = await ProductApi.disabledProduct(props.id);
      console.log(res);
      this.$q.notify({
        color: 'green', position: 'top', message: res.msg, icon: 'report_problem'
      });
      this.products_get();
    },
    update_get(props) {
      this.product = props;
      this.product.categories = props.product_categories_id;
      this.product.description = props.product_desc;
      this.parent_get(props.domainid);
      this.categorie_get(props.parent_categorie_id);
      this.product_id = props.id;
      this.medium2 = true;
    },
    // alerte(item) {
    // if (item.reste <= item.alert_threshold) {
    //   return 'bg-red-1';
    // }
    // },
    products_post: function () {
      if (confirm('Voulez vous ajouter ?')) {
        this.loading1 = true;
        $httpService.postWithParams('/my/post/products', this.product)
          .then((response) => {
            this.products_get();
            this.$q.notify({
              color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
            });
            this.loading1 = false;
            $httpService.postWithParams('/my/post/qr_code', {
              id: response['id'], typerubrique: 1, file: this.image
            });
          }).catch(() => {
          this.loading1 = false;
        })
      }
    },
    categories_all () {
      $httpService.getWithParams('/my/get/categories_all')
        .then((response) => {
          this.categoriesall = response;
          this.domains = response[0];
          this.domains2 = response[0];
          this.parents = response[1];
          this.parents2 = response[1];
          this.categories = response[2];
          this.categories2 = response[2];
        })
    },
    parent_get(value) {
      let val = this.parents2.filter((x) => {
        return x.domainid == value;
      });
      this.parents = val;
    },
    categorie_get (parentid) {
      let val = this.categories2.filter((x) => {
        return x.parentid == parentid;
      });
      this.categories = val;
    },
    marques_get () {
      $httpService.getWithParams('/my/get/marques')
        .then((response) => {
          this.marques = response;
        })
    },
    products_get () {
      $httpService.getWithParams('/my/get/products')
        .then((response) => {
          this.products = response;
        })
    },
    products_update (prod) {
      if (confirm('Voulez vous modifier ?')) {
        $httpService.putWithParams('/my/put/products', prod)
          .then((response) => {
            this.products_get();
            this.$q.notify({
              color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
            });
          })
      }
    },
    appro_stats_get(pid) {
      let params = { 'first': this.first, 'last': this.last, 'pid': pid };
      $httpService.getWithParams('/my/get/appro_product_stats', params)
        .then((response) => {
          this.appro_stats = response;
          this.nbre_achetes = _.sumBy(this.appro_stats, 'amount');
          this.montant_achetes = _.sumBy(this.appro_stats, 'buying_price');
        })
    },
    sales_stats_get(pid) {
      let params = { 'first': this.first, 'last': this.last, 'pid': pid };
      $httpService.getWithParams('/my/get/sales_product_stats', params)
        .then((response) => {
          this.sales_stats = response;
          this.nbre_vendus = _.sumBy(this.sales_stats, 'quantite_vendu');
          this.montant_vendus = _.sumBy(this.sales_stats, 'montant_vendu');
        })
    },
    appro_stats_global(_id) {
      $httpService.getWithParams('/my/get/products_stats/' + _id)
        .then((response) => {
          this.appro_sum = JSON.parse(response.appro_sum.replace(/null/g, '0'));
          const val2 = Object.values(this.appro_sum);
          this.series_appro_sum = [{ name: 'Francs CFA.', data: val2 }];

          this.vente_sum = JSON.parse(response.vente_sum.replace(/null/g, '0'));
          const val3 = Object.values(this.vente_sum);
          this.series_vente_sum = [{ name: 'Francs CFA.', data: val3 }];
        });
    },
    s_product_prix_get (__id) {
      SProductPrixApi.getId(__id).then((res) => { this.prices_list = res })
    },
    s_product_prix_post (params) {
      params.product_id = this.product_id
      this.showLoading()
      SProductPrixApi.post(params)
        .then((res) => {
          this.hideLoading()
          console.log(res)
        })
    },
  }
}
</script>

<style>
</style>
