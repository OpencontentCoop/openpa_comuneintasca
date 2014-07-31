{def $can_add_subitem = $node.parent.class_identifier|eq(zone_class_identifier())}
<div class="dd-handle">
<p class="toggleTable">{$node.name|wash()}</p>
<table class="hide">
  <tr>
	<td style="width: 30px">
	  {attribute_view_gui attribute=$node.data_map.image image_class=tiny}
	</td>
	
	<td style="width: 200px">
	<small>{attribute_view_gui attribute=$node.data_map.tooltip}</small>
	</td>
  
	<td>
	  {attribute_view_gui attribute=$node.data_map.objects}
	</td>
  </tr>
	  
  <tr class="bgdark">
	<td colspan="3">
	  
	  <form method="post" action={"content/action/"|ezurl}>
		
		<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
		<input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />	  
		<input type="hidden" name="NodeID" value="{$node.node_id}" />
		<input type="hidden" name="ClassIdentifier" value="{menuitem_class_identifier()}" />
		<input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
	  
	  
		{def $can_create_languages = $node.object.can_create_languages
			 $languages            = fetch( 'content', 'prioritized_languages' )}
		{if $node.can_edit}
			{if and(eq( $languages|count, 1 ), is_set( $languages[0] ) )}
				<input name="ContentObjectLanguageCode" value="{$languages[0].locale}" type="hidden" />
			{else}
			  <select name="ContentObjectLanguageCode">
			  {foreach $node.object.can_edit_languages as $language}
						 <option value="{$language.locale}"{if $language.locale|eq($node.object.current_language)} selected="selected"{/if}>{$language.name|wash}</option>
			  {/foreach}
			  {if gt( $can_create_languages|count, 0 )}
				  <option value="">{'New translation'|i18n( 'design/admin/node/view/full')}</option>
			  {/if}
			  </select>
			{/if}
			<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit the contents of this item.'|i18n( 'design/admin/node/view/full' )}" />
		{else}
			<select name="ContentObjectLanguageCode" disabled="disabled">
				<option value="">{'Not available'|i18n( 'design/admin/node/view/full')}</option>
			</select>
			<input class="button-disabled" type="submit" name="EditButton" value="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
		{/if}
		{undef $can_create_languages}
				  
		{if $node.can_move}
			<input class="button" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move this item to another location.'|i18n( 'design/admin/node/view/full' )}" />
		{else}
			<input class="button-disabled" type="submit" name="MoveNodeButton" value="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to move this item to another location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
		{/if}
		
		{* Remove button. *}
		{if $node.can_remove}
			<input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'Remove this item.'|i18n( 'design/admin/node/view/full' )}" />
		{else}
			<input class="button-disabled" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
		{/if}
				
		{*<div class="button-right" {if $can_add_subitem|not()}style="display: none"{/if}>
		  <input type="submit" name="NewButton" value="Aggiungi sottoelemento" class="defaultbutton" />
		</div>*}		  
		
	  </form>
	</td>
  </tr>
  
</table>
</div>	
{include uri='design:parts/comuneintasca/children_listitem.tpl' root=$node}    
