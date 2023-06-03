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
export default {
  name: 'ImageComponent',
  components: {
  },
  model: {
    event: 'blur'
  },
  props: {
    idligne: { type: Number, default: 300 },
    width: { type: Number, default: 300 },
  },
  data: function () {
    return {
      // type: this.type_id,
      image_name: '',
      image_extension: 'jpg',
      myCroppa: {},
      dataUrl: '',
      image: '',
      choose_status: false,
      show_crop: true,
      status_upload: false,
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
  emits:['blur'],
  methods: {
    handleInput () {
      this.$emit('blur', {
        image: this.image,
        image_name: 'images',
        image_extension: this.image_extension,
        type: this.type
      })
    },
    reset () {
      this.image = null;
      this.image_name = '';
      this.choose_status = false;
      this.show_crop = true;
      this.status_upload = false;
    },
    uploadCroppedImage () {
      this.dataUrl = this.myCroppa.generateDataUrl('image/png');
      this.image = this.dataUrl;
      this.show_crop = false;
      this.choose_status = false;
      this.handleInput()
    },
    onSliderChange (evt) {
      var increment = evt.target.value;
      this.myCroppa.scaleRatio = +increment;
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
