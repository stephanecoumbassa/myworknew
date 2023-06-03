<template>
  <q-page>

    <div class="row justify-center text-center">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-lg text-center">
        <q-card class="my-card text-center justify-center content-center" flat>
          <q-item clickable>
            <q-card-section>
              <h5 class="text-center">Rubrique: Produits à louer</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>

    <div class="row justify-center q-pa-md">
      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md">

        <q-table title="Produits" :rows="products" :columns="columns" :pagination="pagination" :filter="filter" row-key="name" flat>
          <template #top="props">
            <div class="col-7 q-table__title">Liste des produits</div>
            <q-input v-model="filter"  dense debounce="300" placeholder="Rechercher" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md" @click="props.toggleFullscreen" />
            <q-btn
              label="Ajouter" class="q-mt-sm" size="sm" icon="add" color="secondary"
              @click="product = { description: '', stock: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 }; medium2 = true" />
          </template>
          <template #body="props">
            <q-tr :props="props" :class="alerte(props.row)">
              <q-td key="id" :props="props"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props"> {{props.row.name}} </q-td>
              <q-td key="domainname" :props="props"> {{props.row.domainname}} </q-td>
              <q-td key="parent_categorie_name" :props="props"> {{props.row.parent_categorie_name}} </q-td>
              <q-td key="amount" :props="props"> {{numerique(props.row.reste)}} </q-td>
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="teal" icon="edit" @click="update_get(props.row)" />
                <q-btn class="q-mr-xs" size="xs" color="blue-grey-7" label="photo" icon="photo" @click="photo_get(props.row)" />
                <q-btn size="xs" color="red-9" label="désactivé" icon="toggle_off" @click="product_disable(props.row)" />
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <q-dialog v-model="medium2">
          <q-card style="width: 900px; max-width: 90vw;">
            <q-card-section>
              <div class="text-h6">Ajouter un produit locatif</div>
            </q-card-section>

            <q-card-section>
              <q-form  class="q-gutter-md" @submit="onSubmit">
                <div class="row">
                  <div class="col-md-6 col-sm-12 q-pa-lg">
                    <q-input
                      v-model="product.name" autocomplete label="Nom du produit *"
                      lazy-rules :rules="[ val => val && val.length > 0 || 'champs obligattoire']" />

                    <q-select
                      v-model="product.domain_id" :options="domains" label="Domains" map-options emit-value
                      option-value="id" stack-label input-debounce="0" option-label="name"
                      :rules="[ val => val || 'champs obligattoire']" @input="parent_get(product.domain_id)" />

                    <q-select
                      v-model="product.parent_categorie_id" :options="parents" label="Parents" map-options emit-value
                      option-value="id" stack-label input-debounce="0" option-label="name" :rules="[ val => val || 'champs obligattoire']" />

                    <q-select
                      v-model="product.marque_id" :options="marques" label="Marques" map-options emit-value
                      option-value="id" stack-label input-debounce="0" option-label="nom" />
                    <br>
                    <q-input v-model="product.price_jour" autocomplete  type="number" label="Prix jour *" />
                    <br>
                    <q-input v-model="product.price_week" autocomplete  type="number" label="Prix semaine *" />
                    <br>
                    <q-input v-model="product.price_month" autocomplete  type="number" label="Prix mois *" />
                    <div class="q-gutter-sm">
                      <br>
                      <label>Voulez vous vendre sur internet</label>
                      <q-radio v-model="product.webstatus" :val="0" label="Non" />
                      <q-radio v-model="product.webstatus" :val="1" label="Oui" />
                    </div>

                  </div>
                  <div class="col-md-6 col-sm-12 q-pa-lg">
                    <q-input v-model="product.tva"  type="number" label="TVA *" />
                    <q-input v-model="product.quantity"  type="number" label="Quantité *" />
                    <q-input v-model="product.reference"  type="text" label="Reference Produit" />
                    <q-input v-model="product.youtube" type="text" label="Url Video Youtube *" />

                    <q-input v-model="product.largeur" class="q-pa-xs" type="number" label="Largeur en m" />
                    <q-input v-model="product.longueur" class="q-pa-xs" type="number" label="Longueur en m" />
                    <q-input v-model="product.hauteur" class="q-pa-xs" type="number" label="Hauteur en m" />
                    <q-input v-model="product.poids" class="q-pa-xs" type="number" label="Poids en KG" />

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
              <hello-component :width="300" :height="300" :typerubrique="2" :idligne="product_id" folder="product" />
            </q-card-section>

            <q-card-actions align="right" class="bg-white text-dark">
              <q-btn v-close-popup flat label="Fermer" />
            </q-card-actions>
          </q-card>
        </q-dialog>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import HelloComponent from '../components/hello.vue';
import basemixin from './basemixin';
export default {
  name: 'ProduitLocationPage',
  components: {
    HelloComponent,
  },
  mixins: [basemixin],
  data () {
    return {
      product_id: 1,
      loading1: false,
      medium: false,
      medium2: false,
      marques: [],
      parents: [],
      parents2: [],
      categories: [],
      categories2: [],
      categoriesall: [],
      domains: [],
      domains2: [],
      products: [],
      product: { description: '', stock: 0, webstatus: 1, domainid: 1, parent_categorie_id: 1 },
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'domainname', align: 'left', label: 'Domaine', field: 'domainname', sortable: true },
        { name: 'parent_categorie_name', align: 'left', label: 'Categorie', field: 'parent_categorie_name', sortable: true },
        { name: 'amount', align: 'left', label: 'Quantité Restante', field: 'amount', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 20 }
    }
  },
  created () {
    this.products_get();
    this.categories_all();
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.products_post();
      } else {
        this.$q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
      }
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
        $httpService.postWithParams('/my/post/products_location', this.product)
          .then((response) => {
            this.products_get();
            this.$q.notify({
              color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
            });
            this.loading1 = false;
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
    products_get () {
      $httpService.getWithParams('/my/get/products_location')
        .then((response) => {
          this.products = response;
        })
    },
  }
}
</script>

<style>
</style>
