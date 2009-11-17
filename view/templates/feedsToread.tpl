{include file="header.tpl"}
<div class="container">
{foreach item=read from=$read}

	 <table align="center">
	<th><a href='javascript:void(0)' onclick='$("#{$read.count}").toggle("slow");'>Hide</a><th>
	 <tr id="{$read.count}"><td>
	 <div class="error">
	 <div class='bubble'>
	<div class='rounded'>
        <h3 class="alt">{$read.title}</h3><br>
        <h3 class="alt"><a href='{$read.url}'>{$read.url}</a></h3><br>
	</div>
	<div>
	<div class="notice">
	<div class='rounded'>
	<blockquote>
	  <p>
	  <h4>{$read.content}</h4>	 
	 </p>
	</blockquote>
	<div>
	</div>
	</div>	
	  </div>
	  </td></tr></table>

{/foreach}
</div>


{include file="footer.tpl"}
