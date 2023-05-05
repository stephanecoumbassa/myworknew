<template>
  <div class="c14 doc-content">
<!--    {{salaire}}-->
    <div>
      <p class="c17"><span class="c19"></span></p>
    </div>
    <p class="c17"><span class="c4"></span></p>
    <a id="t.5bacf7e9c5c500989e37d7ffa5fc1b1ef2e2fceb"></a><a id="t.0"></a>
    <table class="c16">
      <tr class="c13">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Nom &amp; pr&eacute;noms</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{employe.nom}} {{employe.prenom}}</span></p>
        </td>
      </tr>
      <tr class="c13">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Matricule</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{employe.matricule}}</span></p>
        </td>
      </tr>
      <tr class="c8">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Fonction</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{employe.fonction}}</span></p>
        </td>
      </tr>
      <tr class="c13">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">CNPS</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{employe.cnps}}</span></p>
        </td>
      </tr>
      <tr class="c12">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Nombre de parts</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">1</span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Date Embauche</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{employe.datearrivee}}</span></p>
        </td>
      </tr>
      <tr class="c21">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Ann&eacute;e en cours</span></p>
        </td>
        <td class="c7" colspan="3" rowspan="1">
          <p class="c0"><span class="c4">{{bulletin.annee}}</span></p>
        </td>
      </tr>
      <tr class="c20">
        <td class="c15" colspan="2" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c1"><span class="c18">Gains</span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c1"><span class="c18">Prélèvements</span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9 c10" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Salaire de base</span></p>
        </td>
        <td class="c6 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2 c10" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">{{__fn(getValuePrime('salairebase'))}}</span></p>
        </td>
        <td class="c5 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Heure normale</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Heure Sup &agrave;</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">15%</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">50%</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">75%</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">100%</span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">&ndash;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">&ndash;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">&ndash;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">&ndash;</span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Sursalaire</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Cong&eacute; Pay&eacute;</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Prime</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Anciennet&eacute;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">Rendement</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">Gratification</span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">&ndash;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">&ndash;</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">&ndash;</span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9 c11" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Salaire Brut Imposable</span></p>
        </td>
        <td class="c6 c11" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2 c11" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">{{__fn(salaireBrut())}}</span></p>
        </td>
        <td class="c5 c11" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Retenues</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">ITS</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">CRN</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">CNPS</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">IGR</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">CN</span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('its'))}}</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('crn'))}}</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('cnps'))}}</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('igr'))}}</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('cn'))}}</span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Total Retenue</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4">{{__fn(totalRetenue())}}</span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Salaire Net</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4">{{__fn(salaireNet())}}</span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Indemnit&eacute;s</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">Transport</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">Autres</span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('transport'))}}</span></p>
          <p class="c0"><span class="c4"></span></p>
          <p class="c1"><span class="c4">{{__fn(getValuePrime('autres'))}}</span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Pr&ecirc;t</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Acompte</span></p>
        </td>
        <td class="c6" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c5" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
      <tr class="c3">
        <td class="c9 c10" colspan="1" rowspan="1">
          <p class="c1"><span class="c4">Salaire Net &agrave; payer</span></p>
        </td>
        <td class="c6 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
        <td class="c2 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4">{{__fn(salaireNetPayer())}}</span></p>
        </td>
        <td class="c5 c10" colspan="1" rowspan="1">
          <p class="c0"><span class="c4"></span></p>
        </td>
      </tr>
    </table>
    <p class="c17"><span class="c4"></span></p>
  </div>

</template>
<script>
import basemixin from '../pages/basemixin'
export default {
  name: 'DepenseAdd',
  emits: ['reload'],
  props: ['employe', 'salaire', 'bulletin', 'date'],
  mixins: [basemixin],
  data() {
    return {}
  },
  methods: {
    getValuePrime(__key) {
      return this.salaire.find(obj => obj.name === __key)?.montant;
    },
    salaireBrut() {
      return this.getValuePrime('salairebase')
    },
    totalRetenue() {
      return this.getValuePrime('cnps')
              +this.getValuePrime('crn')
              +this.getValuePrime('cn')
              +this.getValuePrime('its')
              +this.getValuePrime('igr')
    },
    salaireNet() {
      return this.salaireBrut() - this.totalRetenue()
    },
    salaireNetPayer() {
      return this.salaireNet() + this.getValuePrime('transport') + this.getValuePrime('autres')
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
.c7 {
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
  width: 434.2pt;
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
  width: 120pt;
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
  width: 96.8pt;
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
  width: 178.5pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c5 {
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
  width: 135.8pt;
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
  width: 275.2pt;
  border-top-color: #000000;
  border-bottom-style: solid;
}
.c17 {
  padding-top: 0pt;
  padding-bottom: 0pt;
  line-height: 1.15;
  orphans: 2;
  widows: 2;
  text-align: left;
  height: 11pt;
}
.c18 {
  color: #000000;
  font-weight: 700;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 7pt;
  font-family: "Arial";
  font-style: normal;
}
.c4 {
  color: #000000;
  font-weight: 400;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 7pt;
  font-family: "Arial";
  font-style: normal;
}
.c19 {
  color: #000000;
  font-weight: 400;
  text-decoration: none;
  vertical-align: baseline;
  font-size: 11pt;
  font-family: "Arial";
  font-style: normal;
}
.c0 {
  padding-top: 0pt;
  padding-bottom: 0pt;
  line-height: 1;
  text-align: left;
  height: 11pt;
}
.c1 {
  padding-top: 0pt;
  padding-bottom: 0pt;
  line-height: 1;
  text-align: left;
}
.c16 {
  margin-left: -16.5pt;
  border-spacing: 0;
  border-collapse: collapse;
  margin-right: auto;
}
.c14 {
  background-color: #ffffff;
  max-width: 510.4pt;
  padding: 42.5pt 42.5pt 42.5pt 42.5pt;
}
.c8 {
  height: 15.2pt;
}
.c3 {
  height: 21pt;
}
.c13 {
  height: 20pt;
}
.c21 {
  height: 0pt;
}
.c10 {
  background-color: #efefef;
}
.c12 {
  height: 3.2pt;
}
.c20 {
  height: 14.2pt;
}
.c11 {
  background-color: #f3f3f3;
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
