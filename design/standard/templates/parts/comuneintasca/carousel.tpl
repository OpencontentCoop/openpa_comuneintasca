{def $class_identifier = "item_comuneintasca"}
{ezscript_require( array( 'ezjsc::jquery', 'owl.carousel.min.js' ) )}
{ezcss_require( array( 'owl.carousel.css', 'owl.theme.css' ) )}
<script>
{literal}
$(document).ready(function() {
  $(".app-carousel").owlCarousel({    
    singleItem:true
  });
});
{/literal}
</script>

<div class="app-carousel owl-carousel owl-theme">
  {foreach fetch( content, list, hash( parent_node_id, $root.node_id,
										 sort_by, $root.sort_array,
										 class_filter_type, include,
										 class_filter_array, array( 'item_comuneintasca' ) ) ) as $item}
	<div class="item text-center">
	  {attribute_view_gui attribute=$item.data_map.image image_class=ezflowmediablock}
	  <div class="item-title" style="width:{$item.data_map.image.content.ezflowmediablock.width}px">
		<h3>		  
		  {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item show_buttons=true()}		  
		</h3>		
	  </div>
	</div>
  {/foreach}
</div>

<a id="add-carousel" class="add-item" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
<a id="sort-carousel" class="sort-item" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>

