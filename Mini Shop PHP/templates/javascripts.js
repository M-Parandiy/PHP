function buy(storeid)
{
    cw.openIframe('/buy.php?id='+storeid, 750, 500);
}

function buywindow(storeid)
{
    cw.close();
    buy(storeid);
}

function ShowStore(storeid)
{
    cw.openIframe('/store.php?id='+storeid, 750, 500);
}

function ShowCallback()
{
    cw.openIframe('/callback.php', 500, 300);
}

function AddOne()
{
    jQuery('#store_how').val((jQuery('#store_how').val()*1)+1);
}

function DecOne()
{
    if((jQuery('#store_how').val()*1) <= 1)
    {
        return
    }
    jQuery('#store_how').val((jQuery('#store_how').val()*1)-1);
}

function CountTotal()
{
    var prod_price = jQuery('#prod_price').val()*1;
    var prod_how = jQuery('#store_how').val()*1;
    
    var total_prod = prod_price*prod_how;
    jQuery('#total_prod').html(total_prod);
    
    jQuery('.storeadditem .addid').each(function() {
        if((jQuery(this).val()*1) == 1)
        {
            total_prod += (jQuery(this).parent().children('.addprice').val()*1);
        }
    });
    jQuery('#itogo').html(total_prod);
}

function SendOrder()
{
    if(jQuery('#buyer_fio').val() == '')
    {
        alert('Введите ФИО!');
        return false;
    }
    if(jQuery('#buyer_phone').val() == '')
    {
        alert('Введите номер телефона!')
        return false;
    }
    document.orderform.submit();
}



jQuery(document).ready(function() {
    jQuery('.storeadditem').click(function()
    {
        if(jQuery(this).hasClass('active'))
        {
            jQuery(this).removeClass('active');
            jQuery(this).children('.addid').val('0');
        }
        else
        {
            jQuery(this).addClass('active');
            jQuery(this).children('.addid').val('1');
        }
        CountTotal();
    });
    
    var hash = document.location.hash;
    if(hash.substring(0, 6) == '#store')
    {
        var id = hash.substring(6);
        ShowStore(id);
    }
    else if(hash == '#show_callback')
    {
        ShowCallback();
    }
    else if(hash.substring(0, 4) == '#buy')
    {
        var id = hash.substring(5);
        buy(id);
    }
    
    $(window).on('hashchange', function() { 
        var hash = document.location.hash;
        if(hash.substring(0, 6) == '#store')
        {
            var id = hash.substring(7);
            ShowStore(id);
        }
        else if(hash == '#show_callback')
        {
            ShowCallback();
        }
        else if(hash.substring(0, 4) == '#buy')
        {
            var id = hash.substring(5);
            buy(id);
        }
    }); 
    
});