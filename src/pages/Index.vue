<template>
<!--  <q-page class="flex flex-center">-->
  <q-page class="q-pa-lg-lg q-ma-lg-lg">
    <div class="flex flex-center q-pa-lg-lg q-ma-lg-lg">
<!--      <img alt="Quasar logo" src="~assets/quasar-logo-full.svg">-->
      <br><br>
      <br><br>
      <br><br>
      <br><br>
      <br><br>
      <img alt="Quasar logo" src="~assets/kiyala.png" style="width: 280px ">
    </div>
    <div class="row justify-center text-center q-ma-md" >
        <br><br>
        <br><br>

      <div class="col-md-4 col-sm-12 q-pa-md">
        <q-card class="my-card">
          <q-item clickable>
            <q-card-section>
              <h5>Stats Appro</h5>
            </q-card-section>
          </q-item>
        </q-card>
      </div>

      <div class="col-md-4 col-sm-12 q-pa-md">
        <router-link class="item item-link" to="/commandes" clickable>
          <q-card class="my-card">
            <q-item clickable>
              <q-card-section>
                <h5>Stats Vente</h5>
              </q-card-section>
            </q-item>
          </q-card>
        </router-link>
      </div>

      <div class="col-md-4 col-sm-12 q-pa-md">
        <router-link class="item item-link" to="/commandes" clickable>
        <q-card class="my-card" clickable>
          <q-item clickable>
            <q-card-section>
              <h5>Stock Produits</h5>
            </q-card-section>
          </q-item>
        </q-card>
        </router-link>
      </div>

    </div>
  </q-page>
</template>

<style>
</style>

<script>
export default {
    name: 'PageIndex'
};
</script>
