jQuery.noConflict();

function megamenublock()
{
    jQuery("input[id^='megamenu']").prop('disabled', true);
    jQuery("textarea[id^='megamenu']").prop('disabled', true);
    jQuery("select[id^='megamenu']").prop('disabled', true);
    jQuery("button[id^='megamenu']").prop('disabled', true);
    jQuery("[id*='cmsmart_license_skuproduct']").prop('disabled', false);
    jQuery("[id*='cmsmart_license_key']").prop('disabled', false);
    jQuery("[id*='cmsmart_license_domain']").prop('disabled', false);

    
}
function megamenuactive()
{
    jQuery("input[id^='megamenu']").prop('disabled', false);
    jQuery("textarea[id^='megamenu']").prop('disabled', false);
    jQuery("select[id^='megamenu']").prop('disabled', false);
    jQuery("button[id^='megamenu']").prop('disabled', false);
    jQuery("[id*='cmsmart_license_skuproduct']").prop('disabled', false);
    jQuery("[id*='cmsmart_license_key']").prop('disabled', false);
    jQuery("[id*='cmsmart_license_domain']").prop('disabled', false);

    
}

jQuery(window).load(function(){
    
    var root = location.protocol + '//' + location.host;
    //jQuery("[name='jform[params][domain]']").val(root);     
    var action_url = 'http://mage.club/index.php?option=com_license&task=active';
    var product_sku = jQuery("[name='groups[cmsmart][fields][license_skuproduct][value]']").val();
    var license_key = jQuery("[name='groups[cmsmart][fields][license_key][value]']").val();
    var domain = window.location.host; 
    megamenublock();
    
    jQuery.ajax({
        type: 'POST',
        url: action_url,
        data: 'license[product_sku]='+product_sku+'&license[license_key]='+license_key+'&license[domain]='+domain,       
        dataType: 'json',
        beforeSend: function(){
                    jQuery('#ajax-loader').fadeIn("fast");
            },
        success: function(html){
                    jQuery('#ajax-loader').fadeOut("fast");
                    jQuery('#license-messages').text('');
                    
                    //jQuery('#license-messages').append(html.data);
                    jQuerystr = '';
                    if(html.result==true)
                    {
                        jQuerystr = '<span class="license-messages-success">'+html.data+'</span>';
                        
                        megamenuactive();
                    }
                    else
                    {
                        jQuerystr = '<span class="license-messages-fail">'+html.data+'</span>';
                        
                        megamenublock();                        
                    }
                    jQuery('#license-messages').text('');
                    jQuery('#license-messages').append(jQuerystr);
            },
            error:function()
            {
                jQuery('#ajax-loader').fadeOut("fast");
                //jQuerystr = '<span class="license-msfalse"><span class="icon-checkmark-circle fs32"></span><span class="license-msdes">No connect server cmsmart.net</span></span>';
                jQuery('#license-messages').text('');
                jQuery('#license-messages').append(jQuerystr);
            }
        
        
    });       
        
  
   jQuery('.scalable').click(function(){
    
        var product_sku = jQuery("[name='jform[params][product_sku]']").val();
        var license_key = jQuery("[name='jform[params][license_key]']").val();
        var domain = window.location.host;
    
         jQuery.ajax({
            type: 'POST',
            url: action_url,
            data: 'license[product_sku]='+product_sku+'&license[license_key]='+license_key+'&license[domain]='+domain,
            dataType:'json',
            beforeSend: function(){
                        jQuery('#ajax-loader').fadeIn("fast");
                },
            success: function(html){
                    jQuery('#ajax-loader').fadeOut("fast");
                    jQuery('#license-messages').text('');
                    
                    //jQuery('#license-messages').append(html.data);
                    jQuerystr = '';
                    if(html.result==true)
                    {
                        jQuerystr = '<span class="license-messages-success">'+html.data+'</span>';
                        
                        megamenuactive();
                    }
                    else
                    {
                        jQuerystr = '<span class="license-messages-fail">'+html.data+'</span>';
                        
                        megamenublock();                        
                    }
                    jQuery('#license-messages').text('');
                    jQuery('#license-messages').append(jQuerystr);
            },
            error:function()
            {
                jQuery('#ajax-loader').fadeOut("fast");
                //jQuerystr = '<span class="license-msfalse"><span class="icon-checkmark-circle fs32"></span><span class="license-msdes">No connect server cmsmart.net</span></span>';
                jQuery('#license-messages').text('');
                jQuery('#license-messages').append(jQuerystr);
            }
            
        });       
   
   });
   
   
    
   
});



