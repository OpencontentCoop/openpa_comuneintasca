{def $class_identifier = "item_comuneintasca"}
<div class="clearfix">
  <table class="table table-striped">
    {foreach fetch( content, list, hash( parent_node_id, $root.node_id,
                         sort_by, $root.sort_array,
                         class_filter_type, include,
                         class_filter_array, array( 'item_comuneintasca' ) ) ) as $item}
    
      <tr><td>{include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item}</td></tr>
    
    {/foreach}
  </table>
  
<p>
  <a class="btn btn-xs btn-success" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
  <a class="btn btn-xs btn-info" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>
</p>


</div>