<template>
  <!--  <q-page padding>-->

  <div style="margin: auto;">

    <vue3-html2pdf
      ref="html2Pdf"
      :show-layout="true"
      :float-layout="false"
      :enable-download="false"
      :preview-modal="true"
      :paginate-elements-by-height="1400"
      filename="facture"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="landscape"
      style="margin: 0 auto"
      @before-download="beforeDownload($event)"
    >
      <template #pdf-content>
        <div class="c16 doc-content">
          <p class="c17"><span class="c3">Tableau Pr&eacute;visionnel </span></p>
          <div v-if="!printStatus" class="col-3 text-right">
            <q-btn size="xs" color="grey" @click="generateReport()">PDF</q-btn>
          </div>
          <p class="c17 c7"></p>
          <a id="t.3ca71f02c8f1a8b0e5731afe0d9086b95810928d"></a><a id="t.0"></a>
          <table class="c4">
            <tr class="c20">
              <td class="c8 c10" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Descrp</span></p>
              </td>
              <td class="c2 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Status</span></p>
              </td>
              <td class="c15 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Prix Unit</span></p>
              </td>
              <td class="c18 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Prix HT</span></p>
              </td>
              <td class="c11 c8" colspan="1" rowspan="1">
                <p class="c1">
                        <span class="c0">
                            Qté <br />
                            Totale
                        </span>
                </p>
              </td>
              <td class="c19 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Qt&eacute; D&eacute;j&agrave; Livr&eacute;</span></p>
              </td>
              <td class="c8 c13" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Qt&eacute; Reste</span></p>
              </td>
              <td class="c8 c14" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Qt&eacute; Previs&deg;</span></p>
              </td>
              <td class="c9 c8" colspan="1" rowspan="1">
                <p class="c1">
                        <span class="c0">
                            Date<br />
                            Previs&deg;
                        </span>
                </p>
              </td>
              <td class="c6 c8" colspan="1" rowspan="1">
                <p class="c1">
                        <span class="c0">
                            Qt&eacute; <br />
                            effec
                        </span>
                </p>
              </td>
              <td class="c2 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Date</span></p>
                <p class="c1"><span class="c0">effect</span></p>
              </td>
              <td class="c12 c8" colspan="1" rowspan="1">
                <p class="c1"><span class="c0">Observations</span></p>
              </td>
            </tr>
            <tr class="c5" v-for="(prevision, index) in previsions" :key="index">
              <td class="c10" colspan="1" rowspan="1">
                <p class="c1 c7">
                  {{prevision?.titre}}<br>
                  {{prevision?.datedebut}}<br>
                  {{prevision?.datefin}}
                </p>
              </td>
              <td class="c2" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.status}}</p>
              </td>
              <td class="c15" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.prix_unitaire}}</p>
              </td>
              <td class="c18" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.montant_ht}}</p>
              </td>
              <td class="c11" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.qte}}</p>
              </td>
              <td class="c19" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.livree}}</p>
              </td>
              <td class="c13" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.reste}}</p>
              </td>
              <td class="c14" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.qte_prevision}}</p>
              </td>
              <td class="c9" colspan="1" rowspan="1">
                <p class="c1 c7" v-if="prevision?.date_prevision">{{prevision?.date_prevision?.substring(5)}}</p>
              </td>
              <td class="c6" colspan="1" rowspan="1">
                <p class="c1 c7">{{prevision?.qte_effective}}</p>
              </td>
              <td class="c2" colspan="1" rowspan="1">
                <p class="c1 c7" v-if="prevision?.date_effective">{{prevision?.date_effective.substring(5)}}</p>
              </td>
              <td class="c12" colspan="1" rowspan="1">
                <p class="c1 c7"></p>
              </td>
            </tr>
          </table>
          <p class="c17 c7"></p>
        </div>
      </template>
    </vue3-html2pdf>

  </div>

</template>

<script>
import basemixin from '../pages/basemixin';
import html2pdf from 'html2pdf.js';
import Vue3Html2pdf from 'vue3-html2pdf';
export default {
  name: 'previsionComponent',
  components: {
    // VueHtml2pdf,
    Vue3Html2pdf
  },
  mixins: [basemixin],
  props: {
    previsions: { type: Array, default () { return [] } },
    date: { type: String, default: null },
  },
  data () {
    return {
    }
  },
  created () {
    console.log(this.previsions);
  },
  methods: {
    async beforeDownload ({ html2pdf, options, pdfContent }) {
      console.log(html2pdf, options, pdfContent)
      // await html2pdf().set(options).from(pdfContent).toPdf().get('pdf').then((pdf) => {
      //   const totalPages = pdf.internal.getNumberOfPages()
      //   for (let i = 1; i <= totalPages; i++) {
      //     pdf.setPage(i)
      //     pdf.setFontSize(10)
      //     pdf.setTextColor(150)
      //     pdf.text('Page ' + i + ' of ' + totalPages, (pdf.internal.pageSize.getWidth() * 0.88), (pdf.internal.pageSize.getHeight() - 0.3))
      //   }
      // }).save()
    },
    generateReport () {
      this.printStatus = true;
      this.$refs.html2Pdf.generatePdf();
      setTimeout(() => {this.printStatus = false;}, 1000)
    }
  }
}
</script>

<style scoped>
ol {
  margin: 0;
  padding: 0;
}
table td,
table th {
  padding: 0;
}
.c14 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 48pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c12 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 142.5pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c11 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 45pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c10 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 114.8pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c13 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 43.5pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c9 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 47.2pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c15 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 54.8pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c19 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 52.5pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c2 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 44.2pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c18 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 68.2pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c6 {
  border-right-style: solid;
  padding: 5pt 5pt 5pt 5pt;
  border-bottom-color: #000000;
  border-top-width: 1pt;
  border-right-width: 1pt;
  border-left-color: #000000;
  vertical-align: top;
  border-right-color: #000000;
  border-left-width: 1pt;
  border-top-style: solid;
  border-left-style: solid;
  border-bottom-width: 1pt;
  width: 50.2pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c3 {
  color: #000000;
  font-weight: 400;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 14pt;
  font-family: "Arial";
  font-style: normal;
}
.c0 {
  color: #000000;
  font-weight: 400;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 10pt;
  font-family: "Arial";
  font-style: normal;
}
.c17 {
  padding-top: 0pt;
  padding-bottom: 0pt;
  line-height: 1.15;
  orphans: 2;
  widows: 2;
  text-align: left;
}
.c4 {
  border-spacing: 0;
  border-collapse: collapse;
  margin-right: auto;
  width: 26.7cm;
}
.c1 {
  padding-top: 0pt;
  padding-bottom: 0pt;
  line-height: 1;
  text-align: left;
}
.c16 {
  background-color: #ffffff;
  max-width: 908.1pt;
  padding: 43.7pt 43.7pt 43.7pt 43.7pt;
}
.c20 {
  height: 0pt;
}
.c8 {
  background-color: #efefef;
}
.c5 {
  height: 47.8pt;
}
.c7 {
  height: 09pt;
}
.title {
  padding-top: 0pt;
  color: #000000;
  font-size: 26pt;
  padding-bottom: 3pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
.subtitle {
  padding-top: 0pt;
  color: #666666;
  font-size: 15pt;
  padding-bottom: 16pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
li {
  color: #000000;
  font-size: 11pt;
  font-family: "Arial";
}
p {
  margin: 0;
  color: #000000;
  font-size: 11pt;
  font-family: "Arial";
}
h1 {
  padding-top: 20pt;
  color: #000000;
  font-size: 20pt;
  padding-bottom: 6pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
h2 {
  padding-top: 18pt;
  color: #000000;
  font-size: 16pt;
  padding-bottom: 6pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
h3 {
  padding-top: 16pt;
  color: #434343;
  font-size: 14pt;
  padding-bottom: 4pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
h4 {
  padding-top: 14pt;
  color: #666666;
  font-size: 12pt;
  padding-bottom: 4pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
h5 {
  padding-top: 12pt;
  color: #666666;
  font-size: 11pt;
  padding-bottom: 4pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  orphans: 2;
  widows: 2;
  text-align: left;
}
h6 {
  padding-top: 12pt;
  color: #666666;
  font-size: 11pt;
  padding-bottom: 4pt;
  font-family: "Arial";
  line-height: 1.15;
  page-break-after: avoid;
  font-style: italic;
  orphans: 2;
  widows: 2;
  text-align: left;
}
</style>
