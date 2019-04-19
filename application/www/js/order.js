/////////////////////////////////////////////////////////////////////////////////////////
//  GESTIONNAIRE DE COMMANDE                                                           //
/////////////////////////////////////////////////////////////////////////////////////////


document.addEventListener("DOMContentLoaded", function (event) {
  displayOrder() 
});


//quand le document est chargé calcul des totaux des commandes
$(document).ready(function () {

  var total = 0;

  var subtotal = $('.subtotal')
  for (i = 0; i < subtotal.length; i++) {
    total += parseInt(subtotal[i]['innerHTML']);
  }

  $('#total').html(total);
});

//affichage des commandes
function displayOrder() {
  var detailsButtons = document.querySelectorAll('.shower');
  var divWithDetails = document.querySelectorAll('.hiddenDetail');

  for (var i = 0; i < detailsButtons.length; i++) {
    detailsButtons[i].classList.add('order' + i)
    divWithDetails[i].classList.add('detail' + i);
    detailsButtons[i].addEventListener('click', ondetailsButtonsClicked);
  }
}

function ondetailsButtonsClicked(event) {
  var classes = $(this).attr('class');
  var i = classes.substr(classes.length - 1);
  $('.detail' + i).toggle('slow');
}
