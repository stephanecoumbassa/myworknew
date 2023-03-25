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

foreach ($vars as $item) {
    if(startsWith($item[1],'varchar')) {
        $inputs2 = $inputs2. " <q-input type='text' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[1] == 'text') {
        $inputs2 = $inputs2. " <q-input type='textarea' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[1] == 'int(11)') {
        $inputs2 = $inputs2. " <q-input type='number' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[1] == 'tinyint(1)') {
        $inputs2 = $inputs2. " <div class='q-gutter-sm'>
                                  <q-radio v-model='$name.$item[0]' :val='1' color='red' label='Pas' />
                                  <q-radio v-model='$name.$item[0]' :val='0' color='teal' label='Avec' /> 
                              </div> \n ";
    }elseif($item[1] == 'date') {
        $inputs2 = $inputs2. " <q-input type='date' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[1] == 'time') {
        $inputs2 = $inputs2. " <q-input type='time' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[1] == 'timestamp') {
        $inputs2 = $inputs2. " <q-input type='datetime-local' v-model='$name.$item[0]' label='$item[0]'/> \n ";
    }elseif($item[4] == 'img') {
        $inputs2 = $inputs2. " <myupload type='input' v-model='$name.$item[0]' label='$item[0]'></myupload> \n ";
    }
//    elseif($item[3] == 'MUL') {
//        $inputs2 = $inputs2. " <q-select v-model='$name.$item[0]' :options='$item[0]' label='$item[0]' map-options emit-value
//                                                  option-value='id' stack-label input-debounce='0' option-label='name'></q-select> \n ";
//    }
}

foreach ($var as $item) {
    $listes = $listes. " <q-td key='$item' :props='props'> {{props.row.$item}} </q-td> \n ";
}

foreach ($var as $item) {
    $columns = $columns. " { name: '$item', align: 'left', label: '{$item}', field: '$item', sortable: true }, \n ";
    $visibles = $visibles."'$item',";
    $shows = $shows."<q-toggle v-model='visiblesColumns' val='$item' label='$item' /> \n ";
}

$liste =  <<<EOL

<template>
    <q-page padding>
    <div v-if="{$dollar}q.screen.gt.xs" class="col">
        $shows
    </div>
    <div class="row justify-center">
      <div class="col-12 q-mt-md">
        <q-btn label="Ajouter" class="q-mb-lg" size="sm" icon="add" color="secondary" v-on:click="medium2 = true" />
        <br><br>
        <q-table title="{$name}s" :rows="{$name}s" :columns="columns" :filter="filter"
                :visible-columns="visiblesColumns" :pagination="pagination" row-key="name">
          <template v-slot:top="props">
            <div class="col-7 q-table__title">Liste des $name</div>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Rechercher" />
            <q-btn flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                   @click="props.toggleFullscreen" class="q-ml-md"></q-btn>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props" :class="alerte(props.row)">
              $listes
              <q-td key="actions" :props="props">
                <q-btn class="q-mr-xs" size="xs" color="primary" v-on:click="update_get(props.row)" icon="edit"></q-btn>
                <q-btn class="q-mr-xs" size="xs" color="secondary" v-on:click="{$name}_delete(props.row.id)" icon="photo"></q-btn>
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
  import {$dollar}httpService from '../boot/httpService';
  import basemixin from './basemixin';
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
        visiblesColumns: [{$visibles}],
        columns: [
            {$columns}
        ],
        filter: '',
        pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 10 }
      }
    },
    components: { },
    mixins: [basemixin],
    created () {
      this.{$name}_get();
      var date = new Date();
      this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
      this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    },
    methods: {
      onSubmit () {
        if (this.accept !== true) {
          this.{$name}_post();
        } else {
          this.{$dollar}q.notify({ color: 'green-4', textColor: 'white', icon: 'fas fa-check-circle', message: 'Submitted' })
        }
      },
      update_get(props) {
        this.{$name} = props;
        this.medium2 = true;
      },
      alerte(item) {
        if (item.amount <= item.alert_threshold) {
          return 'bg-blue-grey-3';
        }
      },
      {$name}_post() {
        this.loading1 = true;
        {$dollar}httpService.postWithParams('/api/post/{$name}', this.{$name})
          .then((response) => {
            this.{$name}_get();
            this.{$dollar}q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
          }).catch(() => { this.loading1 = false; })
      },
      {$name}_get () {
        {$dollar}httpService.getWithParams('/api/get/{$name}')
          .then((response) => {
            this.{$name}s = response;
          })
      },
      {$name}_update (prod) {
        {$dollar}httpService.putWithParams('/api/put/{$name}', prod)
          .then((response) => {
            this.{$name}_get();
            this.{$dollar}q.notify({
              color: 'green', position: 'top', message: response.msg, icon: 'report_problem'
            });
          })
      },
      {$name}_delete (_id) {
        {$dollar}httpService.postWithParams('/api/delete/{$name}', {id: _id})
          .then((response) => {
            this.{$name}_get();
          })
      },
    }
  }
</script>

EOL;


//echo '<pre>';
//echo htmlspecialchars($add);
//echo '</pre>';

file_put_contents("./files/{$name}/{$ucname}.vue", $liste.$add.$scripts);
//file_put_contents("./files/{$name}/{$name}_add.vue", $add);
//file_put_contents("./files/{$name}/{$name}_liste.vue", $liste);
//file_put_contents("./files/{$name}/script.vue", $scripts);

//echo "<code>".$var."</code>" ;