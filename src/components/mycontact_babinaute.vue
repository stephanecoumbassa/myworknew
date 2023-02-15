<template>
  <div>
    <div v-for="(item, index) in contacts_list" :key="index">
      <div class="card">
        <div class="card-body">
          {{item.email}}<br>
          +{{item.indicatif}} {{item.telephone}}<br>
          {{item.fax}}<br>
          {{item.bp}}<br>
          {{item.website}}<br>
        </div>
        <div class="card-footer">
          <button type="button" v-on:click="contacts_delete(item)">Supprimer</button>
          <button type="button" v-on:click="update(item, index)">Modifier</button>
        </div>
      </div>
      <!-- <form @submit.prevent="contact_post" v-if="contacts.length >=1" > -->
      <form @submit.prevent="contact_post" v-if="updated_status[index]">
        <div class="content-form-member">
          <div v-for="item in contacts" :key="item.id">
            <div class="form-member-item">
              <label>Email</label>
              <input v-validate="'required|email'" name="email" type="email" v-model="item.email"/>
              <span>{{ errors.first('email') }}</span>
            </div>
            <div class="form-member-item">
              <label>Indicatif</label>
              <select name="indicatif" id="form-select-styled" v-model="item.indicatif" v-on:change="handleInput">
                <option></option>
                <option v-for="item in countries" :key="item.id" :value="item.indicatif">{{item.name_fr}} (+{{item.indicatif}})</option>
              </select>
            </div>
            <label>Telephone</label>
            <input required placeholder="numero" name="telephone" type="number" v-model="item.telephone" />
            <div class="form-member-item">
              <label>Fax</label>
              <input type="text" v-model="item.fax" />
            </div>
            <div class="form-member-item">
              <label>BP</label>
              <input type="text" v-model="item.bp" />
            </div>
            <div class="form-member-item">
              <label>website</label>
              <input v-validate="{url: {require_protocol: true }}" name="website" type=url v-model="item.website" />
              <span>{{ errors.first('website') }}</span>
            </div>
            <div class="form-member-item">
              <div class="form-member-2item1">
                <input type="button" name="submit" value="Annuler" v-on:click="reset">
              </div>
              <div class="form-member-2item2">
                <input type="button" name="submit" value="Modifier" v-on:click="contact_update(item)">
              </div>
              <div class="clearBoth">
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <button type="button" v-on:click="add_status= !add_status">Ajouter un nouveau contact</button>
    <form @submit.prevent="contact_post" v-if="add_status">
      <div class="content-form-member">
        <div>
          <div class="form-member-item">
            <label>Email</label>
            <input name="email" type="email" v-model="contact.email"/>
          </div>
          <div class="form-member-item">
            <label>Indicatif</label>
            <select name="indicatif" id="form-select-styled" v-model="contact.indicatif" v-on:change="handleInput">
              <option></option>
              <option v-for="item in countries" :key="item.id" :value="item.indicatif">{{item.name_fr}} (+{{item.indicatif}})</option>
            </select>
          </div>
          <label>Telephone</label>
          <input placeholder="numero" name="telephone" type="number" v-model="contact.telephone" />
          <div class="form-member-item">
            <label>Fax</label>
            <input type="text" v-model="contact.fax" />
          </div>
          <div class="form-member-item">
            <label>BP</label>
            <input type="text" v-model="contact.bp" />
          </div>
          <div class="form-member-item">
            <label>website</label>
            <input v-validate="{url: {require_protocol: true }}" name="website" type=url v-model="contact.website" />
            <span>{{ errors.first('website') }}</span>
          </div>
          <div class="form-member-item">
            <div class="form-member-2item1">
              <input type="button" name="submit" value="Annuler" v-on:click="reset">
            </div>
            <div class="form-member-2item2">
              <input class="btnSub inlineBlock" type="submit" name="submit" value="Valider">
            </div>
            <div class="clearBoth">
            </div>
          </div>

        </div>
      </div>
    </form>

    <!-- <button v-if="!update_status" type="button" v-on:click="contact_delete">-</button>
    <button v-if="!update_status" type="button" v-on:click="contact_add">+</button> -->
    <div class="clearBoth"></div>

  </div>
</template>

<script>
module.exports = {
  data: function() {
    return {
      who: 'world',
      contacts: [],
      contact: {},
      add_status: false,
      update_status: false,
      updated_status: [true],
      count: 0,
      countries: [],
      country: this.code,
      contacts_list: []
    }
  },
  created: function () {
    this.countries_get();
    this.handleInput();
  },
  model: {
    event: 'blur'
  },
  props: {
    idligne: Number,
    typerubrique: Number,
    code: {
      type: Number,
      default: 225
    }
  },
  watch: {
    idligne: {
      immediate: true,
      handler (val, oldVal) {
        this.contacts_get();
        // this.contacts_get();
      }
    }
  },
  methods: {
    handleInput (value) {
      this.$emit('blur', value)
    },
    reset() {
      this.contacts = [];
      this.update_status = false;
      this.updated_status = [true];
    },
    filter_req(value) {
      if (!value || value == undefined || value.length === 0) {
        return false;
      }
      return true;
    },
    contact_post() {
      // || this.filter_req(this.contact.telephone) || this.filter_req(this.contact.fax)
      //     this.filter_req(this.contact.bp) || this.filter_req(this.contact.website)
      if (this.filter_req(this.contact.email) || this.filter_req(this.contact.telephone) || this.filter_req(this.contact.fax) || this.filter_req(this.contact.bp) || this.filter_req(this.contact.website)) {
        this.$dialog.confirm('Please confirm to continue').then((dialog) => {
          this.$validator.validateAll().then((success) => {
            if (success == true) {
              if (confirm('Voulez vous ajouter ?')) {
                postWithParams('/api/post/contacts',
                  { contacts: [this.contact], id: this.idligne, typerubrique: this.typerubrique }
                ).then((data) => {
                  this.contacts_get();
                  this.contact = {};
                });
              }
            }
          }).catch((error) => {
            console.log(error);
          });
        })
      }
    },
    contact_add() {
      this.contacts.push({ 'email': null, 'telephone': null, 'fax': null });
    },
    contact_delete() {
      this.contacts.pop();
    },
    countries_get() {
      getWithParams('/api/get/countries').then((data) => {
        this.countries = data;
      });
    },
    contacts_get() {
      getWithParams('/api/get/contacts', { idligne: this.idligne, typerubrique: this.typerubrique }).then((data) => {
        this.contacts_list = JSON.parse(data.contacts);
      })
    },
    update(contact, index) {
      this.updated_status = [false];
      this.contacts = [contact];
      this.updated_status[index] = true;
    },
    contact_update(contact) {
      this.$dialog.confirm('Please confirm to continue').then((dialog) => {
        this.update_status = false;
        putWithParams('/api/put/contacts', contact).then((data) => {
          this.contacts_get();
          this.contacts = [];
        });
      })
    },
    contacts_delete(contact) {
      this.$dialog.confirm('Please confirm to continue').then((dialog) => {
        deleteWithParams('/api/delete/contacts', { data: { id: contact.id } }).then((data) => {
          this.contacts_get();
          this.contacts = [];
        });
      })
    }
  }
}
</script>

<style>
</style>
