<template>
  <div class="no-padding">


    <div class="row q-mb-lg">
      <div class="col-12">
        <form enctype="multipart/form-data">
          <q-input v-model="titre" label="titre (optionnel)" />
          <br>
          <input type="file" name="image" v-on:change="handleInput" />
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col" v-for="item in photos" :key="item.id">
        <img :src="baseurl+item.url" :alt="baseurl+item.name"
             width="200" height="200" style="object-fit: cover" />
<!--        {{item.url}}-->
        <br>
        <qtn class="q-pa-xs bg-red" type="button" @click="photos_delete(item.id)">X</qtn>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import basemixin from "pages/basemixin";
import {LocalStorage} from "quasar";

export default {
  name: 'photoscomponent',
  data: function () {
    return {
      titre: '',
      photo: {},
      photos: [],
    }
  },
  components: {
    // 'croppa': Croppa.component
  },
  props: {
    type: String,
    typeid: Number,
    folder: String
  },
  watch: {
    typeid: {
      immediate: true,
      handler () {
        this.photos_get()
      }
    }
  },
  mixins: [basemixin],
  created: function () {
    this.photos_get();
    // this.medias_type_get()
  },
  model: {
    event: 'blur'
  },
  methods: {
    handleInput ($event) {
      let formData = new FormData()
      formData.append('file', $event.target.files[0])
      formData.append('type', this.type)
      formData.append('typeid', this.typeid)
      formData.append('folder', this.folder)
      formData.append('titre', this.titre)
      axios.post(this.apiurl+'/my/post/photos', formData,
        {
          headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
        }
      ).then((data)=> {
        console.log(data)
        this.photos_get();
      })
      this.$emit('blur', {
        image: this.image,
        image_name: 'images',
        image_extension: this.image_extension,
        type: this.type
      })
    },
    photos_get() {
      axios.get(this.apiurl+'/my/get/photos/'+this.typeid,{
        headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
      }).then((data)=> {
        this.photos = data['data'];
      })
    },
    photos_delete(_id) {
      axios.get(this.apiurl+'/my/delete/photos/'+_id,{
        headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
      }).then((data)=> {
        this.photos_get();
      })
    }
  }
}
</script>

<style>
.croppa-container {
  background-color: #f0f0f0;
  border: 2px solid black;
  border-radius: 3px;
  width: 100%;
  /*max-width: 600px;*/
  /*height: auto;*/
}
/*canvas {*/
/*  width: 100%;*/
/*  max-width: 600px;*/
/*  height: auto;*/
/*  background-color: white;*/
/*}*/

</style>
