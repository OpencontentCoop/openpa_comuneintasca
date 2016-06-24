 <div class="class-{$node.class_identifier} float-break">

	{if $node.data_map.image.has_content}
		<div class="main-image left">{attribute_view_gui attribute=$node.data_map.image image_class='small'}</div>
	{/if}

	<div class="blocco-titolo-oggetto">    
 		<div class="titolo-blocco-titolo">
		  <h3>
			<a href={$node.url_alias|ezurl()} title="{$node.name|wash()}">{$node.name|wash()}</a>
			<small>{$node.class_name}</small>
		  </h3>
		  {include name=edit uri="design:parts/openpa/edit_and_traslate_buttons.tpl" node=$node}
		</div>        
	</div>
 </div>
