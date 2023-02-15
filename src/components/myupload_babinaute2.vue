<template>
    <div class="content-form-member">
        <div v-for="(item, index) in medias_list" v-bind:key="index"  class="clearBoth margBottom30">
            <form v-bind="getSize(index)">
				<input type="hidden" name="image" v-model="dataUrl" />
				<input type="hidden" name="image_name" value="images" />
				<input type="hidden" name="image_extension" value="jpg" />
				<input type="hidden" name="babinaute_id" value="<?= $_SESSION['id'] ?>" />
					<croppa v-model="myCroppa[index]" :width="width[index]" :height="height[index]"
                    :quality="quality[index]" initial-size="contain" placeholder="Choisissez votre image"
                     :show-remove-button="false" :prevent-white-space="true" :file-size-limit="1024000" @file-type-mismatch="onFileTypeMismatch"
                      @file-size-exceed="onFileSizeExceed" @new-image="onNewImage(index)" @zoom="onZoom(index)" :initial-image="'https://abidjannet.weblogy.net/public/assets/uploads/'+folder+item.name">
					</croppa>
					<button v-if="!photo_status[index]" class="content-profil-add-photo" v-on:click="myCroppa[index].chooseFile();" type="button">Modifier</button>
					<input v-if="photo_status[index]" type="range" @input="onSliderChange(index)" :min="sliderMin" :max="sliderMax" step=".001" v-model="sliderVal" style="width: 50%">
				<button v-if="photo_status[index]" class="content-profil-add-photo" v-on:click="revenir(index)" type="button">Revenir</button>
				<button v-if="photo_status[index]" id="upload" @click="uploadCroppedImage(index, item)" type="button" class="content-profil-add-photo">Charger</button>
			</form>
            <div class="clearBoth"></div>
            <div class="clearBoth marTop:10 margBottom10">
            {{item.type}} : {{item.name}}
            </div>
        </div>
        <button type="button" v-on:click="add_status = !add_status" class="content-profil-add-photo">Ajouter une photo</button>
        <div class="clearBoth" v-if="add_status">
            <div class="form-member-item">
                <select v-validate="'required'" required id="form-select-styled" v-model="typeid" v-on:change='selected_type()'>
                    <option value="1">Photo (800x600)</option>
                    <option value="2">Logo (250x250)</option>
                    <option value="3">Cover (1200x200)</option>
                </select>
            </div>
            <input type="hidden" name="image" v-on:change="handleInput" v-model="dataUrl" />
            <input type="hidden" v-model="image_name" name="image_name" v-on:change="handleInput" value="images" />
            <input type="hidden" v-model="image_extension" name="image_extension" value="jpg" />
            <input type="hidden" v-model="type_id"  name="type" value="2" />
            <div v-if="show_crop" style="height: 200px">
                <croppa
                        v-model="croppa"
                        :width="type.width"
                        :height="type.height"
                        :quality="type.quality"
                        initial-size="contain"
                        :initial-image="name"
                        placeholder="Choisissez votre image"
                        :prevent-white-space="true"
                        :file-size-limit="1024000"
                        @file-type-mismatch="fileTypeMismatch"
                        @file-size-exceed="fileSizeExceed"
                        @zoom="zoom"
                        @new-image="newImage"
                        @image-remove="remove"
                >
                    <img slot="placeholder"  crossorigin="anonymous"
                         src="../public/assets/imgs/defaut-img.svg" class="content-profil-photo addon"/>
                </croppa>
			    <input v-if="choose_status" type="range" @input="sliderChange" :min="sliderMin" :max="sliderMax" step="0.00001" v-model="sliderVal" style="width: 200px; max-width: 100%; display:block; clear:both;">
                <div class="clearBoth margTop10"></div>
                <button v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()" @click="croppedImage('image/jpeg')">Recadrer</button>
            </div>
            <div v-if="!show_crop">
                <img :src="dataUrl" style="max-height: 150px; height: 100%">
                <button type="button" class="content-profil-add-photo" onclick="event.preventDefault()"
                        @click="show_crop = true">Retourner</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault()"
                    v-on:click="medias_add">Ajouter</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault()"
                    v-on:click="medias_update">Modifier</button>
            </div>
                        <!--<div>
                            <button type="reset" v-on:click='reset()'>Rénitialiser</button>
                        </div>-->
                </div>

    </div>
</template>

<script>
module.exports = {
    data: function() {
        return {
            type: this.type_id,
            image_name: '',
            id: null,
            name: '',
            height: [200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200, 200],
            width: [],
            quality: [],
            photo_status: [false],
            // photo_status: [false],
            add_status: false,
            myheight: 300,
            mywidth: 200,
            image_extension: 'jpg',
            who: 'Stefyu',
            medias_list: [],
            medias_type: [],
            myCroppa: [{}],
            croppa: {},
            dataUrl: '',
            image: '',
            type: { id: 1, width: 300, height: 200, quality: 2 },
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
    props: {
        typerubrique: Number,
        idligne: Number,
        folder: String,
        type_id: {
            type: Number,
            default: 1
        }
    },
    watch: {
        idligne: {
            immediate: true,
            handler (val, oldVal) {
                this.medias_get();
            }
        }
    },
    created: function () {
        // this.medias_type_get();
    },
    model: {
        event: 'blur'
    },
    methods: {
        handleInput ($event) {
            this.$emit('blur', {
                image: this.image,
                image_name: 'images',
                image_extension: this.image_extension,
                type: this.type
            });
        },
        getSize(index) {
            if (this.medias_list[index].type_id == 1) {
                this.width[index] = 300; this.height[index] = 225; this.quality[index] = 2.66666666667;
            }
            if (this.medias_list[index].type_id == 2) {
                this.width[index] = 200; this.height[index] = 200; this.quality[index] = 1.25;
            }
            if (this.medias_list[index].type_id == 3) {
                this.width[index] = 600; this.height[index] = 100; this.quality[index] = 2;
            }
        },
        selected_type(index) {
            if (this.typeid == 1) { this.type = { id: 1, width: 300, height: 225, quality: 2.66666666667 } }
            if (this.typeid == 2) { this.type = { id: 2, width: 200, height: 200, quality: 1.25 } }
            if (this.typeid == 3) { this.type = { id: 3, width: 600, height: 100, quality: 2 } }
            console.log(this.type.id);
        },
        choose() {
            this.croppa.chooseFile();
        },
        medias_type_get() {
            getWithParams('/api/get/medias_type').then(data => {
                const res = JSON.stringify(data);
                this.medias_type = JSON.parse(res);
            });
        },
        medias_get() {
            getWithParams('/api/get/medias', { id: this.idligne, typerubrique: this.typerubrique }).then((data) => {
                console.log(data);
                this.medias_list = JSON.parse(data.medias);
                console.log(this.medias_list);
            })
        },
        removeImage: function (e) {
            this.image = '';
        },
        reset () {
            this.image = null;
            this.image_name = '';
            this.choose_status = false;
            this.show_crop = true;
            this.status_upload = false;
        },
        revenir(index) {
            this.myCroppa[index].refresh();
            this.initial_image = 'https://abidjannet.weblogy.net/public/assets/uploads/' + this.folder + this.image_name_old;
            this.photo_status = false;
            console.log(this.photo_status);
        },
        medias_add() {
            this.$dialog.confirm('Please confirm to continue').then((dialog) => {
                postWithParams('/api/post/medias_one',
                               {
                                   'typerubrique': this.typerubrique,
                                   'type': this.typeid,
                                   'id': this.idligne,
                                   'folder': this.folder,
                                   'image_extension': this.image_extension,
                                   'image': this.image,
                                   'image_name': this.image_name
                               }
                )
                    .then(data => {
                        console.log(data);
                        this.medias_get();
                        if (data.status == 1) { this.status_upload = true }
                    });
            })
        },
        medias_update() {
            console.log()
            this.$dialog.confirm('Please confirm to continue').then((dialog) => {
                putWithParams('/api/put/medias_one',
                              {
                                  'typerubrique': this.typerubrique,
                                  'type': this.typeid,
                                  'id': this.id,
                                  'idligne': this.idligne,
                                  'folder': this.folder,
                                  'image_extension': this.image_extension,
                                  'image': this.image,
                                  'image_name': this.image_name
                              }
                )
                    .then(data => {
                        console.log(data);
                        this.medias_get();
                        if (data.status == 1) { this.status_upload = true }
                    });
            })
        },
        medias_delete(id, filename) {
            this.$dialog.confirm('Please confirm to continue').then((dialog) => {
                deleteWithParams('/api/delete/medias', { data: { id: id, folder: this.folder, filename: filename, idligne: this.idligne, typerubrique: this.typerubrique } }).then((data) => {
                    console.log(data);
                    this.medias_get();
                })
            })
        },
        croppedImage() {
            this.dataUrl = this.croppa.generateDataUrl('image/jpeg');
            this.image = this.dataUrl;
            this.show_crop = false;
            this.choose_status = false;
            this.handleInput();
        },
        uploadCroppedImage(index, item) {
            console.log(item);
            this.dataUrl = this.myCroppa[index].generateDataUrl('image/jpeg');
            this.image = this.dataUrl;
            this.$dialog.confirm('Please confirm to continue').then((dialog) => {
                putWithParams('/api/put/medias_one',
                              {
                                  'typerubrique': this.typerubrique,
                                  'type': this.typeid,
                                  'id': item.id,
                                  'idligne': item.idligne,
                                  'folder': this.folder,
                                  'image_extension': this.image_extension,
                                  'image': this.image,
                                  'image_name': item.name
                              }
                )
                    .then(data => {
                        console.log(data);
                        this.medias_get();
                        this.photo_status = [];
                        this.photo_status[index] = false;
                        if (data.status == 1) { this.status_upload = true }
                    });
            })
        },
        onFileTypeMismatch (file) {
            alert('Type de fichier invalide. Veuillez choisir un fichier jpeg or png.')
        },
        onFileSizeExceed (file) {
            alert('Taille du fichier depassé. SVP Veuillez choisir un fichier plus petit que 1000kb.')
        },
        onNewImage(index) {
            this.photo_status = [];
            this.photo_status[index] = true;
            console.log(this.photo_status);
            console.log(this.photo_status[index]);

            this.sliderVal = this.myCroppa[index].scaleRatio + 0.1;
            this.sliderMin = this.myCroppa[index].scaleRatio / 2;
            this.sliderMax = (this.myCroppa[index].scaleRatio + 5) * 2;
            this.choose_status = true;
        },
        onRemove() {
            this.choose_status = false;
            this.image = '';
        },
        onSliderChange(index) {
            this.myCroppa[index].scaleRatio = +this.sliderVal
        },
        onZoom(index) {
            this.sliderVal = this.myCroppa[index].scaleRatio
        },
        newImage() {
            console.log(this.myCroppa.scaleRatio);
            this.sliderVal = this.croppa.scaleRatio + 0.1;
            this.sliderMin = this.croppa.scaleRatio / 2;
            this.sliderMax = (this.croppa.scaleRatio + 4) * 2;
            this.choose_status = true;
        },
        remove() {
            this.choose_status = false;
            this.image = '';
        },
        sliderChange(evt) {
            var increment = evt.target.value
            this.croppa.scaleRatio = +increment
        },
        zoom() {
            this.sliderVal = this.croppa.scaleRatio
        },
        fileTypeMismatch (file) {
            alert('Type de fichier invalide. Veuillez choisir un fichier jpeg or png.')
        },
        fileSizeExceed (file) {
            alert('Taille du fichier depassé. SVP Veuillez choisir un fichier plus petit que 1000kb.')
        }
    }
}
</script>

<style scoped>
/* canvas {
  width: 100%;
} */
</style>
