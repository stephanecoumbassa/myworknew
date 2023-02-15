<template>
  <div>
    <div id="chart">
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
    series: {
      type: Array, default () { return [
        {
          name: 'Nbre Dons.',
          data: [14, 45, 12, 47, 44, 32, 74, 12, 12]
        }
      ]
      }
    },
    type: { type: String, default: 'area' },
    title: { type: String, default: 'Nombre items' },
    titletooltip: { type: String, default: 'Nombre items' },
    color: { type: String, default: '#26a69a' },
    horizontal: { type: Boolean, default: false }
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
            columnWidth: '35%',
            endingShape: 'rounded'
          },
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: [ 'janv', 'fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aou', 'Sep', 'oct', 'nov', 'dec' ],
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
