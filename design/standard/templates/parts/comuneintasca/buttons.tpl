{def $class_identifier = "item_comuneintasca"}
<div class="app-buttons float-break">
{foreach fetch( content, list, hash( parent_node_id, $root.node_id,
										 sort_by, $root.sort_array,
										 class_filter_type, include,
										 class_filter_array, array( 'item_comuneintasca' ) ) ) as $item}
<h3 class="button">  
  {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item}
</h3>
{/foreach}
<h3 class="button">
  <a class="add-item" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
  <a class="sort-item" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>
</h3>


</div>