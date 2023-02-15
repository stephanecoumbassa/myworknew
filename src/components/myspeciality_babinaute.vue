<template>
  <div class="content-form-member">
    <div v-for="item in specialities_list_item" :key="item.id">
      <div class="card">
        <div class="card-body form-member-item">
          <div class="form-member-2item1">
            <select required id="form-select-styled" v-model="item.specialityid" readonly>
              <option :value="item.specialityid" :key="item.specialityid">{{item.specialityname}}</option>
            </select>
          </div>

          <div class="form-member-2item2">
            <select required id="form-select-styled" v-model="item.value_id">
              <option v-for="auto in JSON.parse(JSON.stringify(item.autocomplete))" :value="auto.id" :key="auto.id">{{auto.name}}</option>
            </select>
          </div>
        </div>
        <div class="card-footer">
          <button type="button" v-on:click="speciality_delete(item)">Supprimer</button>
          <button type="button" v-on:click="speciality_update(item)">Modifier</button>
        </div>
      </div>
    </div>
    <form @submit.prevent="specialities_create">
      <div v-for="item in specialities" :key="item.id">
        <div class="form-member-item">
          <label>Champs</label>
          <select v-validate="'required'" required id="form-select-styled" v-model="item.id"
                  @change="categoriesfilter(item.id)" @input="$emit('blur', specialities)">
            <option></option>
            <option v-for="sp in specialities_list"
                    :value="sp.id"
                    :key="sp.id"
            >{{sp.name}}</option>
          </select>
        </div>
        <div class="form-member-item">
          <label>liste de spécialités</label>
          <v-select v-validate="'required'" data-vv-value-path="mutableValue" data-vv-name="custom" :has-error="errors.has('custom')" multiple label="name" v-model="item.value" :options="autocomplete_list" />
        </div>
      </div>
      <button type="submit">Ajouter</button>
    </form>
    <!--  <button type="button" v-on:click="specialities_delete">-</button> -->
    <!-- <button type="button" v-on:click="specialities_add">+</button> -->
  </div>
</template>

<script>
module.exports = {
  data: function() {
    return {
      count: 0,
      item: [],
      update_status: false,
      // specialities: [{id:'', value: '', value2: [{'label': null, 'id': null}]}],
      // specialities: [{}],
      specialities: [{}],
      specialities_list: '',
      specialities_list_item: [],
      autocomplete_list: [],
      autocomplete_initiales: '',
      valeur_json: []
    }
  },
  props: {
    idligne: Number,
    typerubrique: Number
  },
  watch: {
    idligne: {
      immediate: true,
      handler (val, oldVal) {
        this.speciality_get();
      }
    }
  },
  created: function () {
    this.specialities_get();
    this.specialities_autocomplete_get();
  },
  model: {
    event: 'blur'
  },
  methods: {
    handleInput (value) {
      this.$emit('blur', value)
    },
    setSelected(value) {
      this.valeur_json.push(value);
    },
    categoriesfilter(id) {
      this.autocomplete_list = this.autocomplete_initiales;
      let autos = this.autocomplete_list.filter((auto) => {
        return auto.speciality_id === String(id);
      });
      console.log(autos);
      this.autocomplete_list = autos;
    },
    jsonifier(value) {
      return JSON.parse(JSON.stringify(value));
    },
    speciality_get() {
      getWithParams('/api/get/specialities', { id: this.idligne }).then((data) => {
        console.log(data);
        this.specialities_list_item = JSON.parse(data.specialities);
        console.log(this.specialities_list_item);
      })
    },
    specialities_get() {
      getWithParams('/api/get/specialities_type').then(data => {
        const res = JSON.stringify(data);
        this.specialities_list = JSON.parse(res);
        console.log(this.specialities_list);
      });
    },
    specialities_autocomplete_get() {
      getWithParams('/api/get/specialities_autocomplete').then(data => {
        const res = JSON.stringify(data);
        this.autocomplete_list = JSON.parse(res);
        this.autocomplete_initiales = JSON.parse(res);
        console.log(this.autocomplete_list);
      });
    },
    specialities_add() {
      this.specialities.push({ 'type': null, 'name': null });
    },
    specialities_create() {
      valjson = [];
      for (let i = 0; i < this.specialities.length; i++) {
        for (let j = 0; j < this.specialities[i].value.length; j++) {
          valjson.push(this.specialities[i].value[j]);
        }
      }
      this.update_status = true;
      this.$dialog.confirm('Please confirm to continue').then((dialog) => {
        postWithParams('/api/post/specialities', { valjson: JSON.stringify(valjson), id: this.idligne }).then(data => {
          console.log(data);
          this.speciality_get();
        });
      })
    },
    speciality_update(speciality) {
      this.$dialog.confirm('Please confirm to continue').then((dialog) => {
        putWithParams('/api/put/specialities', speciality).then((data) => {
          console.log(data);
          this.speciality_get();
        });
      })
    },
    speciality_delete(speciality) {
      this.$dialog.confirm('Please confirm to continue').then((dialog) => {
        deleteWithParams('/api/delete/specialities', { data: { id: speciality.id } }).then((data) => {
          console.log(data);
          this.speciality_get();
        });
      })
    },
    specialities_delete() {
      this.specialities.pop();
    },
    update(speciality) {
      this.specialities = [speciality];
      this.update_status = true;
      console.log(this.specialities);
    }
  }
}
</script>

<style scoped>
</style>
