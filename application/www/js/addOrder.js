///////////////////////////////////////////////////////////////////////
//  AJOUT/EDITION DE COMMANDE                                        //
///////////////////////////////////////////////////////////////////////



document.addEventListener("DOMContentLoaded", function (event) {
  //placeholder="Nom"effacerChampsFormulaires()
  checkboxOrders()
  rightCustomer ()
});

//activation / désactivation et vidage des champs de formulaire (addorder)
function checkboxOrders() {
  var checkBoxesArray = document.querySelectorAll('.checkbox');
  var quantityBoxArray = document.querySelectorAll('.togglePost');

  for (var i = 0; i < checkBoxesArray.length; i++) {
    checkBoxesArray[i].checked = false;
    checkBoxesArray[i].classList.add('checkbox' + i)
    quantityBoxArray[i].classList.add('productQuantity' + i);
    checkBoxesArray[i].addEventListener('click', onCheckBoxClicked);
  }
}
function onCheckBoxClicked(event) { 
  var i = $(this).attr('class');
  //On enlève les deux classes checkbox,la classe inputTodelete, puis l'espace entre les deux.
  var i = i.replace(/checkbox/g, "");
  i = i.replace(/ /g, "");
  i = i.replace('inputTodelete', "");
  $('.productQuantity' + i).toggle('slow');
  if ($('.productQuantity' + i).attr('disabled')) {
    $('.productQuantity' + i).attr("disabled", false);
  }
  else {
    $('.productQuantity' + i).attr("disabled", true);
  };
}

//résous des bugs quand le navigateur enregistre des champs de formulaire, fonction qui vide les formulaire
function effacerChampsFormulaires() {
  $('.inputTodelete')
    .not(':button, :submit, :reset, :hidden')
    //.val('')
    .removeAttr('checked')
    .removeAttr('selected');
}

//affichage du bon client en mode édition
function rightCustomer (){
  var rightCustomerId = $('select').attr('class');
  var rightOption = $("option[value|='"+rightCustomerId+"']");
  rightOption.attr("selected","selected");
}