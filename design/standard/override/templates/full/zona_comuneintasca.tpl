{ezpagedata_set( 'left_menu', false() )}
{ezpagedata_set( 'extra_menu', false() )}
{ezpagedata_set( 'show_path', false() )}
{ezpagedata_set( 'hide_valuation', true() )}

<div class="global-view-full">
 <div class="class-{$node.class_identifier}">
	
	{include uri="design:parts/comuneintasca/navigation.tpl" root=$node.parent.parent}
  
	{include uri="design:parts/comuneintasca/profilo.tpl" root=$node.parent}
 </div>
</div>