<template>
  <div class="no-padding">


    <div class="row q-mb-lg">
      <div class="col-12">
        <form enctype="multipart/form-data">
          <q-input v-model="titre" label="titre (optionnel)" />
          <br>
          <input type="file" name="image" @change="handleInput" />
        </form>
      </div>
    </div>

    <div class="row">
      <div v-for="item in photos" :key="item.id" class="col">
        <img
:src="baseurl+item.url" :alt="baseurl+item.name"
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
  name: 'PhotosComponent',
  components: {
    // 'croppa': Croppa.component
  },
  mixins: [basemixin],
  model: {
    event: 'blur'
  },
  props: {
    type: {type: String, required: true},
    typeid: {type: Number, required: true},
    folder: {type: String, required: true}
  },
  data: function () {
    return {
      titre: '',
      photos: [],
    }
  },
  watch: {
    typeid: {
      immediate: true,
      handler () {
        this.photos_get()
      }
    }
  },
  emits:['blur'],
  created: function () {
    this.photos_get();
    // this.medias_type_get()
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
      }).then(()=> {
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
