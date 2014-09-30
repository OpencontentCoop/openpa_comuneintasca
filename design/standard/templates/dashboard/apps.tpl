<h2>{apps_root().name|wash()}</h2>

<ul>
{foreach apps_root().children as $item}
  <li class="button">	
	<a href={$item.url_alias|ezurl()} title="{$item.name|wash()}">{$item.name|wash()}</a>	  
  </li>
{/foreach}
</ul>