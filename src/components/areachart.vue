<template>
  <div>
    <div id="chart" v-if="categories.length >0">
      <!--      {{categories}}-->
      <apexchart :type="type" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
  </div>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts';
export default {
  name: 'AreachartComponent',
  components: {
    apexchart: VueApexCharts
  },
  props: {
    categories: {
      type: Array, default () {
        return [ 'janv', 'fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aou', 'Sep', 'oct', 'nov', 'dec' ]
      }
    },
    series: {
      type: Array, default () { return [
        {
          name: 'Nbre Dons.',
          data: [14, 45, 12, 47, 44, 32, 74, 12, 12]
        }
      ]
      }
    },
    percent: { type: Number, default: 35 },
    type: { type: String, default: 'area' },
    title: { type: String, default: 'Nombre items' },
    titletooltip: { type: String, default: 'Nombre items' },
    color: { type: String, default: '#26a69a' },
    horizontal: { type: Boolean, default: false }
  },
  watch: {
    categories(newQuestion, oldQuestion) {
      console.log("new", JSON.parse(JSON.stringify(newQuestion)))
      console.log("old", JSON.parse(JSON.stringify(oldQuestion)))
      this.chartOptions.xaxis.categories = JSON.parse(JSON.stringify(newQuestion))
    }
  },
  data () {
    return {
      // series: [{
      //     name: 'Nbre Dons.', data: [14, 45, 12, 47, 44, 32, 74, 12, 12]
      // }],
      chartOptions: {
        colors: [this.color, '#E91E63', '#9C27B0'],
        chart: { type: 'area', height: 10 },
        // plotOptions: { bar: { horizontal: this.horizontal, columnWidth: '38%' } },
        dataLabels: { enabled: false },
        plotOptions: {
          bar: {
            horizontal: this.horizontal,
            columnWidth: this.percent+'%',
            endingShape: 'rounded'
          },
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          // categories: this.categories,
          categories: [],
          // categories: this.categories,
          // title: { text: this.title }
        },
        yaxis: { title: { text: this.title } },
        // fill: { opacity: 1, colors: ['#7b9bf4'] },
        fill: { opacity: 1 },
        tooltip: { y: { formatter: function (val) { return val } } }
      }
    }
  }
}
</script>

<style scoped>

</style>
