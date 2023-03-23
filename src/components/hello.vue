<template>
  <div>
    <div>
      <div class="row">
        <div class="col-4 mt-4 mb-2" v-for="item in medias_list" v-bind:key="item.id">
          {{item.type}} -{{item.name}}<br>
          <img style="width: 98%; height: 200px; object-fit: cover" :src="'https://www.affairez.com/apistock/public/shop/'+entreprise.id+'/'+folder+'/'+item.name"/><br>
          <q-btn size="xs" color="negative" type="button" v-on:click="medias_delete(item.id, item.name)">Supprimer</q-btn>
          <q-btn size="xs" color="secondary" type="button" v-on:click="update(item)">Remplacer</q-btn>
        </div>
      </div>
      <br><br>
      <form>
        <div class="row">
          <div class="col-4">
            <select required class="form-control search-slt" v-model="typeid" v-on:change='selected_type()'>
              <option></option>
              <option v-for="item in medias_type" :value="item.id" :key="item.id">
                {{item.name}}
              </option>
            </select><br>
          </div>
          <div class="col-12">
            <input type="hidden" name="image" v-on:change="handleInput" v-model="dataUrl" />
            <input type="hidden" v-model="image_name" name="image_name" v-on:change="handleInput" />
            <input type="hidden" v-model="image_extension" name="image_extension" />
            <!--            <input type="hidden" v-model="type_id" readonly  name="type" />-->
            <div v-if="show_crop" style="height: 320px;">
              <croppa
                v-model="myCroppa"
                :width="type.width"
                :height="type.height"
                :quality="type.quality"
                :canvas-color="'white'"
                :placeholder-font-size="14"
                initial-size="contain"
                :initial-image="name"
                placeholder="Choisissez votre image"
                :file-size-limit="9024000"
                @file-type-mismatch="onFileTypeMismatch"
                @file-size-exceed="onFileSizeExceed"
                @zoom="onZoom"
                @new-image="onNewImage"
                @image-remove="onRemove"
              >
              </croppa><br>
              <input type="file" @change="change" />
              <input v-if="choose_status" type="range" @input="onSliderChange" :min="sliderMin" :max="sliderMax" step="0.00001" v-model="sliderVal" style="width: 200px; max-width: 100%">
              <br><q-btn size="xs" v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                         @click="uploadCroppedImage('image/jpeg')">Recadrer</q-btn>
            </div>
            <div v-if="!show_crop">
              <img :src="dataUrl" style="max-height: 150px; height: 100%"><br>
              <q-btn size="xs" type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                     @click="show_crop = true">Retour</q-btn>
              <q-btn size="xs" type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault()"
                     v-on:click="medias_add">Ajouter</q-btn>
              <!--              <q-btn size="xs" type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault()"-->
              <!--                      v-on:click="medias_update">Modifier</q-btn>-->
            </div>
            <div class="row">
              <!--<button type="reset" v-on:click='reset()'>Rénitialiser</button>-->
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import $httpService from '../boot/httpService';
import Croppa from 'vue-croppa'
import { defineComponent } from 'vue'

export default defineComponent({
// export default {
  name: 'HelloComponent',
  data: function () {
    return {
      // type: this.type_id,
      image_name: '',
      name: '',
      myheight: this.height,
      mywidth: this.width,
      image_extension: 'jpg',
      who: 'Stefyu',
      medias_list: [],
      medias_type: [],
      myCroppa: {},
      entreprise: {},
      dataUrl: '',
      image: '',
      type: { id: 1, width: this.width, height: this.height, quality: this.quality },
      typeid: 1,
      choose_status: false,
      show_crop: true,
      status_upload: false,
      image_name_old: "<?= $_SESSION['photo'] ?>",
      sliderVal: 0,
      sliderMin: 0,
      sliderMax: 0
    }
  },
  components: {
    'croppa': Croppa.component
  },
  props: {
    typerubrique: Number,
    idligne: Number,
    folder: String,
    height: {
      type: Number,
      default: 300
    },
    width: {
      type: Number,
      default: 300
    },
    quality: {
      type: Number,
      default: 3
    },
    type_id: {
      type: Number,
      default: 1
    }
  },
  watch: {
    idligne: {
      immediate: true,
      handler () {
        this.medias_get()
      }
    }
  },
  created: function () {
    this.medias_type_get();
    this.shop_get();
  },
  model: {
    event: 'blur'
  },
  methods: {
    change(e) {
      console.log(e);
    },
    handleInput () {
      this.$emit('blur', {
        image: this.image,
        image_name: 'images',
        image_extension: this.image_extension,
        type: this.type
      })
    },
    selected_type () {
      if (this.typeid === 1) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
      if (this.typeid === 2) { this.type = { id: 2, width: 200, height: 200, quality: 1.25 } }
      if (this.typeid === 3) { this.type = { id: 3, width: 600, height: 100, quality: 2 } }
      if (this.typeid === 4) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
      if (this.typeid === 5) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
    },
    choose () {
      this.croppa.chooseFile()
    },
    medias_type_get () {
      $httpService.getWithParams('/api/get/medias_type').then(data => {
        const res = JSON.stringify(data)
        this.medias_type = JSON.parse(res)
      })
    },
    medias_get () {
      $httpService.getWithParams('/api/get/medias', { id: this.idligne, typerubrique: this.typerubrique }).then((data) => {
        this.medias_list = JSON.parse(data.medias)
      })
    },
    removeImage: function () {
      this.image = ''
    },
    reset () {
      this.image = null;
      this.image_name = '';
      this.choose_status = false;
      this.show_crop = true;
      this.status_upload = false;
    },
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    medias_add () {
      if (confirm('Voulez vous ajouter ?')) {
        $httpService.postWithParams('/my/post/medias_one',
          {
            'typerubrique': this.typerubrique,
            'type': this.typeid,
            'id': this.idligne,
            'folder': this.folder,
            'image_extension': this.image_extension,
            'image': this.image,
            'image_name': this.image_name
          }
        ).then(data => {
          this.medias_get();
          if (data.status === 1) { this.status_upload = true }
        })
      }
    },
    medias_update () {
      if (confirm('Voulez vous ajouter ?')) {
        $httpService.putWithParams('/api/put/medias_one',
          {
            'typerubrique': this.typerubrique,
            'type': this.typeid,
            'id': this.idligne,
            'folder': this.folder,
            'image_extension': this.image_extension,
            'image': this.image,
            'image_name': this.image_name
          }
        ).then(data => {
          this.medias_get();
          if (data.status === 1) { this.status_upload = true }
        })
      }
    },
    medias_delete (id, filename) {
      $httpService.postWithParams('/api/delete/medias', { id: id, folder: this.folder, filename: filename, idligne: this.idligne, typerubrique: this.typerubrique }).then(() => {
        this.medias_get()
      })
    },
    update (item) {
      this.choose_status = true;
      this.show_crop = true;
      this.name = '';
      this.image_name = item.name;
      this.typeid = item.type_id;
      if (this.typeid === 1) { this.type = { id: 1, width: 300, height: 300, quality: 2 } }
      if (this.typeid === 2) { this.type = { id: 2, width: 200, height: 200, quality: 1.25 } }
      if (this.typeid === 3) { this.type = { id: 3, width: 600, height: 100, quality: 2 } }
      if (this.typeid === 4) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
      if (this.typeid === 5) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
      var image = new Image()
      image.src = 'http://localhost:8000/assets/uploads/' + this.folder + item.name
      image.setAttribute('crossorigin', 'anonymous')
      this.name = image;
      this.myCroppa.refresh()
    },
    uploadCroppedImage () {
      this.dataUrl = this.myCroppa.generateDataUrl('image/jpeg');
      this.image = this.dataUrl;
      this.show_crop = false;
      this.choose_status = false;
      this.handleInput()
    },
    onFileTypeMismatch () {
      alert('Type de fichier invalide. Veuillez choisir un fichier jpeg or png.')
    },
    onFileSizeExceed () {
      alert('Taille du fichier depassé. SVP Veuillez choisir un fichier plus petit que 1000kb.')
    },
    onNewImage () {
      this.sliderVal = this.myCroppa.scaleRatio + 0.1;
      this.sliderMin = this.myCroppa.scaleRatio / 2;
      this.sliderMax = (this.myCroppa.scaleRatio + 7) * 2;
      this.choose_status = true
    },
    onRemove () {
      this.choose_status = false;
      this.image = '';
    },
    onSliderChange (evt) {
      var increment = evt.target.value;
      this.myCroppa.scaleRatio = +increment;
    },
    onZoom () {
      this.sliderVal = this.myCroppa.scaleRatio;
    }
  }
})
</script>

<style scoped>
.croppa-container {
  background-color: white;
  border: 3px solid black;
  border-radius: 3px;
  width: 310px;
}
</style>
