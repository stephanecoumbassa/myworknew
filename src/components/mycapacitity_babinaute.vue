<template>
  <div class="content-form-member">
    <div class="form-member-item">
      <div v-for="(item, index) in capacities_list" :key="index">
        <div class="card">
          <div class="card-body">
            {{item.type_name}}: {{item.value}} {{item.unity}}
          </div>
          <div class="card-footer">
            <button type="button" v-on:click="capacity_delete(item)">Supprimer</button>
            <button type="button" v-on:click="update(item, index)">Modifier</button>
          </div>
        </div>
        <form @submit.prevent="capacities_post" v-if="updated_status[index]">
          <div v-for="(item, index) in capacities" :key="index">
            <div class="form-member-item">
              <label>Champs</label>
              <select v-validate="'required'" required id="form-select-styled" v-model="item.type" @input="$emit('blur', capacities)">
                <option></option>
                <option v-for="capacity in capacities_type_list" :value="capacity.id" :key="capacity.id" >{{capacity.name}}</option>
              </select>
            </div>
            <div class="form-member-item">
              <label>Valeur</label>
              <input v-validate="'required'" required type="number" v-model="item.value" />
            </div>
            <div class="form-member-item">
              <label>Unité</label>
              <input required type="text" v-model="item.unity" />
            </div>
            <div class="form-member-2item1">
              <input type="button" name="submit" value="Annuler" v-on:click="reset()">
            </div>
            <div class="form-member-2item1">
              <input v-if="update_status" type="button" name="submit" value="Modifier" v-on:click="capacity_update(item)">
            </div>
          </div>
        </form>
      </div>
    </div>
    <button type="button" v-on:click="add_status= !add_status">Ajouter une nouvelle capacité</button>
    <form @submit.prevent="capacities_post" v-if="add_status">
      <div v-for="(item, index) in capacities" :key="index">
        <div class="form-member-item">
          <label>Champs</label>
          <select v-validate="'required'" required id="form-select-styled" v-model="item.type" @input="$emit('blur', capacities)">
            <option></option>
            <option v-for="capacity in capacities_type_list" :value="capacity.id" :key="capacity.id" >{{capacity.name}}</option>
          </select>
        </div>
        <div class="form-member-item">
          <label>Valeur</label>
          <input v-validate="'required'" required type="number" v-model="item.value" />
        </div>
        <div class="form-member-item">
          <label>Unité</label>
          <input required type="text" v-model="item.unity" />
        </div>
        <input v-if="update_status" type="button" name="submit" value="Modifier" v-on:click="capacity_update(item)">
      </div>
      <button v-if="!update_status" type="submit">Ajouter</button>
    </form>
  </div>
</template>

<script>
module.exports = {
  data: function() {
    return {
      count: 0,
      // update_status: false,
      add_status: false,
      updated_status: [false],
      update_status: false,
      capacities: [{}],
      capacities_list: '',
      capacities_type_list: []
    }
  },
  created: function () {
    this.capacities_get();
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
      // the callback will be called immediately after the start of the observation
      immediate: true,
      handler (val, oldVal) {
        this.capacity_get();
        this.capacities_get();
      }
    }
  },
  methods: {
    handleInput (value) {
      this.$emit('blur', value)
    },
    capacity_get() {
      getWithParams('/api/get/capacities', { id: this.idligne }).then((data) => {
        this.capacities_list = JSON.parse(data.capacities);
      })
    },
    capacities_get() {
      getWithParams('/admin/capacities').then(data => {
        this.capacities_type_list = data;
      });
    },
    capacities_post() {
      this.$dialog.confirm('Voulez vous ajouter ?').then((dialog) => {
        postWithParams('/api/post/capacities', {
          capacities: this.capacities, id: this.idligne, typerubrique: this.typerubrique
        }).then((data) => {
          this.capacity_get();
          this.capacities = [{}];
        });
      })
    },
    capacity_delete(capacity) {
      this.$dialog.confirm('Voulez vous supprimer ?').then((dialog) => {
        deleteWithParams('/api/delete/capacities', { data: { id: capacity.id } }).then((data) => {
          this.capacity_get();
          this.capacities = [{}];
        });
      })
    },
    capacity_update(capacity) {
      capacity.typeid = capacity.type;
      this.update_status = false;
      this.$dialog.confirm('Voulez vous modifier ?').then((dialog) => {
        putWithParams('/api/put/capacities', capacity).then((data) => {
          this.capacity_get();
          this.capacities = [{}];
          this.updated_status = [false];
        });
      })
    },
    reset() {
      this.capacities = [{}];
      this.update_status = false;
      this.updated_status = [false];
    },
    update(capacity, index) {
      this.capacities = [capacity];
      this.update_status = true;
      this.updated_status = [false];
      this.updated_status[index] = true;
    },
    capacities_add() {
      this.capacities.push({ 'type': null, 'value': null, 'unity': null });
    },
    capacities_delete() {
      this.capacities.pop();
    }
  }
}
</script>

<style scoped>
</style>
