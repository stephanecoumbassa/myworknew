// définir un objet mixin
import { LocalStorage } from 'quasar';
import storeGlobal from '../stores/storeController'
import $httpService from '../boot/httpService';
import {useCounterStore} from "stores/baseState";
import {Loading} from 'quasar';

var basemixin = {
  data () {
    return {
      store: {},
      role: '0',
      first: null,
      last: null,
      loading: false,
      state: storeGlobal.state,
      baseurl: 'https://affairez.com',
      apiurl: 'https://affairez.com/apistock',
      uploadurl: 'https://affairez.com/apistock/public/shop',
      upload_url: 'https://affairez.com/apistock/public/shop',
      ecommerceurl: 'https://affairez.com/vuecommerce',
      fonts: {
        arial: 'Arial',
        arial_black: 'Arial Black',
        comic_sans: 'Comic Sans MS',
        courier_new: 'Courier New',
        impact: 'Impact',
        lucida_grande: 'Lucida Grande',
        times_new_roman: 'Times New Roman',
        verdana: 'Verdana'
      },
      entreprise: { name: '', city: 1, footer_facture: 'agffggf', footer_site: '', footer_livraison: '', footer_paiement: '', footer_faq: '', contact: '' },
      toolbar: [
        [
          { label: this.$q.lang.editor.align, icon: this.$q.iconSet.editor.align, fixedLabel: true, list: 'only-icons', options: ['left', 'center', 'right', 'justify'] },
        ],
        ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
        ['token', 'hr', 'link', 'custom_btn'],
        ['print', 'fullscreen'],
        ['insert_img', 'color'],
        [
          {
            icon: this.$q.iconSet.editor.formatting,
            list: 'no-icons',
            options: ['p','h1','h2','h3','h4','h5','h6','code']
          },
          {
            icon: this.$q.iconSet.editor.fontSize,
            fixedLabel: true,
            fixedIcon: true,
            list: 'no-icons',
            options: ['size-1','size-2','size-3','size-4','size-5','size-6', 'size-7']
          },
          {
            fixedIcon: true,
            list: 'no-icons',
            options: ['default_font','arial','arial_black','comic_sans','courier_new','impact','lucida_grande','times_new_roman','verdana']
          },
          'removeFormat'
        ],
        ['quote', 'unordered', 'ordered', 'outdent', 'indent'],

        ['undo', 'redo'],
        ['viewsource']
      ]
    }
  },
  mounted() {
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    this.store = useCounterStore();
  },
  created: function () {
    var date = new Date();
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    if (LocalStorage.getItem('current_user')) {
      this.role = LocalStorage.getItem('current_user')['roles'][0];
      this.state.current_user = LocalStorage.getItem('current_user');
      this.state.token = LocalStorage.getItem('token');
      this.state.token2 = LocalStorage.getItem('token2');
    }
    if (LocalStorage.getItem('shop') == 'undefined' || LocalStorage.getItem('shop') == null) {
      this.shop_get();
    } else {
      this.state.entreprise = this.$q.localStorage.getItem('shop');
      this.entreprise = this.$q.localStorage.getItem('shop');
    }
  },
  methods: {
    currentUser() {
      return this.$q.localStorage.getItem('current_user')
    },
    navigate(url) {
      this.$router.push(url)
    },
    convert(str) {
      var date = new Date(str),
        mnth = ('0' + (date.getMonth() + 1)).slice(-2),
        day = ('0' + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join('-');
    },
    numerique(value) {
      return String(value).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
          this.state.entreprise = response;
          this.$q.localStorage.set('shop', response);
        })
    },
    capitalize(value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    },
    lower(value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toLowerCase() + value.slice(1)
    },
    formatDate(date) {
      var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
      if (month.length < 2) {
        month = '0' + month;
      }
      if (day.length < 2) {
        day = '0' + day;
      }
      return [year, month, day].join('-');
    },
    dateformat(date, type = 1) {
      date = new Date(date);
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const options2 = { year: 'numeric', month: 'long', day: 'numeric' };
      // const options3 = { weekday: 'numeric', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minutes: 'numeric' };
      let year = date.getFullYear();
      let month = (1 + date.getMonth()).toString().padStart(2, '0');
      let day = date.getDate().toString().padStart(2, '0');
      let heures = date.getHours().toString().padStart(2, '0');
      let minutes = date.getMinutes().toString().padStart(2, '0');
      if (type === 1) { return day + '-' + month + '-' + year; }
      if (type === 2) { return date.toLocaleDateString('fr-FR', options); }
      if (type === 3) { return day + '-' + month + '-' + year + ' ' + heures + ':' + minutes; }
      if (type === 4) { return date.toLocaleDateString('fr-FR', options2); }
    },
    get_dateposted() {
      let date = new Date();
      date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
      return date.toISOString().slice(0, 16);
    },
    array_somme(array, key) {
      var somme = 0;
      for (let i = 0; i < array.length; i++) {
        somme = somme + parseInt(array[i][key]);
      }
      return parseInt(somme);
    },
    slugify(text) {
      if (text !== null) {
        return text.toString().toLowerCase()
          .replace(/\s+/g, '-')
          // eslint-disable-next-line no-useless-escape
          .replace(/[^\w\-]+/g, '')
          // eslint-disable-next-line no-useless-escape
          .replace(/\-\-+/g, '-')
          .replace(/^-+/, '')
          .replace(/-+$/, '');
      }
      return '';
    },
    notifyresponse(response) {
      if (response.status == !0) {
        this.$q.notify({
          color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
        });
      } else {
        this.$q.notify({
          color: 'warning', position: 'top', message: response.msg, icon: 'report_problem'
        });
      }
    },
    role_vendor() {
      if(LocalStorage.getItem('current_user')) {
        let role = parseInt(LocalStorage.getItem('current_user')['roles'][0]);
        return role === 1 || role === 3 || role === 5;
      }
    },
    role_achat() {
      if(LocalStorage.getItem('current_user')) {
        let role = parseInt(LocalStorage.getItem('current_user')['roles'][0]);
        return role === 1 || role === 4 || role === 5;
      }
    },
    role_manager() {
      if(LocalStorage.getItem('current_user')) {
        let role = parseInt(LocalStorage.getItem('current_user')['roles'][0]);
        if (role === 1 || role === 5) {
          return true;
        }
      }
    },
    role_admin() {
      if(LocalStorage.getItem('current_user')) {
        let role = parseInt(LocalStorage.getItem('current_user')['roles'][0]);
        return role === 1;
      }
    },
    showLoading() {
      Loading.show();
    },
    hideLoading() {
      Loading.hide();
    },
    showAlert(response, aze='') {
      let msg = '';
      response.status === 1 ?
        msg = { color: 'green', position: 'top', message: response.msg, icon: 'report_problem' }
        : msg = { color: 'warning', position: 'top', message: response.msg, icon: 'report_problem' };
      this.$q.notify(msg);
    },
  },
};
export default basemixin;
