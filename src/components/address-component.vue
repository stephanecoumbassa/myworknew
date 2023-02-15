<template>
  <div>
      <form @submit.prevent="address_update(address)">
        <div class="row">
<!--          <div class="col-6">-->
<!--            <div class="row">-->
<!--              <div class="col-12">-->
<!--                  <select class="form-control search-slt" v-model="address.country_id" @input="$emit('blur', addresses)">-->
<!--                    <option value="1">RCI</option>-->
<!--                  </select>-->
<!--                  <select required class="form-control search-slt" v-model="address.city_id">-->
<!--                    <option v-for="item in cities_list" :value="item.id" :key="item.id"> {{item.name}} </option>-->
<!--                  </select>-->
<!--                  <select class="form-control search-slt" v-model="address.quartier_id">-->
<!--                    <option v-for="q in quartiers_list" :value="q.id" :key="q.id">{{q.name}}</option>-->
<!--                  </select>-->
<!--                  <q-input class="padding" type="text" v-model="address.longitude" />-->
<!--                  <q-input class="padding" type="text" v-model="address.latitude" />-->
<!--                  <q-input type="textarea" class="padding" v-model="address.description"></q-input>-->
<!--                  <button type="button" class="btn btn-secondary btn-sm" v-on:click="address_delete(address)">Supprimer</button>-->
<!--                  <button type="submit" class="btn btn-primary btn-sm">modifier</button>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
          <div class="col-6">
            <q-btn label="voir" v-on:click="status = !status"></q-btn>
            <div style="height: 400px; width: 100%">
              <div class="info" style="height: 15%">
                <span>latLong: {{ address.latitude }} - {{ address.longitude }}</span><br>
              </div>
              <div id="map" @shown="reloadMap()" v-if="status">
                  <l-map ref="map" style="height: 400px; width: 400px" :zoom="zoom" :center="center"
                      @update:zoom="zoomUpdated" @update:center="centerUpdated" @update:bounds="boundsUpdated">
                  <l-tile-layer :url="url"></l-tile-layer>
                  <l-marker :lat-lng="[parseFloat(address.latitude), parseFloat(address.longitude)]" :draggable="true"
                            v-on:dragend="realMarkerAdd" :update="[parseFloat(address.latitude), parseFloat(address.longitude)]"
                            :ready="realMarkerAdd"></l-marker>
                </l-map>
              </div>
            </div>
          </div>
        </div>
      </form>
  </div>
</template>

<script>
import * as api from '../assets/api_axios';
import { LMap, LTileLayer, LMarker, LIcon } from 'vue2-leaflet';
export default {
    name: 'AddressComponent',
    data: function() {
        return {
            count: 0,
            address: { country_id: null, city_id: null, quartier_id: null, longlat: [5.3705945, -3.9776399], description: null, latitude: -3.9776399, longitude: 5.3705945 },
            addresses: [{ 'country_id': null, 'city_id': null, 'quartier_id': null, 'longlat': null, 'description': null }],
            status: false,
            country: '',
            city: '',
            cities_list: '',
            city_initiales: '',
            quartier: '',
            quartiers_list: '',
            quartier_initiales: '',
            addresses_list: '',
            url: 'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
            zoom: 10,
            center: [5.331869883252284, -4.00438422611056],
            bounds: null,
            markerLatLng: [5.331869883252284, -4.00438422611056]
        }
    },
    props: {
        idligne: Number,
        typerubrique: Number
    },
    components: {
        'l-map': LMap, 'l-tile-layer': LTileLayer, 'l-marker': LMarker
    },
    created: function () {
        // this.cities_get();
        // this.quartiers_get();
    },
    model: {
        event: 'blur'
    },
    mounted() {
        setTimeout(function() { window.dispatchEvent(new Event('resize')) }, 250);
    },
    watch: {
        idligne: {
            immediate: true,
            handler (val, oldVal) {
                // this.addresses_get();
            }
        }
    },
    methods: {
        reloadMap: function () {
            this.$refs.map.mapObject.invalidateSize()
        },
        realMarkerAdd(e) {
            // const i = this.addresses.length - 1;
            this.address.latitude = e.target._latlng.lat;
            this.address.longitude = e.target._latlng.lng;
        },
        openPopup: function (event) {
            // console.log(event.target);
            event.target.openPopup();
        },
        zoomUpdated (zoom) {
            this.zoom = zoom;
        },
        centerUpdated (center) {
            this.center = center;
        },
        boundsUpdated (bounds) {
            this.bounds = bounds;
        },
        getLongLat() {
            // console.log(this.markerLatLng)
        },
        addresses_get() {
            api.getWithParams('/api/get/addresses', { idligne: this.idligne, typerubrique: this.typerubrique }).then((data) => {
                this.addresses_list = JSON.parse(data.addresses);
            })
        },
        handleInput (value) {
            this.$emit('blur', value)
        },
        categoriesfilter() {
            this.categories_list = this.categories_initiales;
            let cats = this.categories_list.filter((cat) => {
                return cat.categories_master_id === String(this.categories);
            });
            this.categories_list = cats;
        },
        cities_get() {
            api.getWithParams('/api/get/cities_list', { id: this.country_id }).then(data => {
                const res = JSON.stringify(data);
                this.cities_list = JSON.parse(res);
                this.city_initiales = this.cities_list;
            });
        },
        quartiers_get() {
            api.getWithParams('/api/get/quartiers', { id: this.city_id }).then(data => {
                const res = JSON.stringify(data);
                this.quartiers_list = JSON.parse(res);
                this.quartier_initiales = this.quartiers_list;
            });
        },
        address_update(address) {
            if (confirm('Voulez vous modifier ?')) {
                this.$validator.validateAll().then((success) => {
                    if (success) {
                        // console.log(address);
                        api.putWithParams('/api/put/addresses', address).then(() => {
                            // const a = data;
                        });
                    }
                });
            }
        },
        address_delete(address) {
            if (confirm('Voulez vous supprimer ?')) {
                api.deleteWithParams('/api/delete/addresses', { data: { id: address.id } }).then(() => {
                    // const a = data;
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
