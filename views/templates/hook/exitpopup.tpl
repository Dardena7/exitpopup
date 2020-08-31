<div class="my-mask" role="dialog"></div>
<div class="my-modal" role="alert">
    <button class="my-close" role="button">X</button>
    <div class='img-container'>
        {if isset($exitpopup_image) && $exitpopup_image}
            <img src="{$exitpopup_image}">
        {else}
            Image is missing !
        {/if}
    </div><!--
    --><div class='hook-container'> 
        {if isset($exitpopup_hook) && $exitpopup_hook}
            <h3>{l s=$exitpopup_hook mod='exitpopup'}</h3>
        {else}
            Hook text is missing !
        {/if}
        <p>
            {l s='You have ' mod='exitpopup'}
            <span class='exitpopup-labels' id="exitpopup-products-count">0</span>
            {l s='product(s) in your cart, as ' mod='exitpopup'} :
        </p>
        <div class='product-example' id='exitpopup-product'>
            <img src='' id='exitpopup-product-image'>
            <h4 id='exitpopup-product-name'></h4>
            <p><span class='exitpopup-labels'>{l s='Price' mod='exitpopup'}:</span>  <span id='exitpopup-product-price'></span></p>
            <p><span class='exitpopup-labels'>{l s='Size & Color' mod='exitpopup'}:</span> <span id='exitpopup-product-size'></span></p>
        </div>
        <div id='exitpopup-button-container'>
            <a href='{$cart_url}' class='btn btn-primary exitpopup-checkout-hidden' id='exitpopup-checkout'>{l s='Proceed Checkout' mod='exitpopup'}</a>
        </div>
    </div>
</div>