<template>
  <q-page>

    <div class="row text-center q-pa-md">

      <div class="col-md-12 col-sm-12 col-xs-12 q-pa-md text-center">
        <q-card class="my-card text-center justify-center content-center">
          <q-item>
            <q-card-section>
              <div class="text-h5 text-center">Rubrique: Produits - Mouvements</div>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

    </div>

    <div class="row justify-center q-pa-md">
      <div class="col-md-12 col-sm-12 col-xs-12 q-mt-md q-pa-md">
        <q-input v-model="date" type="date" @change="products_get()" />
        <br>
        <q-table
          dense title="Produits" :rows="products" :columns="columns"
          :pagination="pagination" :filter="filter" row-key="name">
          <template #top="props">
            <div class="col-7 q-table__title">Liste des produits - <small>{{dateformat(date, 4)}} au {{dateformat(end_date)}}</small> </div>
            <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
            <q-btn
              flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
              class="q-ml-md" @click="props.toggleFullscreen" />
          </template>
          <template #body="props">
            <q-tr :key="props.row.id" :props="props" :class="alerte(props.row)+ 'text-center'">
              <q-td key="id" :props="props" class="no-padding no-margin"> {{props.row.id}} </q-td>
              <q-td key="name" :props="props" class="text-wrap no-padding no-margin" style="width: 20px"> {{props.row.name}} </q-td>
              <q-td key="stock" :props="props" class="no-padding no-margin"> {{props.row.stock}} </q-td>

              <q-td key="a1" :props="props" class="no-padding no-margin"> {{props.row.a1}} </q-td>
              <q-td key="v1" :props="props" class="no-padding no-margin"> {{props.row.v1}} </q-td>
              <q-td key="r1" :props="props" class="no-padding no-margin" :set="r1 = (props.row.stock + props.row.a1) - props.row.v1 ">
                {{ (props.row.stock + props.row.a1) - props.row.v1}}
              </q-td>

              <q-td key="a2" :props="props" class="no-padding no-margin"> {{props.row.a2}} </q-td>
              <q-td key="v2" :props="props" class="no-padding no-margin"> {{props.row.v2}} </q-td>
              <q-td key="r2" :props="props" class="no-padding no-margin" :set="r2 = r1 + props.row.a2 - props.row.v2">
                {{r1 + props.row.a2 - props.row.v2}}
              </q-td>

              <q-td key="a3" :props="props" class="no-padding no-margin"> {{ props.row.a3 }} </q-td>
              <q-td key="v3" :props="props" class="no-padding no-margin"> {{ props.row.v3 }} </q-td>
              <q-td key="r3" :props="props" class="no-padding no-margin" :set="r3 = r2 + props.row.a3 - props.row.v3">
                {{r2 + props.row.a3 - props.row.v3}}
              </q-td>

              <q-td key="a4" :props="props" class="no-padding no-margin"> {{ props.row.a4 }} </q-td>
              <q-td key="v4" :props="props" class="no-padding no-margin"> {{ props.row.v4 }} </q-td>
              <q-td key="r4" :props="props" class="no-padding no-margin" :set="r4 = r3 + props.row.a4 - props.row.v4">
                {{r3 + props.row.a4 - props.row.v4}}
              </q-td>

              <q-td key="a5" :props="props" class="no-padding no-margin"> {{ props.row.a5 }} </q-td>
              <q-td key="v5" :props="props" class="no-padding no-margin"> {{ props.row.v5 }} </q-td>
              <q-td key="r5" :props="props" class="no-padding no-margin" :set="r5 = r4 + props.row.a5 - props.row.v5">
                {{r4 + props.row.a5 - props.row.v5}}
              </q-td>

              <q-td key="a6" :props="props" class="no-padding no-margin"> {{parseInt(props.row.a6)}} </q-td>
              <q-td key="v6" :props="props" class="no-padding no-margin"> {{parseInt(props.row.v6)}} </q-td>
              <q-td key="r6" :props="props" class="no-padding no-margin" :set="r6 = r5 + props.row.a6 - props.row.v6">
                {{r5 + props.row.a6 - props.row.v6}}
              </q-td>

              <q-td key="a7" :props="props" class="no-padding no-margin"> {{ props.row.a7 }} </q-td>
              <q-td key="v7" :props="props" class="no-padding no-margin"> {{ props.row.v7 }} </q-td>
              <q-td key="r7" :props="props" class="no-padding no-margin" :set="r7 = r6 + props.row.a7 - props.row.v7">
                {{r6 + props.row.a7 - props.row.v7}}
              </q-td>
            </q-tr>
          </template>
        </q-table>

      </div>
    </div>
    <br>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService.js';
import basemixin from './basemixin.js';
export default {
  components: {
  },
  mixins: [basemixin],
  data () {
    return {
      end_date: '',
      r1: 0,
      r2: 0,
      r3: 0,
      r4: 0,
      r5: 0,
      r6: 0,
      r7: 0,
      entreprise: {},
      products: [],
      columns: [
        { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
        { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
        { name: 'stock', align: 'left', label: 'Stock', field: 'stock', sortable: true, classes: 'bg-grey-6', headerClasses: 'bg-grey-6 text-white' }
      ],
      filter: '',
      pagination: { sortBy: 'name', descending: false, page: 1, rowsPerPage: 50 }
    }
  },
  created () {
    let date = new Date();
    this.date = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.first = this.convert(new Date(date.getFullYear(), date.getMonth(), 1));
    this.last = this.convert(new Date(date.getFullYear(), date.getMonth() + 1, 0));
    // this.end_date = this.convert(new Date(date.getFullYear(), date.getMonth(), new Date(this.first).getDate() + 7));

    this.products_get();
    this.shop_get();

    for (let i = 1; i <= 7; i++) {
      this.columns.push({ name: 'a' + i, label: 'A' + i, field: 'a' + i, sortable: true });
      this.columns.push({ name: 'v' + i, label: 'V' + i, field: 'v' + i, sortable: true });
      this.columns.push({ name: 'r' + i, label: 'J' + i, field: 'r' + i, sortable: true, classes: 'bg-grey-2 ellipsis', headerClasses: 'bg-grey text-white' });
    }
  },
  methods: {
    shop_get() {
      $httpService.getWithParams('/my/get/shop')
        .then((response) => {
          this.entreprise = response;
        })
    },
    alerte(item) {
      if (item.reste <= item.alert_threshold) {
        return 'bg-red-1';
      }
    },
    intervalDate(start, end) {
      for(var arr=[],dt=new Date(start); dt<=new Date(end); dt.setDate(dt.getDate()+1)) {
        arr.push(new Date(dt).toISOString().slice(0,10));
      }
      return arr;
    },
    products_get () {
      var date = new Date();
      this.end_date = this.convert(new Date(date.getFullYear(), date.getMonth(), new Date(this.date).getDate() + 6));
      this.products = [];
      let a = new Date()
      let b = new Date(a).toISOString().slice(0,10)
      a.setDate(a.getDate()-7)
      a = a.toISOString().slice(0,10)
      let interval = this.intervalDate(a, b)
      $httpService.postWithParams('/my/resume/products/' + this.date, {
        a, b, interval
      }).then((response) => {
        this.products = response;
      })
    }
  }
}
</script>

<style>
.q-table th.sortable {
  cursor: pointer;
  padding: 1px;
}
</style>
