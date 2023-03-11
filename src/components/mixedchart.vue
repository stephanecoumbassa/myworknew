<template>
  <apexchart type="line" height="350" :options="chartOptions" :series="series"></apexchart>
</template>

<script>
import VueApexCharts from 'vue3-apexcharts';
export default {
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
    type: { type: String, default: 'area' },
    title: { type: String, default: 'Nombre items' },
    titletooltip: { type: String, default: 'Nombre items' },
    color: { type: String, default: '#26a69a' },
    horizontal: { type: Boolean, default: false }
  },
  data () {
    return  {
      // series: [{
      //   name: 'Vendu',
      //   type: 'column',
      //   data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
      // }, {
      //   name: 'Encaissé',
      //   type: 'column',
      //   data: [1.1, 3, 3.1, 4, 4.1, 4.9, 6.5, 8.5]
      // }, {
      //   name: 'prévision',
      //   type: 'line',
      //   data: [20, 29, 37, 36, 44, 45, 50, 58]
      // }],
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
        // title: {
        //   text: 'XYZ - Stock Analysis (2009 - 2016)',
        //   align: 'left',
        //   offsetX: 110
        // },
        xaxis: {
          // categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016],
          categories: [ 'janv', 'fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jul', 'Aou', 'Sep', 'oct', 'nov', 'dec' ],
        },
        yaxis: { title: { text: this.title } },
        // yaxis: [
        //   {
        // axisTicks: {
        //   show: false,
        // },
        // axisBorder: {
        //   show: false,
        //   color: '#008FFB'
        // },
        // labels: {
        //   style: {
        //     colors: '#008FFB',
        //   }
        // },
        // title: {
        //   text: "Income (thousand crores)",
        //   style: {
        //     color: '#008FFB',
        //   }
        // },
        // tooltip: {
        //   enabled: true
        // }
        // },
        // {
        // tooltip: {
        //   enabled: true
        // }
        // seriesName: 'Income',
        // opposite: true,
        // axisTicks: {
        //   show: false,
        // },
        // axisBorder: {
        //   show: false,
        //   color: '#00E396'
        // },
        // labels: {
        //   style: {
        //     colors: '#00E396',
        //   }
        // },
        // title: {
        //   text: "Operating Cashflow (thousand crores)",
        //   style: {
        //     color: '#00E396',
        //   }
        // },
        // },
        // {
        // tooltip: {
        //   enabled: true
        // },
        // seriesName: 'Revenue',
        // opposite: false,
        // axisTicks: {
        //   show: false,
        // },
        // axisBorder: {
        //   show: false,
        //   color: '#FEB019'
        // },
        // labels: {
        //   style: {
        //     colors: '#FEB019',
        //   },
        // },
        // title: {
        //   text: "Revenue (thousand crores)",
        //   style: {
        //     color: '#FEB019',
        //   }
        // }
        // },
        // ],
        tooltip: {
          fixed: {
            enabled: false,
            position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
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
