<template>
  <q-page padding>
    <!-- content -->
    <q-table title="Produits" :rows="command_list" :columns="columns" :pagination="pagination" :filter="filter" row-key="name">
      <template #top="props">
        <div class="col-7 q-table__title">Liste des commandes clients (Ecommerce)</div>
        <q-input v-model="filter" borderless dense debounce="300" placeholder="Rechercher" />
        <q-btn
flat round dense :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
               class="q-ml-md" @click="props.toggleFullscreen" />
      </template>
      <template #body="props">
        <q-tr :props="props" :class="alerte(props.row)">
          <q-td key="id" :props="props"> {{props.row.id}} </q-td>
          <q-td key="status" :props="props"> {{status_smg(props.row.status)}} </q-td>
          <q-td key="name" :props="props"> {{props.row.name}} </q-td>
          <q-td key="quantity" :props="props"> {{props.row.quantity}} </q-td>
          <q-td key="client_name" :props="props"> {{props.row.client_name}} </q-td>
          <q-td key="email" :props="props"> {{props.row.email}} </q-td>
          <q-td key="telephone" :props="props"> {{props.row.telephone}} </q-td>
          <q-td key="date_posted" :props="props"> {{props.row.date_posted}} </q-td>
          <q-td key="actions" :props="props">
            <q-btn class="q-mr-xs" size="xs" color="primary" icon="check_circle" @click="check(props.row)" />
            <q-btn class="q-mr-xs" size="xs" color="red" icon="cancel" @click="cancel(props.row)" />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>

<script>
import $httpService from '../boot/httpService';
import basemixin from './basemixin'
export default {
    mixins: [basemixin],
    // name: 'PageName',
    data () {
        return {
            command_list: [],
            columns: [
                { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
                { name: 'status', align: 'left', label: 'Status', field: 'status', sortable: true },
                { name: 'name', align: 'left', label: 'Produit', field: 'name', sortable: true },
                { name: 'quantity', align: 'left', label: 'Quantité', field: 'quantity', sortable: true },
                // { name: 'amount', align: 'left', label: 'Quantité', field: 'amount', sortable: true },
                { name: 'client_name', align: 'left', label: 'Client', field: 'client_name', sortable: true },
                { name: 'email', align: 'left', label: 'Email', field: 'email', sortable: true },
                { name: 'telephone', align: 'left', label: 'Telephone', field: 'telephone', sortable: true },
                { name: 'date_posted', align: 'left', label: 'Date', field: 'date_posted', sortable: true },
                { name: 'actions', label: 'Actions' }
            ],
            data: [],
            filter: '',
            pagination: {
                sortBy: 'name',
                descending: false,
                page: 1,
                rowsPerPage: 50
            }
        }
    },
    created() {
        this.command_get();
    },
    methods: {
        command_get () {
            $httpService.getWithParams('/my/get/user_command')
                .then((response) => {
                    this.command_list = response;
                })
        },
        alerte(item) {
            if (item.status === 1) {
                return 'bg-grey-2';
            } else if (item.status == 2) {
                return 'bg-green-2';
            } else if (item.status == 0) {
                return 'bg-red-2';
            }
        },
        status_smg(status) {
            if (status == 0) {
                return 'annuler';
            } else if (status == 1) {
                return 'en attente';
            } else if (status == 2) {
                return 'valider';
            }
        },
        check (prod) {
            prod.status = 2;
            if (confirm('Voulez vous valider la commande  ?')) {
                $httpService.putWithParams('/my/accept/user_command', prod)
                    .then((response) => {
                        this.command_get();
                        this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
                    })
            }
        },
        cancel (prod) {
            if (confirm('Voulez vous annuler la commande ?')) {
                $httpService.putWithParams('/my/cancel/user_command', prod)
                    .then((response) => {
                        this.command_get();
                        this.$q.notify({ color: 'green', position: 'top', message: response.msg, icon: 'report_problem' });
                    })
            }
        }
        // 0 - en annuler  1- en attente - 2- valider 3- en cours de livraison 4- annuler
    }
}
</script>

<style>
</style>
