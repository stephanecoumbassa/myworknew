const required = { requiresAuth: true }
const routes = [
  {
    path: '/',
    component: () => import('layouts/Template1.vue'),
    children: [
      {path: '', component: () => import('pages/StatsMois.vue'), meta: required},
      {path: '/board', component: () => import('pages/StatsMois.vue'), meta: required},
      {path: '/qstock', component: () => import('pages/StatsMois.vue'), meta: required},
      {path: '/clients', component: () => import('pages/Client.vue'), meta: required},
      {path: '/achats', component: () => import('pages/Achat.vue'), meta: required},
      {path: '/achats/credit', component: () => import('pages/CreditAppro.vue'), meta: required},
      {path: '/achats/factures', component: () => import('pages/FactureAppro.vue'), meta: required},
      {path: '/achats/avoir', component: () => import('pages/RetourAvoir.vue'), meta: required},
      {path: '/achats/commandes', component: () => import('pages/Commande.vue'), meta: required},
      {path: '/bon-de-commande', component: () => import('pages/BonCommande.vue'), meta: required},
      {path: '/depenses', component: () => import('pages/Depense.vue'), meta: required},
      {path: '/ventes', component: () => import('pages/Vente.vue'), meta: required},
      {path: '/ventes/new', component: () => import('pages/VenteNew.vue'), meta: required, name: 'vente_new'},
      {path: '/ventes/caisse', component: () => import('pages/VenteOnly.vue'), meta: required, name: 'vente_caisse'},
      {path: '/location/caisse', component: () => import('pages/LocationOnly.vue'), meta: required, name: 'location_caisse'},
      {path: '/location', component: () => import('pages/Location.vue'), meta: required, name: 'location_new'},
      {path: '/ventes/devis', component: () => import('pages/Devis.vue'), meta: required},
      {path: '/devis', component: () => import('pages/Devis.vue'), meta: required},
      {path: '/ventes/credit', component: () => import('pages/CreditVente.vue'), meta: required},
      {path: '/ventes/factures', component: () => import('pages/Facture.vue'), meta: required},
      {path: '/produit', component: () => import('pages/Produit.vue'), meta: required},
      {path: '/produits', component: () => import('pages/Produit.vue'), meta: required},
      {path: '/produits/resume', component: () => import('pages/ProduitResume.vue'), meta: required},
      {path: '/produits/inventaire', component: () => import('pages/ProduitInventaire.vue'), meta: required},
      {path: '/produits-location', component: () => import('pages/ProduitLocation.vue'), meta: required},
      {path: '/produits/pack', component: () => import('pages/ProduitPack.vue'), meta: required},
      {path: '/produits/marques', component: () => import('pages/Marque.vue'), meta: required},
      {path: '/produits/pertes', component: () => import('pages/Perte.vue'), meta: required},
      {path: '/depenses', component: () => import('pages/Depense.vue'), meta: required},
      // { path: '/services', component: () => import('pages/Services.vue'), meta: required },
      {path: '/prestations', component: () => import('pages/Services.vue'), meta: required},
      {path: '/services', component: () => import('pages/ServicesItem.vue'), meta: required},
      // { path: '/produits2', component: () => import('pages/Produit2.vue'), meta: required },
      {path: '/categories', component: () => import('pages/Categorie.vue'), meta: required},
      {path: '/fournisseurs', component: () => import('pages/Fournisseur.vue'), meta: required},
      {path: '/fournisseurs_list', component: () => import('pages/Fournisseur.vue'), meta: required},
      {path: '/users', component: () => import('pages/Utilisateur.vue'), meta: required},
      {path: '/ventes/retour', component: () => import('pages/Retour.vue'), meta: required},
      {path: '/commandes-clients', component: () => import('pages/CommandeList.vue'), meta: required},
      {path: '/parametres', component: () => import('pages/Parametres.vue'), meta: required},
      {path: '/rh/employe', component: () => import('pages/rh/Employe.vue'), meta: required},
      {path: '/facture/f1', component: () => import('pages/facture/F1.vue'), meta: required},
      {path: '/budget-revenu', component: () => import('pages/BudgetrevenuPage.vue'), meta: required},
      {path: '/budget-depense', component: () => import('pages/BudgetdepensePage.vue'), meta: required}
    ]
  },
  {
    path: '/projects',
    component: () => import('layouts/Template1.vue'),
    children: [
      {path: '/absences', component: () => import('pages/project/PAbsencePage.vue'), meta: required},
      {path: '/arrivees', component: () => import('pages/project/PArriveePage.vue'), meta: required},
      {path: '/assignation', component: () => import('pages/project/PAssignationPage.vue'), meta: required},
      {path: '/conges', component: () => import('pages/project/PCongePage.vue'), meta: required},
      {path: '/employe', component: () => import('pages/project/PEmployePage.vue'), meta: required},
      {path: '/employe', component: () => import('pages/project/PEmployePage.vue'), meta: required},
      {path: '/evenement', component: () => import('pages/project/PEvenementPage.vue'), meta: required},
      {path: '/fichier', component: () => import('pages/project/PFichierPage.vue'), meta: required},
      {path: '/projet', component: () => import('pages/project/PProjetPage.vue'), meta: required},
      {path: '/projet/:id', component: () => import('pages/project/PProjetIdPage.vue'), meta: required},
      {path: '/salaire', component: () => import('pages/project/PSalairePage.vue'), meta: required},
      {path: '/taches', component: () => import('pages/project/PTaskPage.vue'), meta: required},
      {path: '/sous-taches', component: () => import('pages/project/PSTaskPage.vue'), meta: required},
    ]
  },
  {
    path: '/app',
    component: () => import('layouts/LoginTemplate.vue'),
    children: [
      {
        path: '/login',
        component: () => import('pages/Login.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: '/mot-de-passe-oublie',
        component: () => import('pages/LoginPassword.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: '/creation',
        component: () => import('pages/MagasinRegister.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: '/azeaze',
        component: () => import('pages/InscriptionCache.vue'),
        meta: { requiresAuth: false }
      }
    ]
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
