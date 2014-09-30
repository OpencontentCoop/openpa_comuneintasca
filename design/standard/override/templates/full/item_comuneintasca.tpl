{ezpagedata_set( 'extra_menu', false() )}
{ezpagedata_set( 'hide_valuation', true() )}

<div class="border-box">
<div class="border-content">

 <div class="global-view-full content-view-full">
  <div class="class-{$node.object.class_identifier}">

	<h1>{$node.name|wash()}</h1>
    
    {* DATA e ULTIMAMODIFICA *}
	{include name = last_modified
             node = $node             
             uri = 'design:parts/openpa/last_modified.tpl'}

	
    {* EDITOR TOOLS *}
	{include name = editor_tools
             node = $node             
             uri = 'design:parts/openpa/editor_tools.tpl'}
    


	<div class="attributi-base">
		{def $style='col-odd'}
		{foreach $node.object.contentobject_attributes as $attribute}		  
		  {if or( $attribute.contentclass_attribute.category|eq(ezini('ClassAttributeSettings', 'DefaultCategory', 'content.ini')),
				  $attribute.contentclass_attribute.category|eq('') )}
			{if $style|eq( 'col-even' )}{set $style = 'col-odd'}{else}{set $style = 'col-even'}{/if}
			<div class="{$style} col float-break attribute-{$attribute.contentclass_attribute_identifier}">
				 <div class="col-title"><span class="label">{$attribute.contentclass_attribute_name}</span></div>
				 <div class="col-content"><div class="col-content-design">
				   {if $attribute.contentclass_attribute_identifier|eq('objects')}
					 {foreach $attribute.content.relation_list as $relation}
					   {def $obj=fetch( content, object, hash( object_id, $relation.contentobject_id ) )}
						 {node_view_gui content_node=$obj.main_node view=item_comuneintasca}
					   {undef $obj}
					 {/foreach}
				   {else}
					 {attribute_view_gui attribute=$attribute}
				   {/if}
				 </div></div>
			</div>
		  {/if}
		{/foreach}
	</div>

	
    </div>
</div>

</div>
</div>
