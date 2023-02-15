<template>
    <div>
        <form>
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label class="form-text text-dark">Mots Cles</label>
                        <input class="form-control" type="text" v-model="tags" />
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-text text-dark">Actions</label>
                    <button type="button" class="btn btn-primary btn-sm" v-on:click="tag_post()">Ajouter</button>
                </div>
            </div>
            <div v-if="tags_list != null">
                Mots cles existants
            </div>
        </form>
    </div>
</template>

<script>
module.exports = {
    data: function() {
        return {
            count: 0,
            // tags: {},
            country: '',
            tags_list: null,
            tags: ''
        }
    },
    props: {
        idligne: Number,
        typerubrique: Number
    },
    created: function () {

    },
    model: {
        event: 'blur'
    },
    mounted: function() {

    },
    watch: {
        idligne: {
            // the callback will be called immediately after the start of the observation
            immediate: true,
            handler (val, oldVal) {
                this.tag_get();
            }
        }
    },
    methods: {
        handleInput (value) {
            this.$emit('blur', value)
        },
        tag_get() {
            getWithParams('/api/get/tags', { id: this.idligne, typerubrique: this.typerubrique }).then((data) => {
                console.log(data);
                this.tags_list = data.tags;
                if (data.tags == null) {
                    this.tags = ''
                } else {
                    var tag = JSON.parse(data.tags);
                    this.tags = tag[0].name;
                    console.log(this.tags);
                }
            })
        },
        tag_post(tag) {
            this.$dialog.confirm('Please confirm to continue').then((dialog) => {
                postWithParams('/api/post/tags', {
                    tags: this.tags,
                    id: this.idligne,
                    typerubrique: this.typerubrique
                }).then((data) => {
                    console.log(data);
                });
            })
        }
    }
}
</script>

<style scoped>
</style>
