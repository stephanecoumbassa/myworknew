
<template>
  <div>
    <div class="info" style="height: 15%">
      <!--      <span>Center: {{ center }}</span>-->
      <!--      <span>Zoom: {{ zoom }}</span>-->
      <!--      <span>Bounds: {{ bounds }}</span>-->
    </div>
    <l-map
      style="height: 400px; width: 100%" :zoom="zoom" :center="center"
      @update:zoom="zoomUpdated" @update:center="centerUpdated" @update:bounds="boundsUpdated">
      <l-tile-layer :url="url" />
      <l-marker v-model="latlong" :lat-lng="markerLatLng" :draggable="true" @update:lat-lng="drag" />
      <!--      <l-marker v-model="latlong" :lat-lng="markerLatLng" :draggable="true" @moveend="drag" />-->
    </l-map>
  </div>
</template>

<script>
// import { LMap, LTileLayer, LMarker } from 'vue3-leaflet';
import { LMap, LTileLayer, LMarker, LGeoJson } from "@vue-leaflet/vue-leaflet";
import { icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';
export default {
  name: 'MyMapComponent',
  components: {
    LMap,
    LTileLayer,
    LMarker
  },
  data () {
    return {
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      latlong: null,
      zoom: 13,
      center: [ 5.331390379294567, -4.022156233545052 ],
      markerLatLng: [ 5.331390379294567, -4.022156233545052 ],
      bounds: null,
      icon: icon({
        iconUrl: 'http://affairez.com/affairez/images/affairez.png',
        iconSize: [32, 37],
        iconAnchor: [16, 37]
      }),
      staticAnchor: [16, 37],
      customText: 'Foobar',
      iconSize: 64,
      longitude: null,
      latitude: null
    };
  },
  model: {
    event: 'blur'
  },
  computed: {
    dynamicSize() {
      return [this.iconSize, this.iconSize * 1.15];
    },
    dynamicAnchor() {
      return [this.iconSize / 2, this.iconSize * 1.15];
    }
  },
  methods: {
    handleInput () {
      // console.log(this.latitude, this.longitude)
      this.$emit('blur', [this.latitude, this.longitude]);
      this.$emit('getlatlng', [this.latitude, this.longitude]);
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
    drag(event) {
      const LatLng = JSON.parse(JSON.stringify(event))
      this.latitude = LatLng.lat;
      this.longitude = LatLng.lng;
      // console.log(LatLng)
      setTimeout(() => {
        this.handleInput();
      }, 500)
    }

  }
}

//
// delete L.Icon.Default.prototype._getIconUrl;
// L.Icon.Default.mergeOptions({
//     iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
//     iconUrl: require('leaflet/dist/images/marker-icon.png'),
//     shadowUrl: require('leaflet/dist/images/marker-shadow.png')
// });

</script>
