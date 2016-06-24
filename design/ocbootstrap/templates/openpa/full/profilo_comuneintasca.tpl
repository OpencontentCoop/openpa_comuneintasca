{ezpagedata_set( 'left_menu', false() )}
{ezpagedata_set( 'extra_menu', false() )}
{ezpagedata_set( 'show_path', false() )}
{ezpagedata_set( 'hide_valuation', true() )}

<div class="content-view-full class-{$node.class_identifier} row">
	<div class="content-main wide">
    {include uri="design:parts/comuneintasca/navigation.tpl" root=$node.parent}    
    {include uri="design:parts/comuneintasca/profilo.tpl" root=$node}      
 </div>
</div>