<?php

$inputs = '';
$inputs2 = '';
$listes= '';
$columns= '';
$ucname = ucfirst($NAME);

$liste_components =  <<<EOL

<template>
    <div>
        <q-select filled v-model="model" use-input map-options emit-value option-value="id" option-label="name" label="Listes des {$name}s"
         :options="{$name}s" @change="emitCustomEvent" v-on:update:model-value="emitCustomEvent">
        <template v-slot:no-option>
          <q-item>
            <q-item-section class="text-grey">
              Pas de resultats
            </q-item-section>
          </q-item>
        </template>
      </q-select>
    </div>
</template>

<script>
import {$dollar}httpService from '../boot/httpService';
import basemixin from './basemixin';
export default {
    // name: 'ComponentName',
    data () {
        return {
            model: null,
            {$name}_id: 1,
            loading1: false,
            {$name}: {},
            {$name}s: [],
            medium: false,
            filter: ''
        }
    },
    components: { },
    mixins: [basemixin],
    props: {
        value: Number
    },
    model: {
        event: 'blur'
    },
    watch: {
        value: {
            immediate: true,
            handler (val, oldVal) {
                this.model = val
            }
        }
    },
    created () {
      this.{$name}_get();
    },
    methods: {
      emitCustomEvent() {
        this.{$dollar}emit('blurevent', this.model)
      },
      {$name}_get () {
        {$dollar}httpService.getWithParams('/api/get/{$name}')
          .then((response) => {
            this.{$name}s = response;
          })
      },
    }
}
</script>

<style>
</style>


EOL;


//echo '<pre>';
//echo htmlspecialchars($add);
//echo '</pre>';

file_put_contents("./files/{$name}/{$ucname}Component.vue", $liste_components);
//file_put_contents("./files/{$name}/{$name}_add.vue", $add);
//file_put_contents("./files/{$name}/{$name}_liste.vue", $liste);
//file_put_contents("./files/{$name}/script.vue", $scripts);

//echo "<code>".$var."</code>" ;