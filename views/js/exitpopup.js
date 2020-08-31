
let originalTitle = document.title;
let originalIcon = document.querySelector("link[rel*='icon']") || document.createElement('link');
originalIcon = originalIcon.href;
let interval;

$("html").on('mouseleave', () => {
    if (prestashop.cart && prestashop.cart.products_count > 0){
        showExitPopup();
    }
});

$("html").on('mouseenter', () => {
    $('#exitpopup-checkout').removeClass('exitpopup-checkout-hidden');
});

window.addEventListener('blur', function()
{
    changeTabIcon(exitpopup_icon);
    changeTabIcon(exitpopup_icon);
    animateTab();
});

function showExitPopup()
{
    $('#exitpopup-checkout').addClass('exitpopup-checkout-hidden');
    displayProductsCount(prestashop.cart.products_count);
    let random = Math.floor(Math.random()*prestashop.cart.products.length);
    displayProduct(prestashop.cart.products[random]);
    $(".my-mask").addClass("my-active");
}

function changeTabIcon(icon)
{
    const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/x-icon';
    link.rel = 'shortcut icon';
    link.href = icon;
    document.getElementsByTagName('head')[0].appendChild(link);
}

function animateTab()
{
    const title = "..."+exitpopup_tab;
    let documentTitle = "";
    let k = 0;
    interval = setInterval(() => {
        ++k;
        documentTitle = title[title.length - k].concat(documentTitle);
        document.title = documentTitle;
        if (k == title.length) {
            k = 0;
        }
        if (documentTitle.length >= title.length *2) {
            documentTitle = documentTitle.slice(0, title.length*2);
        }
    }, 200);
}

window.addEventListener('focus', function() {
    changeTabIcon(originalIcon);
    changeTabIcon(originalIcon);
    document.title = originalTitle;
    if (interval) {
        clearInterval(interval);
    }
});

if (window.history && history.pushState) 
{
    addEventListener('load', function() {
        history.pushState(null, null, null); // creates new history entry with same URL
        addEventListener('popstate', function() {
            if (prestashop.cart && prestashop.cart.products_count > 0){
                showExitPopup();
                $('#exitpopup-checkout').removeClass('exitpopup-checkout-hidden');
            }
            else {
                history.back();
            }
        });    
    });
}

function closeModal(){
    $(".my-mask").removeClass("my-active");
}
  
$(".my-close, .my-mask").on("click", function(){
    closeModal();
});

function displayProductsCount(count) {
    $('#exitpopup-products-count').text(count);
}

function displayProduct(product) {
    if(!product){
        return;
    }
    if (product.images.length > 0) {
        $('#exitpopup-product-image').attr("src", product.images[0].medium.url);
    }
    $('#exitpopup-product-name').text(product.name);
    $('#exitpopup-product-price').text(product.price);
    $('#exitpopup-product-size').text(product.attributes_small);
}