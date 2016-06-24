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
	  {attribute_view_gui attribute=$item.data_map.image image_class=ezflowmediablock alignment=center}
	  <div class="item-title">		  
		  {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item show_buttons=true()}		  
		</div>
	</div>
  {/foreach}
</div>

<p>
  <a id="add-carousel" class="btn btn-xs btn-success" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
  <a id="sort-carousel" class="btn btn-xs btn-info" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>
</p>  

