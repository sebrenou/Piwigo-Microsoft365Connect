<fieldset style="text-align:center;" id="m365connect_wrap">
  <p>{'Or sign in with'|translate}</p>  
{foreach from=$M365CONNECT.providers item=provider key=p}{strip}
  {if $provider.enabled}
    <a href="#" class="m365connect {$p|strtolower}" data-idp="{$p}" title="{$provider.name}"></a>
  {/if}
{/strip}{/foreach}
</fieldset> 