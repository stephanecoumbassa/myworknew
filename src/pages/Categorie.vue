<template>
  <q-page padding>
    <!-- content -->
    <div class="row justify-center">

      <div class="col-10 col-xs-12 q-pa-sm" style="background-color: white">
        <h6>Mouvelle Categorie &nbsp;&nbsp;&nbsp;&nbsp;<a
href="https://material.io/resources/icons/?style=baseline"
                                                          target="_blank">Voir les toutes icons</a></h6>
        <q-form class="q-gutter-md" @submit="onSubmit">

          <q-select
v-model="domainid" filled :options="domains" label="Domaine" map-options emit-value
                    option-value="id" stack-label input-debounce="0" option-label="name" :dense="true"  @input="parent_filter_get(domainid)" />

          <q-select
v-model="parentid" filled :options="parents" label="Parent" map-options emit-value
                    option-value="id" stack-label input-debounce="0" option-label="name" :dense="true" />

          <q-input
v-model="name" filled label="Categories *" hint="Nom de Categorie" :dense="true"
                   lazy-rules :rules="[ val => val && val.length > 0 || 'Champs requis']" />

          <q-input v-model="icon" filled label="Icon *" hint="Icon" :dense="true" />

          <div>
            <q-btn label="Valider" type="submit" color="secondary"/>
            <q-btn label="Annuler" type="reset" color="red" class="q-ml-sm" />
          </div>
        </q-form>
      </div>

      <div class="col-10 col-xs-12 q-pa-sm">
        <br><br>

        <q-table
title="Treats" class="q-pa-sm"
                 :rows="categories" :columns="columns" :pagination="pagination" row-key="name">
          <template #top="props">
            <div class="col-11 q-table__title">Liste des categories</div>
            <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">{{ props.row.id }}</q-td>
              <q-td key="name" :props="props">
                <q-input v-model="props.row.name" filled type="text" dense autofocus />
              </q-td>
              <q-td key="parentid" :props="props">
                <q-select
v-model="props.row.parentid" filled :options="parents" label="" map-options emit-value use-input
                          option-value="id" stack-label option-label="fullname" :dense="true" @filter="filterFn" />
              </q-td>
            </q-tr>
          </template>
        </q-table>

        <br><br>

        <!--        <q-table title="Treats" :data="parents" :columns="domains_columns" :pagination="pagination" row-key="name">-->
        <!--          <template v-slot:top="props">-->
        <!--            <div class="col-11 q-table__title">Liste des categories parentes</div>-->
        <!--            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"-->
        <!--                   @click="props.toggleFullscreen" class="q-ml-md" />-->
        <!--          </template>-->
        <!--          <template v-slot:body="props">-->
        <!--            <q-tr :props="props">-->
        <!--              <q-td key="id" :props="props">{{ props.row.id }}</q-td>-->
        <!--              <q-td key="name" :props="props">-->
        <!--                  <q-input borderless type="text" v-model="props.row.name" dense autofocus />-->
        <!--              </q-td>-->
        <!--              <q-td key="domainid" :props="props">-->
        <!--                <q-select borderless v-model="props.row.domainid" :options="domains" label="" map-options emit-value-->
        <!--                          option-value="id" stack-label input-debounce="0" option-label="name"  :dense="true" />-->
        <!--              </q-td>-->
        <!--              <q-td key="actions" :props="props">-->
        <!--                <q-btn v-if="props.row.id_magasin" size="xs" color="secondary" v-on:click="parent_put(props.row)" icon="save" />-->
        <!--                <q-btn v-if="props.row.id_magasin" size="xs" color="red" v-on:click="categories_delete(props.row.id)" icon="delete" />-->
        <!--              </q-td>-->
        <!--            </q-tr>-->
        <!--          </template>-->
        <!--        </q-table>-->

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
export default {
  name: 'CategoriePage',
  data () {
    return {
      name: null,
      icon: null,
      type: null,
      parentid: null,
      domainid: null,
      categories: [],
      domains: [],
      parents: [],
      parents2: [],
      pagination: {
        sortBy: 'name',
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 10
      },
      options: ['Categorie', 'Parent'],
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'parentid', align: 'left', label: 'Parent', field: 'parentid', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      domains_columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'domainid', align: 'left', label: 'Domaine', field: 'domainid', sortable: true },
        { name: 'actions', label: 'Actions' }
      ],
      data: []
    }
  },
  created () {
    this.categories_get();
    this.domain_get();
    this.parent_get();
  },
  methods: {
    onSubmit () {
      if (this.accept !== true) {
        this.categories_post();
      } else {
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'fas fa-check-circle',
          message: 'Submitted'
        })
      }
    },
    categories_post () {
      $httpService.postWithParams('/my/post/categories_stock', { name: this.name, icon: this.icon, parentid: this.parentid })
        .then(() => {
          this.categories_get();
          this.$q.notify({
            color: 'green', position: 'top', message: 'Catégorie Ajoutée', icon: 'report_problem'
          });
        })
    },
    categories_get() {
      $httpService.getWithParams('/my/get/categories_stock')
        .then((response) => {
          this.categories = response;
        })
    },
    domain_get() {
      $httpService.getWithParams('/api/get/categories_domain')
        .then((response) => {
          this.domains = response;
        })
    },
    parent_filter_get(value) {
      let val = this.parents2.filter((x) => {
        return x.domainid == value;
      });
      this.parents = val;
    },
    parent_get() {
      $httpService.getWithParams('/my/get/categories_parent')
        .then((response) => {
          this.parents = response;
          this.parents2 = response;
        })
    },
    parent_post() {
      $httpService.postWithParams('/my/post/categories_parent', { 'name': this.name, 'domainid': this.domainid })
        .then(() => {
          this.parent_get();
          this.$q.notify({
            color: 'green', position: 'top', message: 'Catégorie Parente Ajoutée', icon: 'report_problem'
          });
        })
    },
    parent_put(item) {
      $httpService.putWithParams('/my/put/categories_parent', item)
        .then((response) => {
          this.parent_get();
          this.$q.notify({
            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
          });
        })
    },
    categories_put(item) {
      $httpService.putWithParams('/my/put/categories_stock', item)
        .then((response) => {
          this.categories_get();
          this.$q.notify({
            color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
          });
        })
    },
    categories_delete(id) {
      $httpService.deleteWithParams('/api/s_product_categories/' + id)
        .then(() => {
          this.categories_get();
          this.$q.notify({
            color: 'green', position: 'top', message: 'Supprimé', icon: 'report_problem'
          });
        })
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.parents = this.parents2.filter(
          (v) => { return v.fullname.toLocaleLowerCase().indexOf(needle) > -1 }
        );
      })
    }
  }
}
</script>

<style>
</style>
