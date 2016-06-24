{def $can_create_languages = $node.object.can_create_languages
     $languages            = fetch( 'content', 'prioritized_languages' )}

{if $node.object.can_edit}
    <form method="post" action={"content/action"|ezurl} style="float: right">
        <input type="hidden" name="HasMainAssignment" value="1" />
        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input type="hidden" name="NodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        
		{*<input type="hidden" name="ContentLanguageCode" value="ita-IT" />
        <input type="hidden" name="ContentObjectLanguageCode" value="ita-IT" />*}

    {if and(eq( $languages|count, 1 ), is_set( $languages[0] ) )}
        <input name="ContentObjectLanguageCode" value="{$languages[0].locale}" type="hidden" />
    {else}
		<select name="ContentObjectLanguageCode">
		{foreach $node.object.can_edit_languages as $language}
				   <option value="{$language.locale}"{if $language.locale|eq($node.object.current_language)} selected="selected"{/if}>{$language.name|wash}</option>
		{/foreach}
		{if $can_create_languages}
			<option value="">{'New translation'|i18n( 'design/admin/node/view/full')}</option>
		{/if}
		</select>
    {/if}    
		
		
        <input type="image" src={"websitetoolbar/ezwt-icon-edit.png"|ezimage}
               name="EditButton" title="{'Edit'|i18n( 'design/ezwebin/parts/website_toolbar')}" />            
        {if $node.object.can_remove}
        <input type="image" src={"websitetoolbar/ezwt-icon-remove.png"|ezimage}
               name="ActionRemove" title="{'Remove'|i18n('design/ezwebin/parts/website_toolbar')}" />            
        {/if}
    </form>
{/if}