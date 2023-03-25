<?php

$content = <<<EOL

{% extends 'base.html.twig' %}

{% block title %}{$name} !{% endblock %}

{% block css %}
    <link href="{{ asset('css/mystyle.css') }}" rel="stylesheet">
    <link href="{{ asset('css/leaflet.css') }}" rel="stylesheet">
{% endblock %}

{% block body %}

    {#{% endif %}#}
    <main class="main" id="app">
        {#<!-- Breadcrumb-->#}
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">
                <a href="#">Admin</a>
            </li>
            <li class="breadcrumb-item active">Tableau de bord</li>
            <!-- Breadcrumb Menu-->
            <li class="breadcrumb-menu d-md-down-none">
                <div class="btn-group" role="group" aria-label="Button group">
                    <a class="btn" href="#">
                        <i class="icon-speech"></i>
                    </a>
                    <a class="btn" href="./">
                        <i class="icon-graph"></i>  Tableau de bord</a>
                    <a class="btn" href="#">
                        <i class="icon-settings"></i>  $NAME </a>
                </div>
            </li>
        </ol>
        <div class="container-fluid" id="app">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col">

                        <div class="card-body">
                            {# {{ app.user.pseudo }} #}
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row float-left">
                                            <div class="col-lg-9 col-md-8 col-sm-12">

                                                <button class="btn-change" type="button" data-toggle="modal" data-target="#{$name}Modal" v-on:click="{$name}s_reset()">
                                                    <span><i class="fa fa-plus"></i> </span> Ajouter
                                                </button>

                                                <mygenerate :idligne="1" :typerubrique="$typerubrique" :pe="true"></mygenerate>

                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-12">
                                                <form method="get">
                                                    <div class="input-group">
                                                        <input type="search" name="search" v-model="name" placeholder="Rechercher" class="form-control bg-light">
                                                        <div class="input-group-append">
                                                            <button id="button-addon1" type="button" class="btn btn-danger" v-on:click="{$name}_search()">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div>
                                            <table class="table table-responsive-sm table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>$search</th>
                                                    {# <th>Cat</th>#}
                                                    {# <th>Type</th>#}
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>

                                                <tbody id="tb">
                                                <tr v-for="item in {$name}_list" :id="'ent'+item.id">
                                                    <td> $dollar{item.id} </td>
                                                    <td> $dollar{item.$search} </td>
                                                    {#<td> $dollar{item.cat} </td>#}
                                                    {# <td>
                                                        <span v-if="item.type{$name} == 'd'" class="badge badge-primary">{$name}</span>
                                                        <span v-if="item.type{$name} == 'p'" class="badge badge-secondary">Pas {$name}</span>
                                                    </td>#}
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-secondary dropdown-toggle" :id="item" :value="item.id" v-on:click="f_get(item)" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#{$name}UpdateModal">Modifier</a>
                                                                <a class="dropdown-item primary" v-on:click="{$name}_delete(item.id)">Supprimer</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>

                                            </table>
                                            <mypagination v-model="{$name}_list" :lists="{$name}_list_initiales"></mypagination>
                                            {{ include('admin/{$name}/__add.html.twig') }}
                                            {{ include('admin/{$name}/__update.html.twig') }}
                                            <mype :idligne="parseInt({$name}.id)" :typerubrique="$typerubrique"></mype>
                                            {#{{ include('admin/{$name}s_cles/__{$name}s_logs.html.twig') }}#}
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

{% endblock %}


{% block javascripts %}

    <script>

        var app = new Vue({
            delimiters: ['{$dollar}{', '}'],
            el: '#app',
            data: {
                {$name}s_list: '',
                {$name}_list_initiales: [],
                {$name}_list: [],
                {$name}: {status:1, alert: null},
                {$name}_id: null,
                image: null,
                photos_list: '',
                categories_list: '',
                categories_sous_list: '',
                logs: '',
                photos_name: '',
                photos: {"one":"", "two":"", "three":"", "four":""},
                items: {},
                selectedLine: {},
                content: null,
                name: null,
                config: {},
                configs: {
                    basic: {
                        autogrow: false
                    },
                    advanced: {
                        btns: [
                            ['viewHTML'], [ 'undo', 'redo'], ['formatting'], [ 'strong', 'em', 'del' ],
                            ['superscript', 'subscript'],['link'],['insertImage'],
                            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                            ['unorderedList', 'orderedList'],['horizontalRule'],['removeformat'],
                            ['fullscreen'],['foreColor', 'backColor'],['table']
                        ]
                    }
                }
            },
            components: {
                'mype': httpVueLoader("{{ asset('js/components/mype.vue') }}"),
                'mypagination': httpVueLoader("{{ asset('js/components/mypagination.vue') }}"),
                'myupload': httpVueLoader("{{ asset('js/components/myupload.vue') }}"),
                'mygenerate': httpVueLoader("{{ asset('js/components/mygenerate.vue') }}"),
                'mygenerateasync': httpVueLoader("{{ asset('js/components/mygenerate_async.vue') }}"),
            },
            created: function () {
                this.{$name}_get();
            },
            methods: {
                {$name}_get(){
                    getWithParams("/api/get/{$name}").then(data => {
                        this.{$name}_list_initiales = data;
                        this.{$name}_list = this.{$name}_list_initiales.slice(0, 20);
                    });
                },
                {$name}_search(){
                    getWithParams("/api/get/{$name}_search", {name: this.name}).then(data => {
                        this.{$name}_list = data;
                    });
                },
                f_get(item){
                    this.{$name} = item;
                },
                {$name}s_reset(){
                    this.{$name} = {status:1};
                },
                {$name}_add(){
                    postWithParams("/api/post/{$name}", this.{$name}).then(data => {
                        console.log(data);
                        this.{$name}_get();
                    });
                },
                {$name}_delete(_id){
                    deleteWithParams("/api/delete/{$name}/"+_id).then(data => {
                        console.log(data);
                    });
                    this.{$name}_get();
                },
                {$name}_update(){
                    putWithParams("/api/put/{$name}", this.{$name}).then(data => {
                        this.{$name}_get();
                        console.log(data);
                    })
                },
                select(_id) {
                    this.{$name}_id = _id
                }
            }

        })
    </script>

{% endblock %}

EOL;


//echo '<pre>';
//    echo htmlspecialchars($content);
//echo '</pre>';

file_put_contents("./files/{$name}/index.html.twig", $content);
//file_put_contents("./files/{$name}/{$name}/index.html.twig", $content);
file_put_contents("./files/templates/{$name}/index.html.twig", $content);