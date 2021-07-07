{if $id == "mbIdentification" and isset($U_LOGIN)}
  {html_style}
  dl#mbIdentification dd:first-of-type { padding-bottom:0 !important; }
  #mbIdentification .m365connect { margin:0 1px; }
  {/html_style}
  
  <dd>
    <form id="quickconnect">
    <fieldset style="text-align:center;">
      <legend>{'Or sign in with'|translate}</legend>
      
    {foreach from=$M365CONNECT.providers item=provider key=p}{strip}
      {if $provider.enabled}
        <a href="#" class="m365connect m365connect_{$M365CONNECT.conf.menubar_icon} {$p|strtolower}" data-idp="{$p}" title="{$provider.name}"></a>
      {/if}
    {/strip}{/foreach}
    </fieldset>
    </form>
  </dd>
{/if}