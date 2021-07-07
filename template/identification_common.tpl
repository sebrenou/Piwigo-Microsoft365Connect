{combine_script id='jquery.colorbox' load='footer' require='jquery' path='themes/default/js/plugins/jquery.colorbox.min.js'}
{combine_css id='colorbox' path="themes/default/js/plugins/colorbox/style2/colorbox.css"}

{html_style}
#openid_form { padding:20px; }
#openid_form h3, #openid_form .m365connect_38px { display:inline-block; vertical-align:middle; margin:0; }
#openid_label.error { color:red; font-weight:bold; }
.microsoftgraph{
  display:inline-block;
  background:url('plugins/m365connect/template/images/microsoft_graph.svg') 0px 0 no-repeat;
  width:108px;
  height:108px;
}
{/html_style}

{footer_script}
// redirect, called from the popup
function m365connect_redirect(type) {
  var url = '{$M365CONNECT.u_redirect}';
  if (type && type != 'default') {
    url = '{$ABS_ROOT_URL}'+ type +'.php';
  }

  window.location.href = url;
}

// open authentication popup
function open_auth(url) {
  window.open(
    '{$M365CONNECT.u_login}' + url + '&key={$M365CONNECT.key}&provider=' + url, 
    'hybridauth_social_sign_on', 
    'location=0,status=0,scrollbars=0,width=800,height=500'
  );  
}
 
// click on a button
jQuery('a.m365connect').click(function(e) {
  e.preventDefault(); 
  var idp = jQuery(this).data('idp');
  open_auth(idp);
});

jQuery('#openid_form').submit(function(e) {
  e.preventDefault();
  
  var idp = jQuery(this).data('idp');
  var oi = jQuery('#openid_form input[name=openid_identifier]').val();
  jQuery('#openid_form input[name=openid_identifier]').val('');
  
  jQuery('#openid_label').removeClass('error');
  if (!oi) {
    jQuery('#openid_label').addClass('error');
    return;
  }  
  open_auth('OpenID&openid_identifier=' + encodeURI(oi));
  jQuery.colorbox.close();
});

jQuery('#openid_cancel').click(function(e) {
  e.preventDefault();
  
  jQuery('#openid_label').removeClass('error');
  jQuery.colorbox.close();
});
{/footer_script}

<div style="display:none;">
  <form id="openid_form" action="">
    <div>
      <span class="m365connect_38px"></span>
      <h3>OpendID</h3>
    </div>
    <div>
      <br>
      <label id="openid_label" for="openid_identifier"></label>
      <br>
      <input type="text" name="openid_identifier" id="openid_identifier" size="50">
    </div>
    <div>
      <br>
      <input type="submit" name="{'Submit'|translate}">
      <a href="#" id="openid_cancel">{'Cancel'|translate}</a>
    </div>
  </form>
</div>