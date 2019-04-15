/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

function toggleCart() {
  $('#cart').toggle('slow');
}

function stikiesNavs() {
  //ajout d'une classe collante en fonction de la hauteur
  var navbar = document.querySelector(".topscroll");
  var sticky = navbar.offsetTop/* - hauteur*/;
  if (window.pageYOffset > sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////

//affichage du nombre de produit commandée, écriture du panier, branchement des boutons

document.addEventListener("DOMContentLoaded", () => {
  //écriture du panier
  writeCart();  
  //bouton d'affichage du panier
  var cartButton = $('.cartButton');
  cartButton.on('click', toggleCart);
});

// Navbar et clientbar sticky (class .sticky dans ideeDeChine.css)

window.onscroll = function () { stikiesNavs() };