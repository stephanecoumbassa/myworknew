<template>
  <q-layout view="lHh lpR fFf" class="bg-grey-3">
    <q-header elevated class="text-grey-8 print-hide" height-hint="64">
      <q-toolbar class="GNL__toolbar bg-grey-3 text-white print-hide">
        <q-btn flat dense round color="secondary" @click="leftDrawerOpen = !leftDrawerOpen"
               aria-label="Menu" icon="menu" class="q-mr-sm" />

        <q-toolbar-title v-if="$q.screen.gt.xs" shrink class="row items-center no-wrap print-hide">
          &nbsp;&nbsp;&nbsp;
          <q-btn size="xs" color="grey-6" icon="arrow_back" v-on:click="go_back()" />&nbsp;&nbsp;
          <q-btn size="xs" color="grey-6" icon="arrow_forward" v-on:click="go_foward()" />
        </q-toolbar-title>

        <q-space />

        <div class="q-gutter-sm row items-center no-wrap">
          <q-btn round dense flat color="teal" icon="notifications" v-on:click="showNotif('Activation des notifications')">
            <q-badge color="red" text-color="white" floating>
              ON/OFF
            </q-badge>
            <q-tooltip>Activation des notifications ( {{status_permission}} )</q-tooltip>
          </q-btn>
          <!--          <q-btn round flat color="white">-->
          <!--            <q-avatar color="white" size="26px">-->
          <!--              <img src="https://cdn.quasar.dev/img/boy-avatar.png">-->
          <!--            </q-avatar>-->
          <!--            <q-tooltip>Account</q-tooltip>-->
          <!--          </q-btn>-->
          <q-btn round dense flat color="red" icon="logout" v-on:click="logout()">
            <q-tooltip>Deconnexion</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above side="left" bordered style="background-color: white"
              content-class="bg-dark-separator text-dark" :width="225" class="print-hide">
      <q-scroll-area class="fit print-hide">
        <q-list padding class="text-grey-10 print-hide" bordered separator>
          <div class="text-center center">
            <img class="animate__animated animate__pulse animate__infinite animate__slower"
                 src="~assets/fmmi.jpeg" style="height: 120px; width: 120px;">
            <!--                 src="~assets/affairez.png" style="height: 60px; width: 120px;">-->
          </div>
          <br>
          <br>
          <div v-for="link in links1" :key="link.text">
            <q-item class="GNL__drawer-item" v-ripple active-class="text-secondary" v-if="link.role"
                    :key="link.text" clickable :to="link.link">
              <q-item-section avatar> <q-icon :name="link.icon" :style="link.style" /> </q-item-section>
              <q-item-section> <q-item-label>{{ link.text }}</q-item-label> </q-item-section>
            </q-item>
          </div>
          <div>
            <q-item v-if="role == 1" class="GNL__drawer-item" clickable :to="'/commandes-clients'" active-class="text-teal">
              <q-item-section avatar> <q-icon name="pie_chart" />  </q-item-section>
              <q-item-section>
                <q-item-label>Commande Ecommerce
                  <q-badge class="animate__animated animate__pulse animate__infinite animate__slower" color="red">{{count}}</q-badge>
                </q-item-label>
              </q-item-section>
            </q-item>
          </div>

          <q-separator inset class="q-my-sm" />



          <q-expansion-item
            class="GNL__drawer-item"
            expand-separator
            icon="diversity_2"
            label="Projet"
            caption="RH"
          >
            <q-list class="q-pl-sm">
              <q-item v-if="role == 1" class="GNL__drawer-item" clickable :to="'/projet'" active-class="text-teal">
                <q-item-section avatar> <q-icon name="diversity_2" /> </q-item-section>
                <q-item-section>
                  <q-item-label>Projet</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="role == 1" class="GNL__drawer-item" clickable :to="'/employe'" active-class="text-teal">
                <q-item-section avatar> <q-icon name="engineering" /> </q-item-section>
                <q-item-section>
                  <q-item-label>Employ??</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="role == 1" class="GNL__drawer-item" clickable :to="'/evenement'" active-class="text-teal">
                <q-item-section avatar> <q-icon name="event_note" /> </q-item-section>
                <q-item-section>
                  <q-item-label>Ev??nement</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
            <!--            <q-card>-->
            <!--              <q-card-section>-->
            <!--                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti-->
            <!--                commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste-->
            <!--                eveniet doloribus ullam aliquid.-->
            <!--              </q-card-section>-->
            <!--            </q-card>-->
          </q-expansion-item>

          <q-item v-if="role == 1" class="GNL__drawer-item" clickable :to="'/users'" active-class="text-teal">
            <q-item-section avatar> <q-icon name="people" /> </q-item-section>
            <q-item-section>
              <q-item-label>Utilisateurs</q-item-label>
            </q-item-section>
          </q-item>

          <q-item class="GNL__drawer-item" clickable active-class="text-teal">
            <q-item-section avatar>
              <q-icon name="web" />
            </q-item-section>
            <q-item-section>
              <a class="text-secondary" :href="baseurl+'/boutique-'+shopid+'-'+slugify(entreprise.name)" target="_blank">Site Web</a>
            </q-item-section>
          </q-item>

        </q-list>
      </q-scroll-area>
    </q-drawer>

    <q-page-container class="bg-grey-3" style="max-width: 1650px">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { LocalStorage } from 'quasar'
import basemixin from '../pages/basemixin';
import $httpService from '../boot/httpService';
import { useCounterStore } from 'stores/baseState';

export default {
  name: 'GoogleNewsLayout',
  data () {
    return {
      store: {},
      leftDrawerOpen: false,
      search: '',
      showAdvanced: false,
      showDateOptions: false,
      exactPhrase: '',
      count: '',
      hasWords: '',
      excludeWords: '',
      byWebsite: '',
      byDate: 'Any time',
      role: {},
      shopid: null,
      status_permission: '',
      entreprise: { name: '', city: 1, footer_facture: 'agffggf', footer_site: '', footer_livraison: '', footer_paiement: '', footer_faq: '', contact: '' },
      links1: [
        { icon: 'web', text: 'Dashboard', link: '/board', role: this.role_admin() },
        { icon: 'attach_money', text: 'Ventes', link: '/ventes', role: this.role_vendor() },
        { icon: 'money_off', text: 'Achats', link: '/achats', role: this.role_achat() },
        { icon: 'money_off', text: 'Devis', link: '/devis', role: this.role_achat() },
        { icon: 'shopping_cart', text: 'Produits', link: '/produits', role: true, style: 'border: 1px solid orange; border-radius: 45%' },
        { icon: 'shopping_cart', text: 'Budget Revenu', link: '/budget-revenu', role: true, style: 'border: 1px solid orange; border-radius: 45%' },
        { icon: 'shopping_cart', text: 'Budget D??pense', link: '/budget-depense', role: true, style: 'border: 1px solid orange; border-radius: 45%' },
        // { icon: 'published_with_changes', text: 'Location', link: '/location', role: this.role_vendor() },
        // { icon: 'shopping_cart', text: 'Produits ?? louer', link: '/produits-location', role: true, style: 'border: 1px solid orange; border-radius: 45%' },
        { icon: 'local_atm', text: 'Prestations', link: '/prestations', role: this.role_vendor() },
        { icon: 'money_off', text: 'Depense', link: '/depenses', role: this.role_achat() },
        { icon: 'room_service', text: 'Services', link: '/services', role: true, style: 'border: 2px solid orange; border-radius: 45%' },
        // { icon: 'remove_shopping_cart', text: 'Pertes', link: '/pertes', role: true },
        { icon: 'category', text: 'Categories', link: '/categories', role: true },
        { icon: 'how_to_reg', text: 'Clients', link: '/clients', role: true },
        { icon: 'transfer_within_a_station', text: 'Fournisseurs', link: '/fournisseurs', role: true },
        // { icon: 'pie_chart', text: 'Commandes Ecommerce', link: '/commandes-clients', role: true },
        // { icon: 'score', text: 'Inventaire', link: '/produits/inventaire', role: this.role_manager() },
        { icon: 'settings_applications', text: 'Parametres', link: '/parametres', role: this.role_manager() }
      ],
      links2: [
        // { icon: 'settings_applications', text: 'Parametres' }
        // { icon: 'language', text: 'Language & region' }
        // { icon: 'near_me', text: 'Localisation' }
      ],
      links3: [
        // { icon: '', text: 'Settings' }
      ]
    }
  },
  created () {
    this.role = LocalStorage.getItem('current_user').roles[0];
    this.shopid = LocalStorage.getItem('current_user').shop_id;
    this.$q.loadingBar.setDefaults({ color: 'orange-4', size: '7px', position: 'top' });
    // this.command_count();
    // setInterval(() => {
    //   this.command_count();
    // }, 180000);
  },
  mixins: [basemixin],
  methods: {
    onClear () {
      this.exactPhrase = '';
      this.hasWords = '';
      this.excludeWords = '';
      this.byWebsite = '';
      this.byDate = 'Any time'
    },
    showNotif(msg = 'Vous avez une nouvelle commande !') {
      this.status_permission = Notification.permission;
      setTimeout(() => {}, 2000)
      if (Notification.permission === 'granted') {
        const audio = new Audio('../statics/discord.mp3')
        audio.play();
      } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then((permission) => {
          if (permission === 'granted') {
            this.status_permission = 'granted';
          }
        })
      } else if (Notification.permission === 'denied') {
        alert('Permission refus??e, veuillez activer les notifications SVP !');
        return false;
      }
      const notification = new Notification('Nouvelle Commande', {
        body: msg,
        icon: 'statics/affairez.png',
        sound: 'statics/discord.mp3'
      });
      notification.onclick = () => {
        window.location.href = window.location.origin + '/#/commandes-clients'
      };
    },
    changeDate (option) {
      this.byDate = option
      this.showDateOptions = false
    },
    go_back() {
      this.$router.go(-1);
    },
    go_foward() {
      this.$router.go(1);
    },
    logout() {
      localStorage.removeItem('current_user');
      localStorage.removeItem('current_user2');
      localStorage.removeItem('token');
      localStorage.removeItem('token2');
      localStorage.removeItem('token3');
      localStorage.removeItem('shop');
      // localStorage.removeItem('command_count');
      localStorage.clear();
      this.$q.cookies.remove('current_user');
      this.$q.cookies.remove('token');
      this.$q.cookies.remove('token2');
      this.$q.cookies.remove('shop');
      this.$router.push({ path: '/login' });
      // window.location.replace('/');
      // this.$q.router.forward('/');
    },
    command_count () {
      let commandCount = localStorage.getItem('command_count');
      $httpService.getWithParams('/my/count/user_command')
        .then((response) => {
          this.count = response['count'];
          if (parseInt(commandCount) < parseInt(this.count)) {
            this.showNotif();
          }
          localStorage.setItem('command_count', this.count);
        })
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

@media screen {
  .print-only {
    display: none !important; } }

@media print {
  .print-hide {
    display: none !important; } }

</style>
