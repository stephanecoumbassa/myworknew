<template>
  <q-layout view="hHh lpR fFf" style="background-color: #f9f9f9; max-width: 1200px; width: 100%; margin: auto">
    <q-header elevated class="bg-white text-grey-8" height-hint="32" style="background-color: #f9f9f9; max-width: 1200px; width: 100%; margin: auto">
      <q-toolbar class="GNL__toolbar bg-dark-separator text-white">
        <q-btn
flat dense round color="secondary" aria-label="Menu" icon="menu"
               class="q-mr-sm" @click="leftDrawerOpen = !leftDrawerOpen" />

        <q-toolbar-title v-if="$q.screen.gt.xs" shrink class="row items-center" >
<!--          <img :src="'https://batison.com/apistock/public/assets/uploads/magasin/'+entreprise.logo"-->
<!--               style="width: 50px; height: 50px; object-fit: cover"/>-->
<!--          <span class="q-ml-sm">News</span>-->
        </q-toolbar-title>
        <q-space />

        <q-space />

        <div class="q-gutter-sm row items-center no-wrap">
          <br>
          <q-btn round flat color="secondary" icon="shopping_cart" @click="show_cart()">
            <q-badge color="red" text-color="white" floating>
              {{countCart}}
            </q-badge>
            <q-tooltip>Panier</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer  v-model="leftDrawerOpen" bordered content-class="bg-white" :width="280">
      <q-scroll-area class="fit">
        <q-list padding class="text-grey-8">
          <img
:src="'https://batison.com/apistock/public/assets/uploads/magasin/'+entreprise.logo" class="q-ml-md"
               style="width: 50px; height: 50px; object-fit: cover"/>
          <br>
          <br>
          <q-item v-for="link in links1" :key="link.text" v-ripple class="GNL__drawer-item" clickable>
            <q-item-section avatar>
              <q-icon :name="link.icon" />
            </q-item-section>
            <router-link class="item item-link" :to="link.link">
              <q-item-section>
                <q-item-label>{{ link.text }}</q-item-label>
              </q-item-section>
            </router-link>
          </q-item>

        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-drawer v-model="right" side="right" bordered content-class="bg-white">
    <h6 class="text-center">Panier</h6>
      <div v-for="(product, index) in products" :key="index" class="row">
        <q-item>
          <q-item-section top avatar>
            <q-avatar rounded>
              <q-img :src="'http://batison.com/apistock/public/assets/uploads/products/'+JSON.parse(product.photos)[0].name"></q-img>
            </q-avatar>
            <q-btn round flat color="red" icon="delete" @click="delete_cart(index)"></q-btn>
          </q-item-section>

          <q-item-section>
            <q-item-label class="no-padding no-margin no-border">{{product.name}}</q-item-label>
            <q-input v-model="product.qty"  borderless class="no-padding no-margin no-border" />
          </q-item-section>

          <q-item-section side top>
          {{product.price}}<br><br>
            <q-badge color="secondary" :label="product.price * product.qty+' FCFA'" />
          </q-item-section>
        </q-item>
        <q-separator spaced />
      </div>
      <q-input v-model="name" class="no-border q-ma-sm" label="Nom et Prenom"/>
      <q-input v-model="telephone" class="no-border q-ma-sm" label="Telephone"/>
      <q-input v-model="email" class="no-border q-ma-sm" label="Email" /><br>
      <p class="text-center">
        <q-btn class="text-center q-ml-sm"  color="secondary" label="COMMANDER" icon="shopping_cart" @click="command_cart()"></q-btn>
      </p>
    </q-drawer>

    <q-page-container style="padding-top: 10px;">
      <router-view />
    </q-page-container>

    <div align="around" class="bg-grey-9 text-center text-white q-pa-md" >
      <h5 class="no-padding text-weight-bolder">{{entreprise.name.toUpperCase()}}</h5>
      <div class="q-pa-md q-gutter-lg">
        <q-btn color="secondary" icon="fab fa-facebook-f" />
        <q-btn color="secondary" icon="fab fa-instagram" />
        <q-btn color="secondary" icon="fab fa-twitter" />
      </div>
      Tel: {{entreprise.telephone}}<br>
      Email: {{entreprise.email}}
      <hr>
      <span class="no-padding no-margin text-weight-bolder">Copyright © All Right Reserved</span>
      <!--      </q-toolbar>-->
    </div>

  </q-layout>
</template>

<script>
import $httpService from '../boot/httpService';
export default {
    name: 'GoogleNewsLayout2',
    data () {
        return {
            right: false,
            leftDrawerOpen: false,
            // countCart: JSON.parse(localStorage.getItem('cart')).length || [],
            countCart: 0,
            search: '',
            telephone: '',
            name: '',
            entreprise: { name: '' },
            email: '',
            id: 1,
            byDate: 'Any time',
            links1: [
                { icon: 'home', text: 'Accueil', link: '/accueil' },
                { icon: 'shop', text: 'Boutique', link: '/boutique' },
                { icon: 'category', text: 'Categories', link: '/categories-liste' }
                // { icon: 'shopping_cart', text: 'Panier', link: '/boutique-panier' }
            ],
            links2: [
                // { icon: 'settings_applications', text: 'Parametres' },
                // { icon: 'near_me', text: 'Localisation' }
            ],
            links3: [
                // { icon: '', text: 'Language & region' },
                // { icon: '', text: 'Settings' }
            ],
            products: []
        }
    },
    created() {
        this.products = JSON.parse(localStorage.getItem('cart'));
        if (this.products !== null) {
            this.countCart = this.products.length;
        }
        this.shop_get();
    },
    methods: {
        show_cart() {
            this.right = !this.right;
            this.products = JSON.parse(localStorage.getItem('cart'));
            this.countCart = this.products.length
        },
        delete_cart(index) {
            // this.products = JSON.parse(localStorage.getItem('cart'));
            this.products.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(this.products));
            this.countCart = this.products.length
        },
        command_cart() {
            $httpService.postWithParams('/api/post/user_command', { name: this.name, telephone: this.telephone, email: this.email, products: this.products })
                .then((response) => {
                    this.$q.notify({ message: response['msg'], color: 'secondary', position: 'top' });
                })
        },
        shop_get() {
            $httpService.postWithParams('/my/get/shop')
                .then((response) => {
                    this.entreprise = response;
                })
        }
    }
}
</script>

<style>
  q-app{
    background-color: #e0e0e0 !important;
  }
  .GNL__toolbar{
    height: 32px
  }
</style>
