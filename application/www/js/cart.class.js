
// nom des produits nécessaire pour l'affichage du panier
var Cart = function () {
    this.cartListQte = {}
    this.products_names = {}
    this.products_prices = {}
    this.img_url = {}
}
//class
Cart.prototype = {
    //properties
    'cartListQte': {},
    'products_names': {},
    'products_prices': {},
    'img_url': {},

    //methods
    //setter getter
    setCartListQte: function (cartListQte) {
        this.cartListQte = cartListQte;
    },
    getCartListQte: function () {
        return this.cartListQte;
    },
    setProducts_names: function (products_names) {
        this.products_names = products_names;
    },
    getProducts_names: function () {
        return this.products_names;
    },
    setProducts_prices: function (products_prices) {
        this.products_prices = products_prices;
    },
    getProducts_prices: function () {
        return this.products_prices;
    },
    setImg_url: function (img_url) {
        this.img_url = img_url;
    },
    getImg_url: function () {
        return this.img_url;
    },
    //ajout de nouveau produit, dans le sens productid, qte
    pushed: function (productId, Qty) {
        this.cartListQte[productId] = Qty;
    },
    //ajout de nouveau produit, dans le sens productid, nom
    pushName: function (productId, nm) {
        this.products_names[productId] = nm;
    },
    //ajout de nouveau produit, dans le sens productid, prix
    pushPrice: function (productId, prx) {
        this.products_prices[productId] = prx;
    },
    //ajout de nouveau produit, dans le sens productid, prix
    pushImg: function (productId, img) {
        this.img_url[productId] = img;
    },
    //suppression d'un produit
    deleteProduct: function (id) {
        delete this.cartListQte[id];
        delete this.products_names[id];
        delete this.products_prices[id];
        delete this.img_url[id];
    }
}