<?php

$inputs = '';
$inputs2 = '';
$listes= '';
$columns= '';
$shows= '';
$visibles= '';
$ucname = ucfirst($NAME);
//$data['titre'] ?? null
foreach ($var as $item) {
    $inputs = $inputs. " <q-input v-model='item.$item' label='$item'/> \n "
    ;
}
//$string = 'The lazy fox jumped over the fence';
//
//dump(str_contains($string, 'lazy'));
//dump($vars);
foreach ($vars as $item) {
    if(startsWith($item[1],'varchar')
        && !str_contains($item[0], 'photo')
        && !str_contains($item[0], 'image')
        && !str_contains($item[0], 'icon') ) {
        $inputs2 = $inputs2. "<q-input dense v-model='$name.$item[0]' label='$item[0]' /> \n ";
    } elseif(startsWith($item[1],'varchar')
        && ( str_contains($item[0], 'photo')
            || str_contains($item[0], 'image')
            || str_contains($item[0], 'icon') )
    ) {
        $inputs2 = $inputs2. "<upload v-model='$name.$item[0]' title='$item[0]' @blurevent=\"setEvent({$dollar}event, '$item[0]')\" /> \n ";
    }elseif($item[1] == 'text') {
        $inputs2 = $inputs2. " <q-input dense type='textarea' v-model='$name.$item[0]' label='$item[0]' /> \n ";
    }elseif($item[1] == 'int(11)') {
        $inputs2 = $inputs2. " <q-input dense type='number' v-model='$name.$item[0]' label='$item[0]' /> \n ";
    }elseif($item[1] == 'tinyint(1)') {
        $inputs2 = $inputs2. " <div class='q-gutter-sm'>
                                  <q-radio v-model='$name.$item[0]' :val='1' color='red' label='Pas' />
                                  <q-radio v-model='$name.$item[0]' :val='0' color='teal' label='Avec' /> 
                              </div> \n ";
    }elseif($item[1] == 'date') {
        $inputs2 = $inputs2. " <q-input dense type='date' v-model='$name.$item[0]' label='$item[0]' /> \n ";
    }elseif($item[1] == 'time') {
        $inputs2 = $inputs2. " <q-input dense type='time' v-model='$name.$item[0]' label='$item[0]' /> \n ";
    }elseif($item[1] == 'timestamp') {
        $inputs2 = $inputs2. " <q-input dense type='datetime-local' v-model='$name.$item[0]' label='$item[0]' /> \n ";
    }elseif($item[4] == 'img') {
        $inputs2 = $inputs2. " <Upload type='input' v-model='$name.$item[0]' label='$item[0]'></Upload> \n ";
    }
//    elseif($item[3] == 'MUL') {
//        $inputs2 = $inputs2. " <q-select v-model='$name.$item[0]' :options='$item[0]' label='$item[0]' map-options emit-value
//                                         dense option-value='id' stack-label input-debounce='0' option-label='name' /> \n ";
//    }
}

//dump($var);
foreach ($var as $item) {
    if( str_contains($item, 'photo')
        || str_contains($item, 'image')
        || str_contains($item, 'icon')
    ) {
        $listes = $listes. " <q-td key='$item' :props='props'> <img v-if='props.row.$item' width='80' height='80' :src=\"uploadurl+'/$name/'+props.row.$item\" :alt='props.row.$item' /> </q-td> \n ";
    }else
        $listes = $listes. " <q-td key='$item' :props='props'> {{props.row.$item}} </q-td> \n ";
}

foreach ($var as $item) {
    $columns = $columns. " { name: '$item', align: 'left', label: '{$item}', field: '$item', sortable: true }, \n ";
    $visibles = $visibles."'$item', ";
    $shows = $shows."<q-toggle v-model='visiblesColumns' val='$item' label='$item' /> \n ";
}

$liste =  <<<EOL

<template>
    <q-page padding>
   
    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="{$name}s" :rows="{$name}s" :columns="columns" :filter="filter"
                :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des $name</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              $listes
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="red" v-on:click="{$name}_delete(props.row.id)" icon="delete"></q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
EOL;

$add =  <<<EOL

    <q-dialog v-model="medium2">
          <q-card style="width: 700px; max-width: 80vw;">
            <q-card-section>
              <div class="text-h6">Ajouter un $NAME</div>
            </q-card-section>
            <q-card-section>
              <q-form  @submit="onSubmit" class="q-gutter-md">
                <div class="row">
                  <div class="col-12">
                   $inputs2
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                   <q-btn color="primary" label="Valider" type="submit" />
                  </div>
                </div>
              </q-form>
            </q-card-section>
            <q-card-actions align="right" class="bg-white text-teal">
              <q-btn flat label="Fermer" v-close-popup />
            </q-card-actions>
          </q-card>
    </q-dialog>

    </q-page>
</template>

EOL;

$scripts =  <<<EOL

<script>
  import {$dollar}httpService from '../services/httpService'
  import basemixin from '../services/basemixin'
  export default {
    data () {
      return {
        {$name}_id: 1,
        loading1: false,
        red: '#6d1412',
        first: null,
        last: null,
        medium: false,
        medium2: false,
        maximizedToggle: true,
        name: null,
        image: null,
        {$name}: {},
        {$name}s: [],
        columns: [
            {$columns}
            { name: 'actions', align: 'left', label: 'Actions' }
        ],
        filter: '',
        pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
      }
    },
    mixins: [basemixin],
    created () {
      this.{$name}_get()
      const date = new Date()
      this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1))
      this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0))
    },
    methods: {
      update_get (props) {
        this.{$name} = props
        this.medium2 = true
      },
      setEvent (payload, _name) {
          this.{$name}[_name] = payload
      },
      handleFile (_name) {
          this.{$name}[_name] = this.{$dollar}refs[_name].files[0]
      },
      {$name}_get () {
        {$dollar}httpService.getApi('/api/get/{$name}')
          .then((response) => {
            this.{$name}s = response
          })
      },
      onSubmit () {
          if (this.{$name}.id) {
            this.{$name}_update()
          } else {
            this.{$name}_post()
          }
        },
      {$name}_post () {
        this.showLoading()
        {$dollar}httpService.postApi('/api/post/{$name}', this.{$name})
          .then((response) => {
              this.{$name} = {}
            this.{$name}_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      },
      {$name}_update () {
          this.showLoading()
        {$dollar}httpService.putApi('/api/put/{$name}', this.{$name})
          .then((response) => {
            this.{$name}_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      },
      {$name}_delete (_id) {
          this.showLoading()
        {$dollar}httpService.deleteApi('/api/delete/{$name}/' + _id)
          .then((response) => {
            this.{$name}_get()
            this.showAlert(response.msg, 'secondary')
            this.hideLoading()
          }).catch(() => { this.hideLoading() })
      }
    }
  }
</script>

EOL;

$services =  <<<EOL

import {BaseApi} from "src/services/api/BaseApi";
const TABLE = '{$name}';
class {$snake}Api extends BaseApi{

  constructor() {
    super();
  }

  static async get() {
    return await super.get(TABLE)
  }
  
  static async getId(id) {
    return await super.getId(TABLE, id)
  }

  static async search(params) {
    return await super.search(TABLE, params)
  }
  
  static async post(params) {
    return await super.post(TABLE, params)
  }

  static async update(params) {
    return await super.update(TABLE, params)
  }

  static async delete(id) {
    return await super.delete(TABLE, id)
  }

}

export {{$snake}Api};



EOL;


file_put_contents("./files/{$name}/{$snake}Page.vue", $liste.$add.$scripts);
file_put_contents("./files/SERVICES/{$snake}Api.js", $services);
