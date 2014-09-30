{default attribute_base='ContentObjectAttribute'
         html_class='full'}


{def $query = $attribute.data_text|parse_item_query()
	 $selected = ''
	 $selected_value = ''
	 $done = false()}

<fieldset>
<div class="block float-break" id="{$attribute_base}_ezstring_data_text_{$attribute.id}">
  <div class="element">
	<label>Classe/Attributo</label>
	<select name="attributes" class="class_helper">
	  {foreach class_list() as $identifier => $name}
		<optgroup label="{$name}">
		
		{if count( $query.classifications )|eq(0)}
		  {if and( not( $done ), is_set( $query.type ), $query.type|eq( $name ) )}
			{set $selected = 'selected="selected"'}
			{set $done = true()}
		  {/if}
		{/if}		
		<option {$selected} value="{$identifier}">{$name}</option>
		{set $selected = ''}
		
		{foreach $identifier|attribute_list() as $attribute_identifier => $attribute_name}	  
		  {if and( not( $done ), is_set( $query.type ), $query.type|eq( $name ) )}				
			{if count( $query.classifications )|gt(0)}
			  {foreach $query.classifications as $classification_name => $classification_value}
				{if $classification_name|eq($attribute_name)}
				  {set $selected = 'selected="selected"'}
				  {set $selected_value = $classification_value}
				  {set $done = true()}
				{/if}
			  {/foreach}
			{/if}
		  {/if}
		  <option {$selected} value="{$identifier}::{$attribute_identifier}=">{$name}/{$attribute_name}</option>
		  {set $selected = ''}
		{/foreach}
		</optgroup>
	  {/foreach}
	</select>
  </div>
  <div class="element">
	<label>Valore</label>
	<input type="text" name="value" class="value_helper" value="{$selected_value|wash()}" />
  </div>
</div>
</fieldset>

<input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="{eq( $html_class, 'half' )|choose( 'box', 'halfbox' )} ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="text" name="{$attribute_base}_ezstring_data_text_{$attribute.id}" value="{$attribute.data_text|wash( xhtml )}" />


<script>
{literal}
$(document).ready(function(){
  $('#{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}').find( '.class_helper' ).on( "change", function(){
	  $('input[name="{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}"]').val( this.value );
	  $('#{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}').find( '.value_helper' ).val( "" );
  });
  $('#{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}').find( '.value_helper' ).on( "keyup", function(){
	  $('input[name="{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}"]').val(
		$('#{/literal}{$attribute_base}_ezstring_data_text_{$attribute.id}{literal}').find( '.class_helper option:selected' ).val() + this.value
	  );
  });
});
{/literal}
</script>

{/default}