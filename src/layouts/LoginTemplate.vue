<template>
  <q-layout view="hHh lpR fFf">

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
export default {
    name: 'GoogleNewsLayout',
    data () {
        return {
            leftDrawerOpen: false,
            search: '',
            showAdvanced: false,
            showDateOptions: false,
            exactPhrase: '',
            hasWords: '',
            excludeWords: '',
            byWebsite: '',
            byDate: 'Any time',
            links1: [
                { icon: 'web', text: 'Dashboard', link: '/' },
                // { icon: 'money_off', text: 'Achats', link: '/achats' },
                { icon: 'money_off', text: 'Achats', link: '/commandes' },
                // { icon: 'attach_money', text: 'Ventes', link: '/ventes' },
                { icon: 'attach_money', text: 'Ventes', link: '/ventes_new' },
                // { icon: 'star_border', text: 'Depenses', link: '/depenses' },
                { icon: 'money', text: 'Factures', link: '/factures' },
                { icon: 'shopping_cart', text: 'Produits', link: '/produits' },
                { icon: 'category', text: 'Categories', link: '/categories' },
                { icon: 'people', text: 'Users', link: '/users' },
                { icon: 'apps', text: 'App', link: '/app' },
                { icon: 'shopping_cart', text: 'Boutique', link: '/boutique' }
            ],
            links2: [
                { icon: 'settings_applications', text: 'Parametres' },
                { icon: 'language', text: 'Language & region' }
                // { icon: 'near_me', text: 'Localisation' }
            ],
            links3: [
                // { icon: '', text: 'Settings' }
            ]
        }
    },
    created () {
        localStorage.clear();
    },
    methods: {
        onClear () {
            this.exactPhrase = ''
            this.hasWords = ''
            this.excludeWords = ''
            this.byWebsite = ''
            this.byDate = 'Any time'
        },
        changeDate (option) {
            this.byDate = option
            this.showDateOptions = false
        },
        logout() {
            localStorage.removeItem('current_user');
            localStorage.removeItem('token');
            localStorage.removeItem('token2');
            window.location.replace('/');
            // this.$q.router.forward('/');
        }
    }
}
</script>

<style>
  .GNL__toolbar{
    height: 64px
  }

  .GNL__toolbar-input{
    width: 55%
  }

  .GNL__drawer-item{
    line-height: 24px;
    border-radius: 0 24px 24px 0;
    margin-right: 12px;

  }

  q-item-section--avatar {
    color: #5f6368;
  }

  q-icon{
    color: #5f6368;
  }
  /*
  li a {
    text-decoration: none;
    color: #5f6368;
  }
  a {
    text-decoration: none;
    color: #5f6368;
  }*/
  q-item-label{
    color: #3c4043;
    letter-spacing: .01785714em;
    font-size: .875rem;
    font-weight: 500;
    line-height: 1.25rem
  }
/*
  router-link{
    text-decoration: none;
  }
*/
  .GNL__drawer-footer-link{
    color: inherit;
    text-decoration: none;
    font-weight: 500;
    font-size: .75rem;
  }

  .GNL__drawer-footer-link:hover{
    color: #000;
  }

</style>
