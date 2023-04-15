<template>
  <!--  <q-page padding>-->

  <div>

    <vue3-html2pdf
      :show-layout="true"
      :float-layout="false"
      :enable-download="false"
      :preview-modal="true"
      :paginate-elements-by-height="1400"
      filename="facture"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="portrait"
      pdf-content-width="800px"
      @beforeDownload="beforeDownload($event)"
      ref="html2Pdf">

      <!--      <section ref="pdf-content">-->
      <template  v-slot:pdf-content>
        <div id="a4" size="A4">
          <h1 v-if="typeselected==='proforma'" style="position: absolute;top: 20%;left: 0%;color: #ff000045;
                    letter-spacing: 30px;transform: rotate(318deg);font-weight: bolder;z-index: 9;">PROFORMA</h1>
          <h1 v-if="typeselected==='devis'" style="position: absolute;top: 20%;left: 10%;color: #ff000045;
                    letter-spacing: 40px;transform: rotate(318deg);font-weight: bolder;
                        font-size: 150px; ;z-index: 9">DEVIS</h1>
          <h1 v-if="typeselected==='facture'" style="position: absolute;top: 25%;left: 0%;color: #ff000045;
                  letter-spacing: 40px;transform: rotate(318deg);font-weight: bolder;
                      font-size: 120px;z-index: 9">FACTURE</h1>
          <h1 v-if="typeselected==='bl'" style="position: absolute;top: 20%;left: 0%;color: #ff000045;
                       transform: rotate(318deg);font-weight: bolder;font-size: 90px;z-index: 9;">BON DE LIVRAISON</h1>
          <header>
            <div class="row no-padding no-margin print-hide title">
              <div class="col-3">
                <img src="~assets/fmmi-logo.jpeg" style="height: 70px; object-fit: cover"/>
              </div>
              <div class="col-3"></div>
              <div class="col-3" v-if="!printStatus">
                <span>
                  <q-select dense v-model="typeselected" :options="['bl', 'facture', 'devis', 'proforma']" />
                </span>
              </div>
              <div class="col-3 text-right" v-if="!printStatus">
                <button v-on:click="generateReport()">PDF</button>
<!--                &nbsp;-->
<!--                <button v-on:click="mail()">Mail</button>-->
              </div>
            </div>
          </header>
          <div class="container" style="position: relative; top: 100px; contenteditable: true;
            padding-right: 1cm; padding-left: 1cm;">

            <div class="facture">
              <div class="logo">
              </div>
              <div class="fact-header">
                <div class="col">
                  <div class="card">
                    <div class="card-header"> {{myentreprise.name}} </div>
                    <div class="card-body">
                      <p>Tel: {{myentreprise.telephone}}</p>
                      <p>Email: {{myentreprise.email}}</p>
                      <p>CC N°:</p>
                      <p>RCCM:</p>
                      <p>BL: <input class="no-border" /> </p>
                      <p>BC: <input class="no-border" /> </p>
                    </div>
                  </div>
                </div>
                <div class="info-right">
                  <div class="title h4">Facture #: <input class="no-border" v-model="facturenumero" /></div>
                  <div class="title h4">{{client?.fullname}}</div>
                  <div class="title h4">{{client?.telephone_code}} {{client?.telephone}}</div>
                  <div class="title h4">{{client?.email}}</div>
                  <p>Date de creation <span>{{date}}</span></p>
                  <div class="resum-total">
                    <p>A payer (CFA) <span> {{numerique(total)}}</span></p>
                  </div>
                </div>
              </div>
              <div class="container-fluid" style="margin-top: -50px">
                <table class="table table-bordered" style="width: 100%; border: 1px solid">
                  <thead>
                  <tr>
                    <th align="left">Libellé</th>
                    <th align="left">Quantité</th>
                    <th align="left">PU</th>
                    <th align="left">TVA</th>
                    <th align="right">Montant</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(product, index) in products" :key="index">
                    <th align="left"> {{product.name}}</th>
                    <td align="left">{{numerique(product.quantity)}}</td>
                    <td align="left">{{numerique(product.price)}}</td>
                    <td align="left">{{numerique(product.tva)}}%</td>
                    <td align="right">{{numerique(
                      (product.quantity * product.price) + (product.quantity * product.price * product.tva /100)
                    )}}</td>
<!--                    <td align="right">{{numerique(product.total)}}</td>-->
                  </tr>
                  </tbody>
                </table>
                <br>
                <div class="total-Ht">
                  <div class="total-ht">Total HT</div> <div class="montant"> {{numerique(totalHt)}} </div>
                  <div class="reduc">TVA</div> <div class="montant"> 18%</div>
                  <!--              <div class="tva">Tva</div> <div class="montant">0</div>-->
                  <div class="reduc" v-if="remise">Remise</div> <div v-if="remise" class="montant"> {{numerique(remise)}}</div>
                  <div class="avance" v-if="acompte"> Acompte</div> <div v-if="acompte" class="montant">0</div>
                  <div class="total-ttc text-r">Total TTC</div> <div class="montant fin text-right">{{numerique(total)}} <span>CFA</span></div>
                </div>
                <p class="nb">Condition de paiement: <span class="nb-text">30 % à la commande, paiement à reception de facture</span></p>
                <p class="nb">Mode de paiement: <span class="nb-text">Par virement ou chèque</span></p>
              </div>
            </div>

          </div>
          <footer contenteditable="true" class="text-center">
            SARL au capital de 5 000 000 Fcfa- Yopougon quartier maroc- 10 bp 1022 abj 10 <br>
            RCCM : CI-ABJ-2019-B-19674 Tél : 07360841/02626152/05051912
          </footer>

        </div>
      </template>
      <!--      </section>-->
    </vue3-html2pdf>


  </div>

  <!--  </q-page>-->
</template>

<script>
import basemixin from '../pages/basemixin';
import Vue3Html2pdf from 'vue3-html2pdf'
import jspdf from 'jspdf'
export default {
  name: 'FactureComponent',
  components: {
    // VueHtml2pdf,
    Vue3Html2pdf
  },
  mixins: [basemixin],
  data () {
    return {
      date: '',
      typeselected: 'FACTURE',
      printStatus: false,
      facturenumero: ''
      // series: [{
      //     name: 'Nbre Dons.', data: [14, 45, 12, 47, 44, 32, 74, 12, 12]
      // }],
    }
  },
  props: {
    products: { type: Array, default () { return [{ name: 'Nbre Dons.', dateposted: '', data: [ ] }] } },
    myentreprise: { type: Object, default () { return { name: 'Fmmi' } } },
    client: { type: Object },
    fournisseur: { type: Object },
    color: { type: String, default: '#26a69a' },
    name: { type: String, default: 'Facture' },
    facturename: { type: String },
    facturenum: { type: String },
    type: { type: String, default: null},
  },
  computed: {
    total() {
      let sum = 0
      this.products.forEach((product) => {
        sum = sum + (product.prix_unitaire * product.quantity + ( product.prix_unitaire * product.quantity * product.tva /100 ))
      });
      return sum
    },
    totalHt() {
      let sum = 0
      this.products.forEach((product) => {
        sum = sum + (product.prix_unitaire * product.quantity)
      });
      return sum
    },
    remise() {
      return this.products.reduce((product, item) => product + (item.remise_totale * 1), 0);
    },
    acompte() {
      return this.products.reduce((product, item) => product + (item.remise_totale * 1), 0);
    }
  },
  created () {
    let date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth(), date.getDate()), 4);

    setTimeout(() => {
      if(this.type) { this.typeselected = this.type }
      if(this.facturenum) { this.facturenumero = this.facturenum }
      console.log(this.type)
      console.log(this.typeselected)
    }, 3000)
  },
  watch: {
  },
  methods: {
    // imprimer() {
    //   window.print();
    // },
    // download() {
    //     if (confirm('Voulez vous telecharger')) {
    //         htmlToImage.toJpeg(document.getElementById('a4'), { quality: 0.95 })
    //             .then((dataUrl) => {
    //                 var link = document.createElement('a');
    //                 link.download = 'facture.jpeg';
    //                 link.target = '_blank';
    //                 link.href = dataUrl;
    //                 link.click();
    //             });
    //     }
    // },
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
    },
    mail () {
      this.$refs.html2Pdf.generatePdf();
    }
  }
}
</script>

<style scoped>

.logo {
  text-align: right;
}


.logo img{
  margin: 5% 0 3%;

}
.card-body p{
  margin: 0;
}

.fact-header{
  display: flex;
  justify-content: space-around;
}

/* info right de la facture */

header .title{
  z-index: 1;
  color: #fc9700 ;
  font-weight: bold;
  font-size: 1rem;
  margin-bottom: 1.5%;
}

/* resumé du net à payer  */

.resum-total{
  background-color: #fc9700;
  color: #fff;
  padding: 3%;
  width: 200px;
  height: 35px;
  font-weight: bolder;
}

/* table */

table{
  margin-top: 15%;
}

/* facture content  */
.total-Ht div{border-bottom: 1px solid black;}

.total-Ht{
  display: grid;
  grid-template-columns: 3fr 1fr;
  /*grid-template-rows: 10px 10px 10px 10px 10px;*/
}

.total-Ht .montant{
  text-align: right;
  margin-top: 5%;
  padding-right: 1px;
}
/* montant ttc */
.total-Ht .fin{
  font-weight: bold;
  color: #fc9700;
}

/* la remarque nb */
.nb{
  margin-top: 5%;
}
.nb-text{
  color:#fc9700;
}

/* facture content  */

body {
  background: rgb(204,204,204);
}
#a4 {
  position: relative;
  background: white;
  display: block;
  /*margin: 0 auto;*/
  /*margin-bottom: 0.5cm;*/
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
#a4[size="A4"] {
  width: 21cm;
  height: 28.7cm;
  /*margin-top: 2%;*/

}
#a4[size="A4"][layout="portrait"] {
  width: 28.7cm;
  height: 21cm;
}
#a4[size="A3"] {
  width: 29.7cm;
  height: 42cm;
}
#a4[size="A3"][layout="portrait"] {
  width: 42cm;
  height: 29.7cm;
}
#a4[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}
#a4[size="A5"][layout="portrait"] {
  width: 21cm;
  height: 14.8cm;
}

header{
  position: absolute;
  left: 0;
  right: 0;
  background-color: #ffffff;
  border-top: 0.5px solid #cfcfd3;
  /*border-bottom: 1px solid #67666a;*/
  padding-right: 1cm;
  padding-left: 1cm;
}

footer {
  position: absolute;
  left: 0;
  right: 0;
  background-color: #ffffff;
  border-top: 0.5px solid #cfcfd3;
  padding-right: 1.5cm;
  padding-left: 1.5cm;
}
/* header:after{
  content: "Header";
}
footer:after{
  content: "Footer";
} */

header {
  top: 0;
  padding-top: 5mm;
  padding-bottom: 3mm;
}
footer {
  bottom: 0;
  color: #000;
  padding-top: 1mm;
  padding-bottom: 1mm;
  height: 60px;
}

.no-border {
  border: 0px solid grey;
}

@media print {
  body, #a4 {
    margin: 0;
    /*box-shadow: 0;*/
  }
  header{
    position: fixed;
    left: 0;
    right: 0;
    background-color: #ffffff;
    border: 1px solid #67666a;
    padding-right: 1.5cm;
    padding-left: 1.5cm;
  }
  footer {
    position: fixed;
    left: 0;
    right: 0;
    height: 60px;
    background-color: #ffffff;
    border: 1px solid #67666a;
    padding-right: 1.5cm;
    padding-left: 1.5cm;
  }
  .resum-total{
    background-color: #fc9700;
    color: #fff;
    padding: 3%;
    width: 200px;
    height: 40px;
  }
}

</style>
