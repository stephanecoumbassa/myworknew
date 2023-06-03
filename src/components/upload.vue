<template>
<!--  <div>-->
    <div class="row">
      <div v-if="show_crop">
        <input v-model="dataUrl" type="hidden" name="image" @change="handleInput" />
        <input v-model="image_name" type="hidden" name="image_name" value="images" @change="handleInput" />
        <input v-model="image_extension" type="hidden" name="image_extension" value="jpg" />
        <input type="hidden" :value="type_id || 2"  name="type" />
        <button
v-if="dimension" type="button"
                class="btn btn-sm btn-secondary" @click="dimension_status = !dimension_status">
          changer la dimension
        </button>
        <div v-if="dimension">
          <input v-if="dimension" v-model="mywidth" type="number" placeholder="largeur">
          <input v-if="dimension" v-model="myheight" type="number" placeholder="hauteur">
        </div>
        <div v-if="show_crop">
          <croppa
v-model="myCroppa" :width="mywidth" :height="myheight" :quality="quality"
                  initial-size="contain" placeholder="Ajouter votre image" :placeholder-font-size="14"
                  :placeholder-color="'default'" :prevent-white-space="true"
                  :file-size-limit="9024000" :show-remove-button="false"
                  @file-type-mismatch="onFileTypeMismatch" @file-size-exceed="onFileSizeExceed"
                  @zoom="onZoom" @new-image="onNewImage" @image-remove="onRemove">
            <!--                            <img v-if="!(src == null)" :src="src" slot="placeholder" />-->
            <!--                            <img v-if="!(src == null) && show_crop" slot="initial" :src="src" />-->
            <!--                            <img v-if="!(src == null) && show_crop" slot="placeholder" :src="src" />-->
          </croppa>
          <br>
          <input
v-if="choose_status" v-model="sliderVal" type="range"
                 :min="sliderMin" :max="sliderMax" step="0.00001" style="width: 200px; max-width: 100%" @input="onSliderChange">
          <br>
          <!-- <button type="button" class="content-profil-add-photo" @click="croppa.remove()">Supprimer</button> -->
          <button
v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                  @click="sup()">Supprimer</button>
          <button
v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                  @click="uploadCroppedImage('image/jpeg')">Charger</button>
        </div>
      </div>
      <div v-if="!show_crop" class="col-12">
        <img style="width: 50%; height: auto" :src="dataUrl"><br><br>
        <button
type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                @click="show_crop = !show_crop">Modifier</button>
      </div>
      <div v-if="!(src == null) && show_crop" class="col-6">
        <img :src="src" style="width: 90%; height: auto">
      </div>
    </div>
<!--  </div>-->
</template>

<script>
import Croppa from 'vue-croppa'
export default {
    name: 'UploadComponent',
    components: {
        'croppa': Croppa.component
    },
    model: {
        event: 'blur'
    },
    props: {
        src: { type: String, default: null },
        height: { type: Number, default: 250 },
        width: { type: Number, default: 250 },
        quality: { type: Number, default: 3 },
        dimension: { type: Boolean, default: false }
    },
    data: function() {
        return {
            type: this.type_id,
            image_name: '',
            myheight: this.height,
            mywidth: this.width,
            image_extension: 'jpg',
            myCroppa: {},
            dataUrl: '',
            image: '',
            dimension_status: false,
            choose_status: false,
            show_crop: true,
            sliderVal: 0,
            sliderMin: 0,
            sliderMax: 0
        }
    },
    emits: ['blur'],
    methods: {
        handleInput () {
            this.$emit('blur', {
                image: this.image,
                image_name: 'images',
                image_extension: this.image_extension,
                type: this.type
            });
        },
        uploadCroppedImage() {
            this.dataUrl = this.myCroppa.generateDataUrl('image/jpeg');

            this.image = this.dataUrl;
            this.show_crop = false;
            this.choose_status = false;
            this.handleInput();
        },
        onFileTypeMismatch () {
            alert('Type de fichier invalide. Veuillez choisir un fichier jpeg or png.')
        },
        onFileSizeExceed () {
            alert('Taille du fichier depassé. SVP Veuillez choisir un fichier plus petit que 1000kb.')
        },
        onNewImage() {
            this.sliderVal = this.myCroppa.scaleRatio + 0.1;
            this.sliderMin = this.myCroppa.scaleRatio / 2;
            this.sliderMax = (this.myCroppa.scaleRatio + 7) * 2;
            this.choose_status = true;
        },
        onRemove() {
            this.choose_status = false;
            this.image = '';
        },
        onSliderChange(evt) {
            var increment = evt.target.value
            this.myCroppa.scaleRatio = +increment
        },
        onZoom() {
            this.sliderVal = this.myCroppa.scaleRatio
        },
        sup() {
            this.myCroppa.remove();
        }
    }
}
</script>

<style scoped>
  .croppa-container {
    background-color: white;
    border: 1px solid gray;
    border-radius: 3px;
    width: 210px;
  }
  /* canvas { */
  /* width: 100%;} */
  /*.center {*/
    /*margin: auto;*/
    /* width: 50%; */
    /*width: 210px;*/
    /*height: auto;*/
    /* border: 3px solid black;*/
    /*padding: 0 auto;*/
  /*}*/
</style>
