/////////////////////////////////////////////////////////////////////////////////////////
//  AJOUT DE COMMANDE                                                                  //
/////////////////////////////////////////////////////////////////////////////////////////



document.addEventListener("DOMContentLoaded", function (event) {
  console.log("DOM entièrement chargé et analysé");
  checkboxOrders()
});

//activation / desactivation et vidage des champs de formulaire (addorder)

console.log('js lancé');
function checkboxOrders() {

  var checkBoxesArray = document.querySelectorAll('.checkbox');
  var quantityBoxArray = document.querySelectorAll('.togglePost');

  for (var i = 0; i < checkBoxesArray.length; i++) {
    checkBoxesArray[i].checked = false;
    checkBoxesArray[i].classList.add('checkbox' + i)
    quantityBoxArray[i].classList.add('productQuantity' + i);
    checkBoxesArray[i].addEventListener('click', onCheckBoxClicked);
  }

  function onCheckBoxClicked(event) {
    var classes = $(this).attr('class');
    var i = classes.substr(classes.length - 1);
    $('.productQuantity' + i).toggle('slow');
    if ($('.productQuantity' + i).attr('disabled')) {
      $('.productQuantity' + i).attr("disabled", false);
    }
    else {
      $('.productQuantity' + i).attr("disabled", true);
    }
    ;
  }
}

//résoud des bugs quand le navigateur enregistre des champs de formulaire
function effacerChampsFormulaires() {
  $('.inputTodelete')
    .not(':button, :submit, :reset, :hidden')
    .val('')
    .removeAttr('checked')
    .removeAttr('selected');
  console.log('champs de formulaire vidées')
}

