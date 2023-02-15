<template>
  <!--  <q-page padding>-->

  <div>

    <vue3-html2pdf
      :show-layout="false"
      :float-layout="true"
      :enable-download="true"
      :preview-modal="true"
      :paginate-elements-by-height="1400"
      filename="facture"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="portrait"
      pdf-content-width="800px"
      @hasStartedGeneration="hasStartedGeneration()"
      ref="html2Pdf">

      <section ref="pdf-content">

        <slot name="pdf-content">
          <div id="a4" size="A4">

            <header>
              <p class="title">
                <span>
<!--                  <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 40px; height: 40px; object-fit: cover"/>-->
<!--                  <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 40px; height: 40px; object-fit: cover"/>-->
                  <img src="~assets/fmmi-logo.jpeg" style="width: 40px; height: 40px; object-fit: cover"/>
                </span>
                {{name}}
                <span class="float-right print-hide"></span>
              </p>
            </header>
            <div class="container" style="position: relative; top: 100px; contenteditable: true;
      padding-right: 1cm; padding-left: 1cm;">

              <div class="facture" contenteditable="true">
                <div class="logo">
                  <!-- <img src="img/izuddin-helmi-adnan-onh-FdFUyeM-unsplash.jpg" alt="logo du fournisseur" width="100px" height="100px">-->
                </div>
                <div class="fact-header">
                  <div class="col">
                    <div class="card">
                      <div class="card-header"> {{myentreprise.name}} </div>
                      <div class="card-body">
                        <p>DATE: {{date}}</p>
                        <p>Tel: {{myentreprise.telephone}}</p>
                        <p>Email: {{myentreprise.email}}</p>
                        <p>CC N°</p>
                        <p>RCCM</p>
                      </div>
                    </div>
                  </div>
                  <div class="info-right">
                    <div class="title h4">Facture #: {{facturenum}}</div>
                    <div class="title h4" v-if="client.fullname">{{client.fullname}}</div>
                    <div class="title h4">{{client.telephone_code}} {{client.telephone}}</div>
                    <div class="title h4">{{client.email}}</div>
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
                      <th align="left">Montant</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(product, index) in products" :key="index">
                      <th align="left"> {{product.name}}</th>
                      <td align="left">{{numerique(product.quantity)}}</td>
                      <td align="left">{{numerique(product.price)}}</td>
                      <td align="left">{{numerique(product.total)}}</td>
                    </tr>
                    </tbody>
                  </table>
                  <br>
                  <div class="total-Ht">
                    <div class="total-ht">Total HT</div> <div class="montant"> {{numerique(total)}} </div>
                    <div class="reduc">Reduction</div> <div class="montant"> {{numerique(remise)}}</div>
                    <div class="tva">Tva</div> <div class="montant">0</div>
                    <div class="avance"> Avance</div> <div class="montant">0</div>
                    <div class="total-ttc">Total TTC</div> <div class="montant fin">{{numerique(total)}} <span>CFA</span></div>
                  </div>
                  <p class="nb">NB: <span class="nb-text">lorem Lorem ipsum dolor sit amet consectetur, adipisicing elit. Error, sint?</span></p>
                </div>
              </div>

            </div>
            <footer contenteditable="true">
              Copyright - Affairez <br>
              Copyright - Affairez <br>
            </footer>

          </div>

        </slot>

      </section>
    </vue3-html2pdf>

    <div id="a4" size="A4">

      <header>
        <p class="title">
          <span>
<!--            <img v-if="entreprise.logo" :src="uploadurl+'/'+entreprise.id+'/magasin/'+entreprise.logo" style="width: 40px; height: 40px; object-fit: cover"/>-->
<!--            <img v-if="!entreprise.logo" src="~assets/affairez.png" style="width: 40px; height: 40px; object-fit: cover"/>-->
            <img src="~assets/fmmi-logo.jpeg" style="width: 50px; height: 50px; object-fit: cover"/>
          </span>
          <span class="float-right print-hide">
          <button v-on:click="imprimer()">Imprimer</button>&nbsp;&nbsp;
            <!--          <button v-on:click="download()">Telecharger</button>&nbsp;&nbsp;-->
          <button v-on:click="generateReport()">PDF</button>
        </span>
        </p>
      </header>
      <div class="container" style="position: relative; top: 100px; contenteditable: true;
      padding-right: 1cm; padding-left: 1cm;">

<!--        <div class="facture" contenteditable="true">-->
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
              <div class="title h4">Facture #: {{facturenum}}</div>
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
                <th align="left">Montant</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(product, index) in products" :key="index">
                <th align="left"> {{product.name}}</th>
                <td align="left">{{numerique(product.quantity)}}</td>
                <td align="left">{{numerique(product.price)}}</td>
                <td align="left">{{numerique(product.tva)}}%</td>
                <td align="left">{{numerique(product.total)}}</td>
              </tr>
              </tbody>
            </table>
            <br>
            <div class="total-Ht">
              <div class="total-ht">Total HT</div> <div class="montant"> {{numerique(total)}} </div>
              <div class="reduc">Reduction</div> <div class="montant"> {{numerique(remise)}}</div>
              <!--              <div class="tva">Tva</div> <div class="montant">0</div>-->
              <div class="avance"> Avance</div> <div class="montant">0</div>
              <div class="total-ttc">Total TTC</div> <div class="montant fin">{{numerique(total)}} <span>CFA</span></div>
            </div>
            <p class="nb">NB: <span class="nb-text">lorem Lorem ipsum dolor sit amet consectetur, adipisicing elit. Error, sint?</span></p>
          </div>
        </div>

      </div>
      <footer contenteditable="true">
        Copyright - Affairez
      </footer>

    </div>

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
      date: ''
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
    facturenum: { type: String }
  },
  computed: {
    total() {
      // return this.products.reduce((product, item) => product + (item.p.sales_price * item.quantity + (item.p.tva * item.p.sales_price * item.quantity)), 0);
      return this.products.reduce((product, item) => product + (item.total * 1), 0);
    },
    remise() {
      return this.products.reduce((product, item) => product + (item.remise_totale * 1), 0);
    }
  },
  created () {
    let date = new Date();
    this.date = this.dateformat(new Date(date.getFullYear(), date.getMonth(), date.getDate()), 4);
    // if (this.products[0]['dateposted']) this.date = this.dateformat(this.products[0]['dateposted'], 4);
  },
  watch: {
    // myentreprise: {
    //     immediate: true,
    //     handler (val) { this.myentreprise = val; }
    // },
    // fournisseur: {
    //     immediate: true,
    //     handler (val) { this.fournisseur = val; }
    // },
    // client: {
    //     immediate: true,
    //     handler (val) { this.client = val; }
    // },
    // facturenum: {
    //     immediate: true,
    //     handler (val) { this.facturenum = val; }
    // },
    // products: {
    //     immediate: true,
    //     handler (val) {
    //         this.products = val;
    //         if (this.products.length > 0) this.date = this.dateformat(this.products[0]['dateposted'], 4);
    //     }
    // },
    // name: {
    //     immediate: true,
    //     handler (val) { this.name = val; }
    // }
  },
  methods: {
    imprimer() {
      window.print();
    },
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
    generateReport () {
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
  height: 40px;
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
  text-align: center;
  margin-top: 5%;
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
  border-bottom: 1px solid #67666a;
  padding-right: 1.5cm;
  padding-left: 1.5cm;
}

footer {
  position: absolute;
  left: 0;
  right: 0;
  background-color: #ffffff;
  border-top: 1px solid #67666a;
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
