<template>
  <q-layout view="hHh lpR fFf">

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
export default {
    name: 'GoogleNewsLayout',
    data () {
        return {
        }
    },
    created () {
        localStorage.clear();
    },
    methods: {
    }
}
</script>

<style>
  .GNL__toolbar{
    height: 64px
  }

  .GNL__toolbar-input{
    width: 55%
  }

  .GNL__drawer-item{
    line-height: 24px;
    border-radius: 0 24px 24px 0;
    margin-right: 12px;

  }

  q-item-section--avatar {
    color: #5f6368;
  }

  q-icon{
    color: #5f6368;
  }
  /*
  li a {
    text-decoration: none;
    color: #5f6368;
  }
  a {
    text-decoration: none;
    color: #5f6368;
  }*/
  q-item-label{
    color: #3c4043;
    letter-spacing: .01785714em;
    font-size: .875rem;
    font-weight: 500;
    line-height: 1.25rem
  }
/*
  router-link{
    text-decoration: none;
  }
*/
  .GNL__drawer-footer-link{
    color: inherit;
    text-decoration: none;
    font-weight: 500;
    font-size: .75rem;
  }

  .GNL__drawer-footer-link:hover{
    color: #000;
  }

</style>
