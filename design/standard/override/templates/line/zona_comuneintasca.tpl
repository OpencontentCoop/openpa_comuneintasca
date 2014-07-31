<div class="content-view-line float-break">
  <div class="class-{$node.class_identifier}">  
	<div class="box-header">
	  <h2 class="context-title">{$node.name|wash()|upfirst} ({$node.children_count})</h2>
	</div>
	<div class="block float-break">	
	<form method="post" action={"content/action/"|ezurl}>
	  <div class="button-right">
		<input type="submit" name="NewButton" value="Aggiungi elemento" class="defaultbutton" />
	  </div>
	  <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
	  <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />	  
	  <input type="hidden" name="NodeID" value="{$node.node_id}" />
	  <input type="hidden" name="ClassIdentifier" value="{menuitem_class_identifier()}" />
	  <input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
	</form>
	</div>
	<div id="nestable" class="dd">
	  {include uri='design:parts/comuneintasca/children_listitem.tpl' root=$node}
	</div>
  </div>  
</div>