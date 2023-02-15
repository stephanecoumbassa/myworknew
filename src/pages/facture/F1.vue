<template>
  <q-page padding>

    <page size="A4">
      <header>
        <p class="title">Facture Proforma</p>
      </header>
      <div class="container" style="position: relative; top: 100px; contenteditable: true;
      padding-right: 1.5cm; padding-left: 1.5cm;">

        <div class="facture" contenteditable="true">
          <div class="logo">
            <img src="img/izuddin-helmi-adnan-onh-FdFUyeM-unsplash.jpg" alt="logo du fournisseur" width="100px" height="100px">
          </div>
          <div class="fact-header">
<!--            <div class="info-left">-->
            <div class="col">
              <div class="card">
                <div class="card-header"> DOIT  <span class="h5 text-center">frotomougou</span></div>
                <div class="card-body">
                  <p class="h5">N° TVA UE FR 74784754147</p>
                  <p>DATE: 01/10/2020</p>
                  <p>CC N°</p>
                  <p>RCCM</p>
                  <p>TEL</p>
                </div>
              </div>
            </div>
            <div class="info-right">
              <div class="title h4">Kiyala Corporation</div>
              <p>Date de creation <span>01/10/2020</span></p>
              <p>Echéance <span>01/10/2020</span></p>
              <div class="resum-total">
                <p>A payer (CFA) <span>0.000.000</span></p>
              </div>
            </div>
          </div>
          <div class="container-fluid">
            <table class="table table-bordered" style="width: 100%; border: 1px solid">
              <thead>
              <tr>
                <th align="left">n° Piéce</th>
                <th align="left">desc</th>
                <th align="left">Quantité</th>
                <th align="left">PU</th>
                <th align="left">Montant</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <th align="left">1</th>
                <td align="left">.....</td>
                <td align="left">.....</td>
                <td align="left">@......</td>
                <td align="left">.....</td>
              </tr>

              <tr>
                <th align="left">1</th>
                <td align="left">.....</td>
                <td align="left">.....</td>
                <td align="left">@......</td>
                <td align="left">.....</td>
              </tr>

              </tbody>
            </table>
            <br>
            <div class="total-Ht">
              <div class="total-ht">Total HT</div>
              <div class="montant">0.000</div>
              <div class="reduc">Reduction</div>
              <div class="montant"> <span>-</span> 0.000</div>
              <div class="tva">Tva</div>
              <div class="montant">0.000</div>
              <div class="avance"> Avance</div>
              <div class="montant"> <span>-</span> 0.000</div>
              <div class="total-ttc">Total TTC</div>
              <div class="montant fin">0.000 <span>CFA</span></div>
            </div>
            <p class="nb">NB: <span class="nb-text">lorem Lorem ipsum dolor sit amet consectetur, adipisicing elit. Error, sint?</span></p>
          </div>
        </div>

      </div>

    </page>

  </q-page>
</template>

<script>
export default {
    // name: 'PageName',
  name: 'F1Page',
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
    color: rgb(4, 133, 4, 0.7) ;
    font-weight: bold;
    font-size: 2rem;
    margin-bottom: 2%;
  }

  /* resumé du net à payer  */

  .resum-total{
    background-color: rgb(4, 133, 4, 0.7);
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
    grid-template-columns: 2fr  1fr ;
    grid-template-rows: 30px 30px 30px 50px 30px;
  }

  .total-Ht.montant{
    text-align: center;
    margin-top: 3%;
  }
  /* montant ttc */
  .total-Ht .fin{
    font-weight: bold;
    color:rgb(4, 133, 4, 0.7) ;
  }

  /* la remarque nb */
  .nb{
    margin-top: 5%;
  }
  .nb-text{
    color:rgb(4, 133, 4) ;
  }

  /* facture content  */



  body {
    background: rgb(204,204,204);
  }
  page {
    position: relative;
    background: white;
    display: block;
    margin: 0 auto;
    margin-bottom: 0.5cm;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
  }
  page[size="A4"] {
    width: 21cm;
    height: 29.7cm;
    margin-top: 2%;

  }
  page[size="A4"][layout="portrait"] {
    width: 29.7cm;
    height: 21cm;
  }
  page[size="A3"] {
    width: 29.7cm;
    height: 42cm;
  }
  page[size="A3"][layout="portrait"] {
    width: 42cm;
    height: 29.7cm;
  }
  page[size="A5"] {
    width: 14.8cm;
    height: 21cm;
  }
  page[size="A5"][layout="portrait"] {
    width: 21cm;
    height: 14.8cm;
  }

  header,
  footer {
    position: absolute;
    left: 0;
    right: 0;
    background-color: #ccc;
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
    padding-top: 3mm;
    padding-bottom: 2mm;
  }
  footer {
    bottom: 0;
    color: #000;
    padding-top: 3mm;
    padding-bottom: 2mm;
  }

  @media print {
    body, page {
      margin: 0;
      box-shadow: 0;
    }
    header,
    footer {
      position: fixed;
      left: 0;
      right: 0;
      background-color: #ccc;
      padding-right: 1.5cm;
      padding-left: 1.5cm;
    }
  }

</style>
