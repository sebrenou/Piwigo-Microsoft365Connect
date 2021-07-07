{html_style}
#m365connect {
  width:400px;
  height:48px;
  overflow:hidden;
  margin:0 auto 15px auto;
  padding:5px;
  background:rgba(128,128,128,0.2);
  border:1px solid #7e7e7e;
  border-radius:5px;
}
#m365connect .avatar {
  width:48px;
  border-radius:5px;
  margin-right:5px;
  float:left;
}
{/html_style}

<div id="m365connect">
{if $M365CONNECT_USER.avatar}
  <img src="{$M365CONNECT_USER.avatar}" class="avatar">
{else}
  <img src="{$ROOT_URL}{$M365CONNECT_PATH}template/images/avatar-default.png" class="avatar">
{/if}

  {'Logged with'|translate} : <b>{$M365CONNECT_USER.provider}</b><br>
  <b>{'Username'|translate}</b> : {$M365CONNECT_USER.username}<br>
  {if $M365CONNECT_USER.u_profile}<b>{'Profile URL'|translate}</b> : <a href="{$M365CONNECT_USER.u_profile}">{$M365CONNECT_USER.u_profile|truncate:40:' ... ':true:true}</a>{/if}
</div>