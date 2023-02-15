<template>
  <div class="content-form-member">
    <div class="form-member-item" v-if="links_list.length >=1">
      <div v-for="(item, index) in links_list" :key="index">
        <div class="card">
          <div class="card-body">
            {{item.type}}: {{item.name}}
          </div>
          <div class="card-footer">
            <button type="button" v-on:click="links_delete(item)">Supprimer</button>
            <button type="button" v-on:click="update(item, index)">Modifier</button>
          </div>
        </div>
      </div>
    </div>
    <button type="button" v-on:click="add_status= !add_status">Ajouter un nouveau lien</button>
    <form @submit.prevent="link_post" v-if="add_status">
      <div v-for="(item, index) in links" :key="index">
        <div class="form-member-item">
          <label class="form-member-item">Type de lien</label>
          <select id="form-select-styled" v-validate="'required'" required v-model="item.type" @input="$emit('blur', links)">
            <option></option>
            <option v-for="item in links_type_list"
                    :value="item.id"
                    :key="item.id">
              {{item.name}}
            </option>
          </select>
        </div>
        <div class="form-member-item">
          <label class="form-member-item">Lien</label>
          <input v-validate="'required|url'" required type="url" v-model="item.name" />
        </div>
        <div class="form-member-item">
          <input v-if="update_status" type="button" name="submit" value="modifier" v-on:click="link_update(item)">
        </div>
      </div>
      <button v-if="!update_status" type="submit">Ajouter</button>
    </form>
    <!-- <button v-if="!update_status" type="button" v-on:click="link_delete">-</button> -->
    <!-- <button v-if="!update_status" type="button" v-on:click="link_add">+</button> -->
  </div>
</template>
<script>
  module.exports = {
    data: function() {
      return {
        count: 0,
        add_status: false,
        updated_status: [false],
        update_status: false,
        links: [{}],
        link: {},
        links_type_list: [],
        links_list: []
      }
    },
    created: function () {
      this.links_get();
    },
    model: {
      event: 'blur'
    },
    props: {
      idligne: Number,
      typerubrique: Number
    },
    watch: {
      idligne: {
        immediate: true,
        handler (val, oldVal) {
          this.link_get();
        }
      }
    },
    methods: {
      handleInput (value) {
        this.$emit('blur', value)
      },
      links_get() {
        getWithParams('/api/get/links_type').then((data) => {
          this.links_type_list = data;
        })
      },
      link_get() {
        getWithParams('/api/get/links', { idligne: this.idligne, typerubrique: this.typerubrique }).then((data) => {
          this.links_list = JSON.parse(data.links);
        })
      },
      link_post() {
        postWithParams('/api/post/links', { links: this.links, id: this.idligne, typerubrique: this.typerubrique })
          .then((data) => {
            this.links = [{}];
            this.link_get();
          });
      },
      link_update(link) {
        this.$dialog.confirm('Please confirm to continue').then((dialog) => {
          this.update_status = false;
          putWithParams('/api/put/links', link).then((data) => {
            this.links = [];
            this.link_get();
            this.updated_status = [false];
          });
        })
      },
      links_delete(link) {
        this.$dialog.confirm('Please confirm to continue').then((dialog) => {
          deleteWithParams('/api/delete/links', { data: { id: link.id } }).then((data) => {
            this.links = [{}];
            this.link_get();
          });
        })
      },
      reset() {
        this.links = [{}];
        this.update_status = false;
        this.updated_status = [false];
      },
      update(link, index) {
        link.type = link.typeid;
        this.links = [link];
        this.update_status = true;
        this.updated_status = [false];
        this.updated_status[index] = true;
      },
      link_add() {
        this.links.push({});
      },
      link_delete() {
        this.links.pop();
      }
    }
  }
</script>

<style scoped>
</style>
