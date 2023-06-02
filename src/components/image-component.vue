<template>
  <div class="no-padding">
    <input v-model="dataUrl" type="hidden" name="image" @change="handleInput" />
    <div v-if="show_crop">
      <input v-if="choose_status" v-model="sliderVal" type="range" :min="sliderMin" :max="sliderMax" step="0.00001" style="width: 200px; max-width: 100%" @input="onSliderChange">
      <br><q-btn
v-if="choose_status" size="xs"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                 @click="uploadCroppedImage('image/jpeg')">Recadrer</q-btn>
    </div>
    <div v-if="!show_crop">
      <img :src="dataUrl" :style="'max-width:'+ width +'px; width: 100%; height: auto;'">
      <span style="position: absolute"><q-btn  size="xs" type="reset" color="negative" @click='reset()'>X</q-btn></span>
    </div>
  </div>
</template>

<script>
// import Croppa from 'vue-croppa'
export default {
  name: 'ImageComponent',
  components: {
    // 'croppa': Croppa.component
  },
  model: {
    event: 'blur'
  },
  props: {
    idligne: Number,
    folder: String,
    height: { type: Number, default: 300 },
    width: { type: Number, default: 300 },
  },
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
      dataUrl: '',
      image: '',
      type: { id: 1, width: this.width, height: this.height, quality: 1 },
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
  watch: {
    idligne: {
      immediate: true,
      handler () {
        // this.medias_get()
      }
    }
  },
  created: function () {
    // this.medias_type_get()
  },
  methods: {
    handleInput () {
      this.$emit('blur', {
        image: this.image,
        image_name: 'images',
        image_extension: this.image_extension,
        type: this.type
      })
    },
    choose () {
      this.croppa.chooseFile()
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
    update (item) {
      this.choose_status = true;
      this.show_crop = true;
      this.name = '';
      this.image_name = item.name;
      this.typeid = item.type_id;
      var image = new Image();
      image.src = 'http://localhost:8000/assets/uploads/' + this.folder + item.name;
      image.setAttribute('crossorigin', 'anonymous');

      this.name = image;
      this.myCroppa.refresh()
    },
    uploadCroppedImage () {
      this.dataUrl = this.myCroppa.generateDataUrl('image/png');
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
