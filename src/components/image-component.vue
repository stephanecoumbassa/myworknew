<template>
  <div class="no-padding">
          <input type="hidden" name="image" v-on:change="handleInput" v-model="dataUrl" />
<!--          <input type="hidden" v-model="image_name" name="image_name" v-on:change="handleInput" value="images" />-->
<!--          <input type="hidden" v-model="image_extension" name="image_extension" value="jpg" />-->
<!--          <input type="hidden" v-model="type_id"  name="type" value="2" />-->
<!--          <div v-if="show_crop" style="height: 320px;">-->
          <div v-if="show_crop">
<!--            <croppa-->
<!--              v-model="myCroppa"-->
<!--              :width="type.width"-->
<!--              :height="type.height"-->
<!--              :quality="quality"-->
<!--              :canvas-color="'transparent'"-->
<!--              :placeholder-font-size="14"-->
<!--              :removeButtonSize="18"-->
<!--              initial-size="contain"-->
<!--              :initial-image="name"-->
<!--              :border="true"-->
<!--              border-color="'default'"-->
<!--              :border-size="2"-->
<!--              placeholder="Choisissez votre image"-->
<!--              :file-size-limit="9024000"-->
<!--              @file-type-mismatch="onFileTypeMismatch"-->
<!--              @file-size-exceed="onFileSizeExceed"-->
<!--              @zoom="onZoom"-->
<!--              @new-image="onNewImage"-->
<!--              @image-remove="onRemove"-->
<!--              style="border: 1px solid #c7b9b9"-->
<!--            >-->
<!--               <img slot="placeholder" crossorigin="anonymous" :src="src" style="object-fit: cover" />-->
<!--            </croppa>-->
            <input v-if="choose_status" type="range" @input="onSliderChange" :min="sliderMin" :max="sliderMax" step="0.00001" v-model="sliderVal" style="width: 200px; max-width: 100%">
            <br><q-btn size="xs" v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                       @click="uploadCroppedImage('image/jpeg')">Recadrer</q-btn>
          </div>
          <div v-if="!show_crop">
<!--            <img :src="dataUrl" :style="'max-width:'+ width +'px; height:'+ height+'px'"><br>-->
            <img :src="dataUrl" :style="'max-width:'+ width +'px; width: 100%; height: auto;'">
            <span style="position: absolute"><q-btn  size="xs" type="reset" v-on:click='reset()' color="negative">X</q-btn></span>
          </div>
  </div>
</template>

<script>
// import Croppa from 'vue-croppa'
export default {
    name: 'ImageComponent',
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
    components: {
        // 'croppa': Croppa.component
    },
    props: {
        typerubrique: Number,
        idligne: Number,
        folder: String,
        src: { type: String, default: 'https://media.sproutsocial.com/uploads/2017/02/10x-featured-social-media-image-size.png' },
        height: { type: Number, default: 300 },
        width: { type: Number, default: 300 },
        quality: { type: Number, default: 1 },
        type_id: { type: Number, default: 1 }
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
    model: {
        event: 'blur'
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
