///////////////////////////////////////////////////////////////////////
//  GESTIONNAIRE DE PANIER                                           //
///////////////////////////////////////////////)///////////////////////
//(nécessite le fichier cart.class.js)
// affichage du panier géré dans main.js

//ajout/modification dans le panier
function addProductToCart(event) {
    //nouveau panier
    var cart = new Cart;
    //on récupère le panier déjà enregistré
    var curentCart = JSON.parse(localStorage.getItem('panier'));
    //si il existe, on le manipule pour l'afficher plus tard
    if (curentCart != null) {
        cart.cartListQte = curentCart.cartListQte;
        cart.products_names = curentCart.products_names;
        cart.products_prices = curentCart.products_prices;
        cart.img_url = curentCart.img_url;
    }
    //récupération des nouvelles données à enregistrer
    var id = $(this).attr('id');
    var quantity = $("[title=" + id + "]").val();
    //nécessaire pour corriger un bug : .val() remet l'input en string
    quantity = parseInt(quantity);
    $("[title=" + id + "]").val(quantity);
    var name = $(".name" + id).text();
    var price = $(".price" + id).text();
    var img = $(".img-responsive" + id).attr("src");
    //poussées dans le tableau
    if (quantity > 0) {
        cart.pushed(id, quantity);
        cart.pushName(id, name);
        cart.pushPrice(id, price);
        cart.pushImg(id, img);
        //écrasement des données dans le local
        localStorage.setItem('panier', JSON.stringify(cart));
        //écriture du nouveau panier
        writeCart();
    }
    //cas de remise à zéro
    /*
    else if (quantity == 0) {
        cart.deleteProduct(id);
        //écrasement des données dans le local
        localStorage.setItem('panier', JSON.stringify(cart));
        //écriture du nouveau panier
        writeCart();
    }
    */
    //corrige le bug undefined après l'utilisation de val
    else {
        $("[title=" + id + "]").val(1);
    }
}

//écriture du panier
function writeCart() {
    //effacement des anciennes données
    $('#orderlist').empty()
    var cart = JSON.parse(localStorage.getItem('panier'));
    if (cart != null) {
        //récupération des clés (product id)
        var index = []
        for (var property in cart.cartListQte) {
            index.push(property);
        }
        var total = 0;
        //construction de la section
        if (index.length == 0) {
            $('#orderlist').append('<h3>Votre panier est vide</h3>')
        } else {
            for (i = 0; i < index.length; i++) {
                $('#orderlist').append
                    ('<li>'
                        + '<h5>'
                        + '<img class="product_img" src="' + cart.img_url[index[i]] + '" width="50px">'
                        + '<p class="product_name">' + cart.products_names[index[i]] + '</p>'
                        + '</h5>'
                        + '<p class="quantity" title="' + index[i] + '">Quantité (lot de 100g) : ' + cart.cartListQte[index[i]] + '</p>'
                        + '<p class="product_price">Prix : ' + cart.products_prices[index[i]] + '</p>'
                        + '<p class="under_total">Sous-total : ' + cart.cartListQte[index[i]] * parseInt(cart.products_prices[index[i]]) + '€</p>'
                        + '<a class="delete" name="' + index[i] + '" href="#none">Retirer cet article</a>'
                        + '<hr>'
                        +
                        '</li>')
                //calcul du total
                total += cart.cartListQte[index[i]] * parseInt(cart.products_prices[index[i]])
            }
        }
        //affichage du total 
        $('#total').empty();
        $('#total').append('Total de la commande : ' + total + '€');
        //affichage du nombre de produit commandé
        if (index.length == 0) {
            $('#ordercount').empty();
        } else if (index.length > 0) {
            $('#ordercount').empty();
            $('#ordercount').append(index.length);
        }
        //boutons de suppression d'un produit dans le panier
        var deleteButtons = document.querySelectorAll('.delete');
        for (var i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener('click', deleteProduct);
        }
    } else {
        $('#orderlist').append('<p>Votre panier est vide</p>');
    }
}

// envoi de la commande
$('.submit').on('click', toOrder);
function toOrder() {
    var cart = JSON.parse(localStorage.getItem('panier'));
    if (cart != null && Object.keys(cart['cartListQte']).length != 0) {
        cart = cart.cartListQte;
        cart = Object.entries(cart);
        for (i = 0; i < cart.length; i++) {
            $('.toOrder').append('<input type="hidden" name="' + cart[i][0] + '" value="' + cart[i][1] + '">')
        }
        $(".toOrder").submit();
    }
    else {
        alert('Votre panier est vide !');
    }
}

//suppression dans le panier
function deleteProduct(event) {
    //nouveau panier
    var cart = new Cart;
    var id = $(this).attr('name');
    //on récupère le panier déjà enregistré
    var curentCart = JSON.parse(localStorage.getItem('panier'));
    //si il existe, on le manipule pour l'afficher plus tard
    if (curentCart != null) {
        cart.cartListQte = curentCart.cartListQte;
        cart.products_names = curentCart.products_names;
        cart.products_prices = curentCart.products_prices;
        cart.img_url = curentCart.img_url;
    }
    cart.deleteProduct(id);
    //écrasement des données dans le local
    localStorage.setItem('panier', JSON.stringify(cart));
    //écriture du nouveau panier
    writeCart();
}