<template>
  <div style="width: 1500px; height: 1000px">
  </div>
</template>

<script>
export default {
    name: 'PagePage',
    props: {
    },
    data () {
        return {
            // series: [{
            //     name: 'Nbre Dons.', data: [14, 45, 12, 47, 44, 32, 74, 12, 12]
            // }],
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
    color: rgb(4, 133, 4) ;
    font-weight: bold;
    font-size: 2rem;
    margin-bottom: 2%;
  }

  /* resumé du net à payer  */

  .resum-total{
    background-color: rgb(4, 133, 4);
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
    grid-template-rows: 50px 50px 50px 50px 50px;
  }

  .total-Ht .montant{
    text-align: center;
    margin-top: 5%;
  }
  /* montant ttc */
  .total-Ht .fin{
    font-weight: bold;
    color:rgb(4, 133, 4) ;
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
    padding-top: 5mm;
    padding-bottom: 3mm;
  }
  footer {
    bottom: 0;
    color: #000;
    padding-top: 3mm;
    padding-bottom: 5mm;
  }

  @media print {
    body, page {
      margin: 0;
      /*box-shadow: 0;*/
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
