<template>
  <apexchart type="line" height="350" :options="chartOptions" :series="series"></apexchart>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts';
export default {
  name: 'MixedChart',
  components: {
    apexchart: VueApexCharts,
  },
  props: {
    series: {
      type: Array, default () { return [
        {
          name: 'Nbre Dons.',
          data: []
        }
      ]
      }
    },
    title: { type: String, default: 'Nombre items' },
    color: { type: String, default: '#26a69a' },
    horizontal: { type: Boolean, default: false }
  },
  data () {
    return  {
      chartOptions: {
        colors: [this.color, '#E91E63', '#ff960c'],
        chart: {
          height: 350,
          type: 'line',
          stacked: false
        },
        dataLabels: { enabled: false },
        plotOptions: {
          bar: {
            horizontal: this.horizontal,
            columnWidth: '35%',
            endingShape: 'rounded'
          },
        },
        stroke: {
          width: [1, 1, 4]
        },
        xaxis: {
          categories: [ 'janv', 'fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aou', 'Sep', 'oct', 'nov', 'dec' ],
        },
        yaxis: { title: { text: this.title } },
        tooltip: {
          fixed: {
            enabled: false,
            position: 'topLeft',
            offsetY: 20,
            offsetX: 40
          },
        },
        legend: {
          horizontalAlign: 'left',
          offsetX: 40
        }
      },
    }
  }
};
</script>
