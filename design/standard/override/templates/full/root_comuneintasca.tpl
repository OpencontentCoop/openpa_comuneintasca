{ezpagedata_set( 'left_menu', false() )}
{ezpagedata_set( 'extra_menu', false() )}
{ezpagedata_set( 'show_path', false() )}
{ezpagedata_set( 'hide_valuation', true() )}

<div class="global-view-full">
 <div class="class-{$node.class_identifier}">
	
	<h1>{$node.name|wash()}</h1>
	
	<h2>Profili disponibili:</h2>
	<ul>
	{foreach fetch( content, list, hash( parent_node_id, $node.node_id,
										 class_filter_type, include,
										 class_filter_array, array( 'profilo_comuneintasca' ) ) ) as $item}
	  <li>	
		<a href={$item.url_alias|ezurl()} title="{$item.name|wash()}">{$item.name|wash()}</a>	  
	  </li>
	{/foreach}
	</ul>
	
	{*<h2>Contenuti temporanei</h2>
	<ul>
	{foreach fetch( content, list, hash( parent_node_id, $node.node_id,
										 class_filter_type, include,
										 class_filter_array, array( 'folder' ) ) ) as $item}
	  <li>	
		<a href={$item.url_alias|ezurl()} title="{$item.name|wash()}">{$item.name|wash()}</a>	  
	  </li>
	{/foreach}
	</ul>*}
  	
 </div>
</div>