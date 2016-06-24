
	<a href={$root.url_alias|ezurl()} title="{$root.name|wash()}">{$root.name|wash}</a>:    
	{foreach fetch( content, list, hash( parent_node_id, $root.node_id,
										 class_filter_type, include,
										 class_filter_array, array( 'profilo_comuneintasca' ) ) ) as $item}
	  <a href={$item.url_alias|ezurl()} title="{$item.name|wash()}">
		<small>{$item.name|upcase()|wash()}</small>
	  </a>
	  {delimiter} - {/delimiter}
	{/foreach}  