////////////////////////////////////////////////////////
//  JS DES PAGES PRODUCT                              //
////////////////////////////////////////////////////////

//boutons de selections des produits
document.addEventListener("DOMContentLoaded", function (event) {
    //sélection des boutons qui envoie vers le localstorage la quantité
    var sendButtons = document.querySelectorAll('.addProductToCart');
    //écouteur d'évènements
    for (var i = 0; i < sendButtons.length; i++) {
        sendButtons[i].addEventListener('click', addProductToCart);
    }
    //bouton gérant la quantité
    var moreButtons = document.querySelectorAll('.more');
    var lessButtons = document.querySelectorAll('.less');
    //écouteur d'évènements
    for (var i = 0; i < moreButtons.length; i++) {
        moreButtons[i].addEventListener('click', more);
    }
    for (var i = 0; i < lessButtons.length; i++) {
        lessButtons[i].addEventListener('click', less);
    }
});

//évenement branché dans main.js. Marche seulement pour la page product
function more() {
    //récupération de l'id du produit
    var id = $(this).attr('name');
    //récupération de la quantité présente actuellement dans l'input 
    var quantity = $("[title=" + id + "]").val();
    if (quantity.length == 0) {
        quantity = 0;
    };
    quantity = parseInt(quantity) + 1;
    //écriture de la nouvelle quantité
    $("[title=" + id + "]").val(quantity);
}
function less() {
    //récupération de l'id du produit
    var id = $(this).attr('name');
    //récupération de la quantité présente actuellement dans l'input 
    var quantity = parseInt($("[title=" + id + "]").val());
    if (quantity.length == 0) {
        quantity = 0;
    }
    //écriture de la nouvelle quantité
    quantity = parseInt(quantity) - 1;
    $("[title=" + id + "]").val(quantity);
}