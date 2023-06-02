<template>
  <div class="no-padding">


    <div class="row q-mb-lg">
      <div class="col-12">
        <form enctype="multipart/form-data" @submit.prevent="post()">
          <q-input v-model="titre" label="titre (optionnel)" />
          <br>
          <input type="file" name="image" @change="handleInput" />

          <cropper
            class="cropper"
            :src="previewImage"
            :stencil-props="{
              aspectRatio: 10/12
            }"
            :canvas="true"
            @change="change"
          />
          <br />
          <button type="submit" style="padding: 5px 10px 5px 10px; color: black">
            <q-icon name="image" />
            Publier
          </button>

        </form>
      </div>
    </div>

    <div class="row">

      <div class="col-12">
        <q-list bordered padding class="rounded-borders">
          <q-item v-for="item in photos" :key="item.id" v-ripple clickable>
            <q-item-section avatar top>
              <q-avatar color="primary" text-color="white">{{item.extension}}</q-avatar>
            </q-item-section>

            <q-item-section>
              <q-item-label v-if="item.titre" lines="1">{{item.titre}}</q-item-label>
              <q-item-label v-if="!item.titre" lines="1">{{item.name}}</q-item-label>
              <q-item-label caption>{{item.date}}</q-item-label>
            </q-item-section>

            <q-item-section side>
              <a target="_blank" :href="'https://fmmi.ci/'+item.url" style="text-decoration: none">
                <q-icon size="md" name="visibility" color="primary" />
              </a>
            </q-item-section>
          </q-item>
        </q-list>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import basemixin from "pages/basemixin";
import {LocalStorage} from "quasar";
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';

export default {
  name: 'FilesComponent',
  components: {
    Cropper
  },
  emits: ['input', 'blur'],
  mixins: [basemixin],
  model: {
    event: 'blur'
  },
  props: {
    type: {type: String, default: null},
    typeid: {type: Number, default: null},
    folder: {type: String, default: null}
  },
  data: function () {
    return {
      titre: null,
      previewImage: null,
      extension: null,
      file: {},
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
  created: function () {
    this.photos_get();
  },
  methods: {
    post() {
      let formData = new FormData()
      formData.append('file', this.file)
      formData.append('type', this.type)
      formData.append('typeid', this.typeid)
      formData.append('folder', this.folder)
      formData.append('titre', this.titre)
      formData.append('extension', this.extension)
      axios.post(this.apiurl+'/my/post/photos', formData,
        {
          headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
        }
      ).then(()=> {
        this.photos_get();
      })
      this.$emit('blur', {
        image: this.image,
        image_name: 'images',
        image_extension: this.image_extension,
        type: this.type
      })
    },
    async change(datacropper) {
      console.log(datacropper)
      datacropper.canvas.toBlob((blob) => {
        this.file = new File([blob], "file");
      });
    },
    handleInput ($event) {
      this.extension = $event.target.files[0].name.split('.')[1];
      if ($event.target.files[0]) {
        if($event.target.files[0].type === 'image/png' ||
          $event.target.files[0].type === 'image/jpeg' ||
          $event.target.files[0].type === 'image/gif') {
          let reader = new FileReader
          reader.onload = e => {
            this.previewImage = e.target.result;
          }
          reader.readAsDataURL($event.target.files[0]);
          this.$emit('input', $event.target.files[0]);
        }else {
          this.file = $event.target.files[0]
        }
      }
    },
    photos_get() {
      axios.get(this.apiurl+'/my/get/photos/'+this.type+'/'+this.typeid,{
        headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
      }).then((data)=> {
        this.photos = data['data'];
      })
    },
    // photos_delete(_id) {
    //   axios.get(this.apiurl+'/my/delete/photos/'+_id,{
    //     headers: { Authorization: 'bearer ' + LocalStorage.getItem('token') }
    //   }).then(()=> {
    //     this.photos_get();
    //   })
    // }
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
