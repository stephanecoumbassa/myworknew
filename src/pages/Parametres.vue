<template>
  <q-page padding>

    <div class="row justify-center">

      <div class="col-md-10 col-sm-12">
        <q-card flat>
          <q-tabs
            v-model="tab" dense class="text-grey" active-color="secondary"
            indicator-color="secondary" align="justify" narrow-indicator
          >
            <q-tab name="mails" label="Generale" />
            <q-tab name="alarms" label="Logo & Photos" />
            <q-tab name="home" label="Texte Accueil" />
            <q-tab name="movies" label="Factures" />
          </q-tabs>

          <q-separator />

          <q-tab-panels v-model="tab" animated>

            <q-tab-panel name="mails">
              <div class="text-h6">Generale</div>

              <div class="col-md-8 col-sm-12 col-xs-12 q-pa-md">
                <h6 class="q-mb-sm q-mt-sm">Parametres</h6>

                <q-input v-model="entreprise.name" icon="event" padding type="text" label="Nom">
                  <template #prepend> <q-icon name="store" /> </template>
                </q-input>
                <q-input v-model="entreprise.slogan" padding type="text" label="Slogan">
                  <template #prepend> <q-icon name="format_quote" /> </template>
                </q-input>
                <q-input v-model="entreprise.telephone" padding type="text" label="telephone">
                  <template #prepend> <q-icon name="call" /> </template>
                </q-input>
                <q-input v-model="entreprise.email" padding type="text" label="email">
                  <template #prepend> <q-icon name="location_on" /> </template>
                </q-input>
                <q-input v-model="entreprise.site" padding type="text" label="Site Web">
                  <template #prepend> <q-icon name="language" /> </template>
                </q-input>
                <q-input v-model="entreprise.facebook" padding type="text" label="facebook">
                  <template #prepend> <q-icon name="facebook" /> </template>
                </q-input>
                <q-input v-model="entreprise.youtube" padding type="text" label="youtube">
                  <template #prepend> <q-icon name="youtube" /> </template>
                </q-input>
                <q-input v-model="entreprise.adresse" outlined padding type="textarea" label="Adresse">
                  <template #prepend> <q-icon name="home" /> </template>
                </q-input>
                <!--        {{entreprise.city}}-->
                <q-select
                  v-model="entreprise.city" :options="cities" label="Ville" map-options emit-value use-input
                  option-value="id" stack-label input-debounce="0" option-label="name" @filter="filterFn">
                  <template #prepend> <q-icon name="location_on" /> </template>
                </q-select>
                <q-input v-model="entreprise.quartier" icon="event" padding type="text" label="Quartier">
                  <template #prepend> <q-icon name="location_on" /> </template>
                </q-input>
                <br>
                <!-- option-value="id" stack-label input-debounce="0" option-label="name" @filter="filterFn" />-->
                <div style="height: 400px">
                  {{entreprise.latlong}}
                  <my-map-component v-model="entreprise.latlong" @blur="setLatLong" />
                </div>
                <!-- <q-input padding type="text" v-model="entreprise.latitude" label="Latitude" />-->
                <!-- <q-input padding type="text" v-model="entreprise.longitude" label="Longitude" />-->
                <q-input v-model="entreprise.tva" padding type="number" label="TVA" class="q-mt-md" />
                <br>
                <!--                <q-select padding v-model="entreprise.theme" :options="[1, 2, 3]" label="Theme" /><br>-->
                <label>Couleur primaire du site</label><br>
                <input v-model="entreprise.color" type="color" /><br><br>
                <!--                <my-map2-component></my-map2-component>-->
                <div class="q-gutter-sm">
                  <q-icon color="red" name="money_off" size="md" />
                  <q-radio v-model="entreprise.showprice" color="red" :val="0" label="Ne pas Afficher le prix" />
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <q-icon color="green" name="paid" size="md" />
                  <q-radio v-model="entreprise.showprice" color="green" :val="1" label="Afficher le prix" />
                  <!--                    <q-radio v-model="entreprise.contact" :val="true" label="Rectangle" />-->
                </div>
                <br>
                <br>
                <q-input v-model="entreprise.passwordupdate" type="password" label="Mot de passe de modification" /><br><br>

                <q-btn icon="update" color="secondary" class="q-mt-md" size="md" label="Mettre à Jour" @click="onSubmit" /><br>
                <br>

              </div>

            </q-tab-panel>

            <q-tab-panel name="alarms">
              <div class="text-h6">Photos</div>

              <div class="col-md-8 col-sm-12 col-xs-12 q-pa-sm">
                <label>Logo</label><br>
                <image-component
                  v-model="entreprise.logo" :src="uploadurl +'/'+ entreprise.id +'/magasin/'+entreprise.logo" :type_id="2" image_extension="jpg"
                  images :width="150" :height="150" :quality="1" o-padding class="no-padding" /><br>
                <q-btn color="secondary" class="no-padding" size="sm" label="uploader" @click="upload()" />
              </div>
              <div class="col-md-8 col-sm-12 col-xs-12 q-pa-sm">
                <label>Slider 1</label><br>
                <image-component
                  v-model="entreprise.slider1" :src="uploadurl +'/'+ entreprise.id +'/magasin/'+entreprise.slider1"
                  :width="300" :height="150" /><br>
                <q-btn color="secondary" label="uploader" size="sm" @click="upload_slider1()" />
              </div>
              <div class="col-md-8 col-sm-12 col-xs-12 q-pa-sm">
                <label>Slider 2</label><br>
                <image-component
                  v-model="entreprise.slider2" :src="uploadurl +'/'+ entreprise.id +'/magasin/'+entreprise.slider2"
                  :width="300" :height="150" /><br>
                <q-btn color="secondary" label="uploader" size="sm" @click="upload_slider2()" />
              </div>
              <div class="col-md-8 col-sm-12 col-xs-12 q-pa-sm">
                <label>Slider 3</label><br>
                <image-component
                  v-model="entreprise.slider3" :src="uploadurl +'/'+ entreprise.id +'/magasin/'+entreprise.slider3"
                  :width="300" :height="150" /><br>
                <q-btn color="secondary" label="uploader" size="sm" @click="upload_slider3()" />
              </div>

            </q-tab-panel>

            <q-tab-panel name="home">
              <br><br>
              <div class="text-h6">Texte - Livraison</div>
              <q-input v-model="entreprise.home_livraison" outlined type="textarea" label="livraison" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Texte - Payer à Livraison</div>
              <q-input v-model="entreprise.home_payer" outlined type="textarea" label="Payer à livraison" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Texte - Retour Jour</div>
              <q-input v-model="entreprise.home_jour" outlined type="textarea" label="Retour" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Texte - Ouverture</div>
              <q-input v-model="entreprise.home_ouverture" outlined type="textarea" label="Ouverture" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Texte - Newsletter</div>
              <q-input v-model="entreprise.home_newletter" outlined type="textarea" label="Ouverture" :toolbar="toolbar" />
              <br>
              <br>
              <q-btn color="secondary" class="q-mt-md" size="md" label="Mettre à Jour" @click="onSubmit" /><br>

            </q-tab-panel>

            <q-tab-panel name="movies">
              <div class="text-h6">Conditions & Factures</div>

              <br><br>
              <!--              <q-editor v-model="product.description" min-height="5rem" :toolbar="toolbar" />-->
              <div class="text-h6">Factures</div>
              <q-editor v-model="entreprise.footer_facture" outlined padding type="textarea" label="Pied de page Facture" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Footer Site</div>
              <q-editor v-model="entreprise.footer_site" outlined padding type="textarea" label="Pied de page Site Web" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Livraison</div>
              <q-editor v-model="entreprise.footer_livraison" outlined padding type="textarea" label="Conditions de Livraison" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Conditions de paiement</div>
              <q-editor v-model="entreprise.footer_paiement" outlined padding type="textarea" label="Conditions de paiement" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Faq</div>
              <q-editor v-model="entreprise.footer_faq" outlined padding type="textarea" label="Faq" :toolbar="toolbar" />
              <br>
              <div class="text-h6">Contacts</div>
              <q-editor v-model="entreprise.contact" outlined padding type="textarea" label="Contacts" :toolbar="toolbar" />
              <br>
              <br>
              <q-btn color="secondary" class="q-mt-md" size="md" label="Mettre à Jour" @click="onSubmit" /><br>

            </q-tab-panel>

          </q-tab-panels>

        </q-card>
      </div>


    </div>

  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import ImageComponent from '../components/image-component.vue';
import MyMapComponent from '../components/mymap.vue';
import basemixin from './basemixin';
export default {
  name: 'ParametresName',
  components: {
    ImageComponent,
    MyMapComponent
  },
  data () {
    return {
      tab: 'mails',
      entreprise: { city: 1, footer_facture: 'agffggf', footer_site: '', footer_livraison: '', footer_paiement: '', footer_faq: '', contact: '', latlong: [0, 0] },
      cities: [],
      cities2: [],
      uploadurl: 'https://www.affairez.com/apistock/public/shop',
      toolbar: toolbar
    }
  },
  mixin: [basemixin],
  created() {
    this.get();
    this.cities_get();
  },
  methods: {
    onSubmit() {
      $httpService.putWithParams('/my/put/shop', this.entreprise)
        .then((response) => {
          this.get();
          this.$q.notify({ color: 'secondary', position: 'top-right', message: response.msg });
        })
    },
    get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
          this.entreprise.footer_facture = response.footer_facture == null ? '' : response.footer_facture;
          this.entreprise.footer_site = response.footer_site == null ? '' : response.footer_site;
          this.entreprise.footer_livraison = response.footer_livraison == null ? '' : response.footer_livraison;
          this.entreprise.footer_paiement = response.footer_paiement == null ? '' : response.footer_paiement;
          this.entreprise.footer_faq = response.footer_faq == null ? '' : response.footer_faq;
          this.entreprise.contact = response.contact == null ? '' : response.contact;
          // this.entreprise.latlong = [0, 0];
        })
    },
    cities_get() {
      $httpService.getWithParams('/api/get/cities_list')
        .then((response) => {
          this.cities = response;
          this.cities2 = response;
        })
    },
    upload() {
      $httpService.postWithParams('/my/put/shop_logo', this.entreprise)
        .then((response) => {
          this.$q.notify({ color: 'green', position: 'top-right', message: response.msg });
          this.get();
        })
    },
    upload_slider1() {
      $httpService.postWithParams('/my/put/shop_slider1', this.entreprise)
        .then((response) => {
          this.$q.notify({ color: 'secondary', position: 'top-right', message: response.msg });
          this.get();
        })
    },
    upload_slider2() {
      $httpService.postWithParams('/my/put/shop_slider2', this.entreprise)
        .then((response) => {
          this.$q.notify({ color: 'secondary', position: 'top-right', message: response.msg });
          this.get();
        })
    },
    upload_slider3() {
      $httpService.postWithParams('/my/put/shop_slider3', this.entreprise)
        .then((response) => {
          this.$q.notify({ color: 'secondary', position: 'top-right', message: response.msg });
          this.get();
        })
    },
    filterFn (val, update) {
      update(() => {
        const needle = val.toLocaleLowerCase();
        this.cities = this.cities2.filter(v => v.name.toLocaleLowerCase().indexOf(needle) > -1);
      })
    },
    setLatLong(latlong) {
      // console.log(latlong)
      this.entreprise.latitude = latlong[0];
      this.entreprise.longitude = latlong[1];
    }
  }
}
</script>
