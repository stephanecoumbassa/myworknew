<template>
  <q-page>

    <div class="row text-center q-pa-sm">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-sm text-center">
        <q-card class="my-card text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <div class="text-h5 text-center">Rubrique: Produits</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-sm">
        <router-link class="item item-link" to="/produits/pertes">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h6">Pertes</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-sm">
        <router-link class="item item-link" to="/produits/marques">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h6">Marques</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-sm">
        <router-link class="item item-link" to="/produits/inventaire">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h6">Inventaires</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 q-pa-sm">
        <router-link class="item item-link" to="/produits/resume">
          <q-card class="my-card" clickable>
            <q-item clickable>
              <q-card-section>
                <div class="text-h6">Mouvements</div>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

    </div>

    <div class="row justify-center q-pa-sm">
      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md q-pa-sm">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary"
               v-on:click="product = { description: '', stock: 0, buy_price: 0, price: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 }; medium2 = true" />&nbsp;&nbsp;
        <q-btn label="Liste des produits désactivés" class="q-mb-lg" size="sm" color="grey-8" />
        <br><br>
        <q-icon name="dashboard_customize" />

        <q-table dense title="Produits" :rows="products" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
          <template v-slot:top>
            <div class="col-7 q-table__title">Liste des produits</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" :class="alerte(props.row)" :key="props.row.id">
              <q-td key="id" :props="props">
                {{props.row.id}}
                <q-icon name="dashboard_customize" v-if="props.row.customize" />
              </q-td>
              <q-td key="photo" :props="props">
                <img v-if="props.row.photos" :src="uploadurl+'/'+entreprise.id+'/product/'+JSON.parse(props.row.photos)[0]['name']" style="width: 50px; height: 50px; object-fit: cover"/>
              </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="domainname" :props="props"> {{props.row.domainname}} </q-td>
              <q-td key="parent_categorie_name" :props="props"> {{props.row.parent_categorie_name}} </q-td>
              <q-td key="categorie" :props="props"> {{props.row.categorie}} </q-td>
              <q-td key="marque" :props="props"> {{props.row.marque}} </q-td>
              <q-td key="amount" :props="props"> {{numerique(props.row.reste)}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="grey-9" v-on:click="stat_status = true; product_id = props.row.id; appro_stats_global(props.row.id); " label="stat" />
                <q-btn class="q-mr-xs" size="xs" color="grey-9" v-on:click="vente_status = true; product_id = props.row.id; sales_stats_get(props.row.id); " label="vente" />
                <q-btn class="q-mr-xs" size="xs" color="grey-9" v-on:click="appro_status = true; product_id = props.row.id; appro_stats_get(props.row.id);" label="achat" />
                <q-btn class="q-mr-xs" size="xs" color="teal" v-on:click="update_get(props.row)" icon="edit" />
                <q-btn class="q-mr-xs" size="xs" color="blue-grey-7" v-on:click="photo_get(props.row)" icon="photo" />
                <q-btn size="xs" color="red-9" title="Désactivé" v-on:click="product_disable(props.row)" icon="toggle_off" />
                <!--<q-btn v-if="role == 1" class="q-mr-xs" size="xs" color="red" icon="delete"></q-btn>-->
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>
    <br>

    <q-dialog v-model="medium2">
      <q-card style="width: 900px; max-width: 90vw;">
        <q-card-section>
          <div class="text-h6">Ajouter un produit</div>
        </q-card-section>

        <q-card-section>
          <q-form  @submit="onSubmit" class="q-gutter-md">
            <div class="row">
              <div class="col-md-7 col-sm-12">
                <q-input autocomplete v-model="product.name" id="name" label="Nom du produit *" :dense="true"
                         lazy-rules :rules="[ val => val && val.length > 0 || 'champs obligattoire']" />

                <q-select v-model="product.domainid" :options="domains" id="domainid" label="Domains" map-options emit-value :dense="true"
                          option-value="id" stack-label input-debounce="0" option-label="name"
                          @input="parent_get(product.domainid)" :rules="[ val => val || 'champs obligattoire']" />

                <q-select v-model="product.parent_categorie_id" :options="parents" id="parent_categorie_id" label="Parents" map-options emit-value :dense="true"
                          option-value="id" stack-label input-debounce="0" option-label="name"
                          @input="categorie_get(product.parent_categorie_id)" :rules="[ val => val || 'champs obligattoire']" />

                <q-select v-model="product.product_categories_id" :options="categories" id="product_categories_id" label="Categories" map-options emit-value :dense="true"
                          option-value="id" stack-label input-debounce="0" option-label="name" />
                <br>
                <q-select v-model="product.marque_id" :options="marques" label="Marques" map-options emit-value :dense="true"
                          option-value="id" stack-label input-debounce="0" option-label="nom" />
                <br>
                <q-input autocomplete type="number" v-model="product.price" label="Prix de vente *" :dense="true" />
                <br>
                <q-input type="number"  v-model="product.tva" label="TVA *" />
                <br>
                <q-input autocomplete type="number"  v-model="product.promo" label="Prix de promotionel" :dense="true" />

                <div class="q-gutter-sm">
                  <br>
                  <label>Voulez vous vendre sur internet</label>
                  <q-radio v-model="product.webstatus" :val="0" label="Non" />
                  <q-radio v-model="product.webstatus" :val="1" label="Oui" />
                </div>

                <div class="q-gutter-sm">
                  <br>
                  <label>Ce produit est-il customizable </label>
                  <q-radio v-model="product.customize" :val="0" label="Non" />
                  <q-radio v-model="product.customize" :val="1" label="Oui" />
                </div>

                <q-input type="number" v-if="product.webstatus" v-model="product.priceweb" label="Prix sur Internet *" :dense="true" />
                <q-input type="number"  v-model="product.alert_threshold" label="Alert *" />
                <!--<q-input autocomplete type="textarea"  v-model="product.serial" label="Numero de serie" />-->
                <q-input type="text"  v-model="product.reference" label="Reference Produit" />

                <q-input v-if="!product.id" type="number" v-model="product.buy_price" label="Prix achat *" />
                <q-input v-if="!product.id" type="number" v-model="product.stock" label="Stock Initial *" />

                <q-input type="text" v-model="product.youtube" label="Url Video Youtube *" />

                <div class="row">
                  <q-input class="col-6 q-pa-xs" type="number" v-model="product.largeur" label="Largeur en m" :dense="true" />
                  <q-input class="col-6 q-pa-xs" type="number" v-model="product.longueur" label="Longueur en m" :dense="true" />
                  <q-input class="col-6 q-pa-xs" type="number" v-model="product.hauteur" label="Hauteur en m" :dense="true" />
                  <q-input class="col-6 q-pa-xs" type="number" v-model="product.poids" label="Poids en KG" :dense="true" />
                </div>

              </div>
              <div class="col-md-5 col-sm-12 content-center text-center">
                <vue-qr :size="200" :text="JSON.stringify(product)" :callback="test" qid="testid" />
              </div>
            </div>
            <q-editor v-model="product.description" min-height="5rem" :toolbar="toolbar" />
            <q-btn :loading="loading1" label="Ajouter" type="submit" color="primary" v-if="!product.id"/>
            <q-btn :loading="loading1" label="Modifier" type="button" v-if="product.id"
                   v-on:click="products_update(product)" color="primary"/>
            <q-btn  label="Annuler" type="reset" color="negative" outline class="q-ml-sm" />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Fermer" v-close-popup />
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
          <q-btn flat label="Fermer" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-dialog v-model="vente_status" transition-show="slide-up" transition-hide="slide-down">

      <q-table :rows="sales_stats" :columns="sales_columns" style="width: 800px; max-width: 100%"
               row-key="id" :pagination="pagination">
        <template v-slot:top>
          <span>{{'Ventes du '+ dateformat(first)+ ' au '+ dateformat(last)}}</span>
        </template>
        <template v-slot:top-left>
          <div class="row">
            <div class="col-5 "><q-input type="date"  v-model="first" label="debut" /></div>
            <div class="col-5"><q-input type="date"  v-model="last" label="fin" /></div>
            <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" v-on:click="sales_stats_get(product_id)"/></div>
          </div>
        </template>
        <template v-slot:top-right>
          <q-btn size="sm" :label="'Nb Produits vendus: '+ numerique(nbre_vendus)" /><br>
          <q-btn size="sm" class="q-ml-sm" :label="'total: '+numerique(montant_vendus)+' FCFA'" />
          <!--          <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"-->
          <!--                 @click="props.toggleFullscreen" class="q-ml-md float-right" />-->
        </template>
      </q-table>

    </q-dialog>

    <q-dialog v-model="appro_status" transition-show="slide-up" transition-hide="slide-down">
      <q-table :rows="appro_stats" :columns="appro_columns" style="width: 800px; max-width: 100%"
               row-key="id" :pagination="pagination">
        <template v-slot:top-left>
          <div class="row">
            <div class="col-5 "><q-input type="date"  v-model="first" label="debut" /></div>
            <div class="col-5"><q-input type="date"  v-model="last" label="fin" /></div>
            <div class="col-2"><br><q-btn size="sm" type="submit" label="filtrer" v-on:click="appro_stats_get(product_id)" /></div>
          </div>
        </template>
        <template v-slot:top-right>
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
export default {
  name: 'ProduitName',
  data () {
    return {
      product_id: 1,
      loading1: false,
      red: '#6d1412',
      first: null,
      last: null,
      medium: false,
      medium2: false,
      stat_status: false,
      vente_status: false,
      appro_status: false,
      entreprise: {},
      sales_stats: [],
      appro_stats: [],
      nbre_achetes: 0,
      montant_achetes: 0,
      nbre_vendus: 0,
      montant_vendus: 0,
      vente_sum: [],
      appro_sum: [],
      marques: [],
      series: [{ name: 'Nbre de Produit.', data: [] }],
      series_appro_sum: [{ name: 'Montant Achete par mois', data: [] }],
      series_vente_count: [{ name: 'Montant Achete.', data: [] }],
      series_vente_sum: [{ name: 'Montant Vendu par mois', data: [] }],
      maximizedToggle: true,
      name: null,
      image: null,
      productid: null,
      parents: [],
      parents2: [],
      categories: [],
      categories2: [],
      categoriesall: [],
      domains: [],
      domains2: [],
      users: [],
      products: [],
      product: { description: '', stock: 0, buy_price: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1, customize: 0 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'photo', align: 'left', label: 'photo' },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'domainname', align: 'left', label: 'Domaine', field: 'domainname', sortable: true },
        { name: 'parent_categorie_name', align: 'left', label: 'Parent', field: 'parent_categorie_name', sortable: true },
        { name: 'categorie', align: 'left', label: 'Catégorie', field: 'categorie', sortable: true },
        { name: 'marque', align: 'left', label: 'Marque', field: 'marque', sortable: true },
        { name: 'amount', align: 'left', label: 'Qté Reste', field: 'amount', sortable: true },
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
      data: [],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 },
    }
  },
  components: {
    areachart: AreachartComponent,
    HelloComponent,
    vueQr
  },
  mixins: [basemixin],
  created () {
    this.products_get();
    this.categories_all();
    this.marques_get();
    this.shop_get();
    // this.domain_get();
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
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
    photo_get(props) {
      this.product_id = props.id;
      this.medium = true;
    },
    product_disable(props) {
      this.product_id = props.id;
      if (confirm('Voulez vous désactiver ?')) {
        $httpService.postWithParams('/my/disabled/products/' + this.product_id)
          .then((response) => {
            this.products_get();
            this.$q.notify({
              color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
            });
          })
      }
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
    alerte(item) {
      if (item.reste <= item.alert_threshold) {
        return 'bg-red-1';
      }
    },
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
    categories_delete (id) {
      if (confirm('Voulez vous supprimer ?')) {
        $httpService.deleteWithParams('/api/s_product_categories/' + id)
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
    }
  }
}
</script>

<style>
</style>
