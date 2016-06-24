<a href={$node.object.main_node.url_alias|ezurl()}>
{def $isRef = false()}
{if $node.object.main_node_id|ne( $node.node_id )}{set $isRef = true()}{/if}
{if $isRef}<em>{/if}{$node.name|shorten(30)|wash()}{if $isRef}</em>{/if}
{undef $isRef}
</a>

{if is_set( $show_buttons )|not()}
  {def $show_buttons = true()}
{/if}

{if $show_buttons}
{include name=edit uri="design:parts/openpa/edit_and_traslate_buttons.tpl" node=$node}
{/if}