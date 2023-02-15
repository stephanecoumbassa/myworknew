function numerique(value) {
  return String(value).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

function dateformat(date, type = 1) {
  date = new Date(date);
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const options2 = { year: 'numeric', month: 'long', day: 'numeric' };
  // const options3 = { weekday: 'numeric', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minutes: 'numeric' };
  let year = date.getFullYear();
  let month = (1 + date.getMonth()).toString().padStart(2, '0');
  let day = date.getDate().toString().padStart(2, '0');
  let heures = date.getHours().toString().padStart(2, '0');
  let minutes = date.getMinutes().toString().padStart(2, '0');
  if (type === 1) { return day + '-' + month + '-' + year; }
  if (type === 2) { return date.toLocaleDateString('fr-FR', options); }
  if (type === 3) { return day + '-' + month + '-' + year + ' ' + heures + ':' + minutes; }
  if (type === 4) { return date.toLocaleDateString('fr-FR', options2); }
}

// --------------------------HOME COLUMNS---------------------------------------------------------
export const salesColumnsHome = [
  { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
  { name: 'quantite_vendu', align: 'center', label: 'Qté', field: 'quantite_vendu', sortable: true, format: val => `${numerique(val)}` },
  { name: 'prix_unitaire', label: 'prix_vente', field: 'prix_unitaire', sortable: true, format: val => `${numerique(val)}` },
  // { name: 'remise_totale', label: 'Remise', field: 'remise_totale', format: val => `${numerique(val)}` },
  { name: 'montant_vendu', label: 'Montant Vendu', field: 'montant_vendu', format: val => `${numerique(val)}`, sortable: true },
  // { name: 'prix_achat', label: 'Prix Achat', field: 'prix_achat', format: val => `${numerique(val)}` },
  // { name: 'benefice_vente', label: 'Benefice', field: 'benefice_vente', sortable: true },
  { name: 'dateposted', label: 'Date Vente', field: 'dateposted', sortable: true, format: val => `${dateformat(val, 3)}` }
];

export const approColumnsHome = [
  { name: 'p_name', required: true, label: 'Nom', align: 'left', field: 'p_name', sortable: true },
  { name: 'amount', label: 'Quantite', field: 'amount', align: 'left', sortable: true, format: val => `${numerique(val)}` },
  { name: 'p_buying_price', align: 'center', label: 'Prix Achat', field: 'p_buying_price', format: val => `${numerique(val)}`, sortable: true },
  { name: 'p_sell_price', label: 'Prix Vente', align: 'left', field: 'p_sell_price', format: val => `${numerique(val)}`, sortable: true },
  // { name: 'price', label: 'Prix Achat Moyen', align: 'left', field: 'price', format: val => `${numerique(val)}` },
  // { name: 'sales_price', label: 'Prix Vente Moyen', align: 'left', field: 'sales_price', format: val => `${numerique(val)}` },
  { name: 'dateposted', label: 'Date Achat', align: 'left', field: 'dateposted', sortable: true, format: val => `${dateformat(val, 3)}` }
];

export const productColumnsHome = [
  { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
  { name: 'name', align: 'left', label: 'Nom', field: 'name', sortable: true },
  { name: 'amount', align: 'left', label: 'Quantité Restante', field: 'amount', sortable: true }
  // { name: 'actions', label: 'Actions' }
];
// --------------------------END HOME COLUMNS---------------------------------------------------------


export const fontsCte={
  arial: 'Arial',
  arial_black: 'Arial Black',
  comic_sans: 'Comic Sans MS',
  courier_new: 'Courier New',
  impact: 'Impact',
  lucida_grande: 'Lucida Grande',
  times_new_roman: 'Times New Roman',
  verdana: 'Verdana'
};

export default {
  salesColumnsHome,
  approColumnsHome,
  productColumnsHome,
  fontsCte
}
