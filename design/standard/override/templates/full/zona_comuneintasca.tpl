{def $parent = $node.parent}
<div class="content-view-full">
 <div class="class-{$parent.class_identifier}">

{include uri='design:infocollection_validation.tpl'}

<div class="content-navigation">

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title"><a href={concat( '/class/view/', $parent.object.contentclass_id )|ezurl}>{$parent.class_identifier|class_icon( normal, $parent.class_name )}</a>&nbsp;{$parent.name|wash}{*&nbsp;[{$parent.class_name|wash}]*}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>


{* DESIGN: Content START *}<div class="box-content">

<div class="tab-block">
  <ul class="tabs">
  {foreach $parent.children as $child}
	<li class="{if $child.node_id|eq($node.node_id)}first selected{/if}" id="node-tab-view">
	  <a title="{$child.name|wash()}" href="{$child.url_alias|ezurl(no)}">{$child.name|upfirst()|wash()}</a>
	</li>
  {/foreach}
  </ul>
  <div class="float-break"></div>
  <div class="tabs-content">
	<div class="tab-content selected" style="height: auto; overflow: hidden">
	  {node_view_gui content_node=$node view=line}
	</div>
  </div>
</div>

{* DESIGN: Content END *}</div>




</div>



</div>

 </div>
</div>