{def $query = $attribute.data_text|parse_item_query()}
{if is_set( $query.type )}
  <p><strong>Classe:</strong> {$query.type}</p>
  {if count( $query.classifications )|gt(0)}
  <p><strong>Filtro:</strong>
  {foreach $query.classifications as $name => $value}
	<em>{$name} = </em>{$value}
	{delimiter}, {/delimiter}
  {/foreach}
  </p>
  {/if}
{/if}