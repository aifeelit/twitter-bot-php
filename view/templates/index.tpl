{include file="header.tpl"}


{foreach item=public from=$public}
{assign var="i" value=$i+1}
		<div class="container">  
	 <table align="center" id="public">
	 <th><a href='javascript:void(0)' onclick='$("#{$public.id}").toggle("slow");'>Hide{$i}</a></th>
	 <tr id="{$public.id}"><td>
	 
	 <div class="error" id="{$public.id}">
	 <div class='bubble'>
	<div class='rounded'>
	<div id='twitter_photo'><img  src='{$public.user.profile_image_url}'/></div>
        <h2 class="alt">{$public.text}</h3>
	</div>
	<div>
	<div class="notice">
	<div class="success">
		<div class='rounded'>
	<blockquote>
	  <p>
	  <h3 >Username: {$public.user.name}</h3><br />
	  <h3>UserScreenName: <a href='http://twitter.com/{$public.user.screen_name}'>{$public.user.screen_name}</a></h3><br />
	  {if $public.user.url}<h3 >Url <a href='{$public.user.url}'>{$public.user.url}</a> </h3><br />{/if}
	  <h3>Location: {$public.user.location}</h3><br />
	  <h3>Id: {$public.user.id}</strong><br />
	  <h3 style="color:red;">Followers: {$public.user.followers_count}</h3>
	   {$i-1}
	  <img src='templates/example1.png'/>
	  <br />
	 </p>
	</blockquote>
	<div>
	</div>
	</div>
	</div>	
	  </div>
	  </div></td></tr></table>

{/foreach}



{include file="footer.tpl"}
