<template>
    <div class="content-form-member">
            <div v-for="item in medias_list" v-bind:key="item.id" class="clearBoth margBottom30">
                <!--
                <div class="form-group">
                    <label class="form-text text-dark">Type de lien</label>
                    <select v-validate="'required'" required class="form-control search-slt" v-model="item.type_id">
                        <option></option>
                        <option v-for="item in medias_type"
                                :value="item.id"
                                :key="item.id"
                        >
                        </option>
                    </select>
                </div>-->
                <div class="clearBoth"></div>
                <div class="clearBoth marTop:10 margBottom10">
                {{item.type}} : {{item.name}}
                </div>
                <img style="width: 98%; height: 200px; object-fit: cover" :src="'https://abidjannet.weblogy.net/public/assets/uploads/'+folder+item.name"/>
                <button type="button" v-on:click="medias_delete(item.id, item.name)">Supprimer</button>
                <button type="button" v-on:click="update(item)">Remplacer</button>
            </div>
        <form>
                <div class="form-member-item">
                    <select v-validate="'required'" required id="form-select-styled" v-model="typeid" v-on:change='selected_type()'>
                        <option value="1">Photo (250x250)</option>
                        <option value="2">Logo (250x250)</option>
                        <option value="3">Cover (250x250)</option>
                        <!-- <option v-for="item in medias_type" :value="item.id" :key="item.id">
                                {{item.name}}
                        </option> -->
                    </select>
                </div>
                <div class="clearBoth">
                        <input type="hidden" name="image" v-on:change="handleInput" v-model="dataUrl" />
                        <input type="hidden" v-model="image_name" name="image_name" v-on:change="handleInput" value="images" />
                        <input type="hidden" v-model="image_extension" name="image_extension" value="jpg" />
                        <input type="hidden" v-model="type_id"  name="type" value="2" />
                        <div v-if="show_crop" style="height: 200px">
                            <croppa
                                    v-model="myCroppa"
                                    :width="type.width"
                                    :height="type.height"
                                    :quality="type.quality"
                                    initial-size="contain"
                                    :initial-image="name"
                                    placeholder="Choisissez votre image"
                                    :prevent-white-space="true"
                                    :file-size-limit="1024000"
                                    @file-type-mismatch="onFileTypeMismatch"
                                    @file-size-exceed="onFileSizeExceed"
                                    @zoom="onZoom"
                                    @new-image="onNewImage"
                                    @image-remove="onRemove"
                            >
                                <img slot="placeholder"  crossorigin="anonymous"
                                     src="../public/assets/imgs/defaut-img.svg" class="content-profil-photo addon"/>
                            </croppa>

							<input v-if="choose_status" type="range" @input="onSliderChange" :min="sliderMin" :max="sliderMax" step="0.00001" v-model="sliderVal" style="width: 200px; max-width: 100%; display:block; clear:both;">
                            <div class="clearBoth margTop10"></div>
                            <button v-if="choose_status"  type="button" class="content-profil-add-photo" onclick="event.preventDefault()" @click="uploadCroppedImage('image/jpeg')">Recadrer</button>
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
        </form>
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
            myheight: this.height,
            mywidth: this.width,
            image_extension: 'jpg',
            who: 'Stefyu',
            medias_list: [],
            medias_type: [],
            myCroppa: {},
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
        height: {
            type: Number,
            default: 250
        },
        width: {
            type: Number,
            default: 250
        },
        quality: {
            type: Number,
            default: 1
        },
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
        selected_type(index) {
            if (this.typeid == 1) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
            if (this.typeid == 2) { this.type = { id: 2, width: 200, height: 200, quality: 1.25 } }
            if (this.typeid == 3) { this.type = { id: 3, width: 600, height: 100, quality: 2 } }
            if (this.typeid == 4) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
            if (this.typeid == 5) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
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
        update(item) {
            this.choose_status = true;
            this.show_crop = true;
            this.name = '';
            this.image_name = item.name;
            this.typeid = item.type_id;
            this.id = item.id;
            if (this.typeid == 1) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
            if (this.typeid == 2) { this.type = { id: 2, width: 200, height: 200, quality: 1.25 } }
            if (this.typeid == 3) { this.type = { id: 3, width: 600, height: 100, quality: 2 } }
            if (this.typeid == 4) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
            if (this.typeid == 5) { this.type = { id: 1, width: 300, height: 200, quality: 2 } }
            this.name = 'https://abidjannet.weblogy.net/public/assets/uploads/' + this.folder + item.name;
            console.log(this.name);
            this.myCroppa.refresh();
        },
        uploadCroppedImage() {
            this.dataUrl = this.myCroppa.generateDataUrl('image/jpeg');
            this.image = this.dataUrl;
            this.show_crop = false;
            this.choose_status = false;
            this.handleInput();
        },
        onFileTypeMismatch (file) {
            alert('Type de fichier invalide. Veuillez choisir un fichier jpeg or png.')
        },
        onFileSizeExceed (file) {
            alert('Taille du fichier depassé. SVP Veuillez choisir un fichier plus petit que 1000kb.')
        },
        onNewImage() {
            console.log(this.myCroppa.scaleRatio);
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
        }
        /*
            upload() {
                if(confirm('Voulez-vous renitialiser votre mot de passe ?'))
                    postWithParams("/babinaute_photo_upload", {
                        "image": this.dataUrl,
                        "image_name": 'image.jpg',
                        "image_name_old": "profil_5d68fe94ed61d.jpg",
                        "image_extension": 'jpg',
                        "babinaute_id": this.id
                    }).then(data => {
                        if(data.status == 1){
                            this.status_upload = true;
                        }
                    })

            } */
    }
}
</script>

<style scoped>
canvas {
  width: 100%;
}
</style>
