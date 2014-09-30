{def $class_identifier = "item_comuneintasca"}
<ul class="app-menu">
  <li>
	<div class="menu-item">
	  <a class="add-item" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
	  <a class="sort-item" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>
	</div>
  </li>
  {foreach fetch( content, list, hash( parent_node_id, $root.node_id,
										 sort_by, $root.sort_array,
										 class_filter_type, include,
										 class_filter_array, array( 'item_comuneintasca' ) ) ) as $item}
  <li class="button">
	<div class="menu-item">	  
	  {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item}	  
	</div>
	
	<ul class="app-submenu">
	  <li>
		<div class="menu-item">
		  <a class="add-item" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $item.node_id )|ezurl(no)}">Inserisci</a>
		  <a class="sort-item" href="{concat( 'websitetoolbar/sort/', $item.node_id )|ezurl(no)}">Ordina</a>
		</div>
	  </li>
	  {foreach fetch( content, list, hash( parent_node_id, $item.node_id,
										 sort_by, $root.sort_array,
										 class_filter_type, include,
										 class_filter_array, array( 'item_comuneintasca' ) ) ) as $subitem}
	  <li class="button">
		<div class="menu-item">		  
		  {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$subitem}		  
		</div>
	  </li>
	  {/foreach}	  
	</ul>
	
  </li>
  {/foreach}  
</ul>

