{def $class_identifier = "item_comuneintasca"}

  <p>
    <a class="btn btn-xs btn-success" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $root.node_id )|ezurl(no)}">Inserisci</a>
    <a class="btn btn-xs btn-info" href="{concat( 'websitetoolbar/sort/', $root.node_id )|ezurl(no)}">Ordina</a>
  </p>

  <table class="table table-striped">
  {foreach fetch( content, list, hash( parent_node_id, $root.node_id,
										 sort_by, $root.sort_array,
										 class_filter_type, include,
										 class_filter_array, array( 'item_comuneintasca' ) ) ) as $item}    
    <tr>
      <td>              

        {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$item}	
        <table class="table table-compat">
          {foreach fetch( content, list, hash( parent_node_id, $item.node_id,
                           sort_by, $root.sort_array,
                           class_filter_type, include,
                           class_filter_array, array( 'item_comuneintasca' ) ) ) as $subitem}
          <tr>
            <td>
              {include name=edit uri="design:parts/comuneintasca/item_link.tpl" node=$subitem}
            </td>
          </tr>
          {/foreach}
          <tr>
            <td>
            <p>
              <a class="btn btn-xs btn-success" href="{concat( 'openpa/add/', $class_identifier, '/?parent=', $item.node_id )|ezurl(no)}">Inserisci</a>
              <a class="btn btn-xs btn-info" href="{concat( 'websitetoolbar/sort/', $item.node_id )|ezurl(no)}">Ordina</a>
            </p>
          </td>
          </tr>
        </table>
  
    </td>
    </tr>
    
	
  
  {/foreach}  
  </table>

