<div class="content-view-full">
 <div class="class-{$node.class_identifier}">

{include uri='design:infocollection_validation.tpl'}

<div class="content-navigation">

{* Content window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title"><a href={concat( '/class/view/', $node.object.contentclass_id )|ezurl}>{$node.class_identifier|class_icon( normal, $node.class_name )}</a>&nbsp;{$node.name|wash}{*&nbsp;[{$node.class_name|wash}]*}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>


{* DESIGN: Content START *}<div class="box-content">

<div class="tab-block">
  <ul class="tabs">
  {foreach $node.children as $index => $child}
	<li class="{if $index|eq(0)}first selected{/if}" id="node-tab-view">
	  <a title="{$child.name|wash()}" href="{$child.url_alias|ezurl(no)}">{$child.name|upfirst()|wash()}</a>
	</li>
  {/foreach}
  </ul>
  <div class="float-break"></div>
  <div class="tabs-content">
	<div class="tab-content selected" style="height: auto; overflow: hidden">
	  {node_view_gui content_node=$node.children[0] view=line}
	</div>
  </div>
</div>

{* DESIGN: Content END *}</div>




</div>



</div>

 </div>
</div>


{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'jquery.nestable.js' ) )}
<script type="text/javascript">
{literal}
$(function  () {  
  $("#nestable").nestable({group: 1, maxDepth: 2});
  $('#nestable').on('change', function(event) {
    console.log(event);
  });
  $('p.toggleTable').bind( 'click', function(e){ $(e.target).next().toggle(); console.log(e) });
})
{/literal}
</script>

<style>
{literal}
.dd {
    display: block;
    font-size: 13px;
    line-height: 20px;
    list-style: none outside none;
    margin: 0;
    max-width: 600px;
    padding: 0;
    position: relative;
}
.dd-list {
    display: block;
    list-style: none outside none;
    margin: 0;
    padding: 0;
    position: relative;
}
.dd-list .dd-list {
    padding-left: 30px;
}
.dd-collapsed .dd-list {
    display: none;
}
.dd-item, .dd-empty, .dd-placeholder {
    display: block;
    font-size: 13px;
    line-height: 20px;
    margin: 0;
    min-height: 20px;
    padding: 0;
    position: relative;
}
.dd-handle {
    background: -moz-linear-gradient(center top , #FAFAFA 0%, #EEEEEE 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    box-sizing: border-box;
    color: #333333;
    display: block;
    font-weight: bold;    
    margin: 5px 0;
    padding: 5px 10px;
    text-decoration: none;
}
.dd-handle:hover {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #2EA8E5;
}
.dd-item > button {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 0 none;
    cursor: pointer;
    display: block;
    float: left;
    font-size: 12px;
    font-weight: bold;
    height: 20px;
    line-height: 1;
    margin: 5px 0;
    overflow: hidden;
    padding: 0;
    position: relative;
    text-align: center;
    text-indent: 150%;
    white-space: nowrap;
    width: 25px;
}
.dd-item > button:before {
    content: "+";
    display: block;
    position: absolute;
    text-align: center;
    text-indent: 0;
    width: 100%;
}
.dd-item > button[data-action="collapse"]:before {
    content: "-";
}
.dd-placeholder, .dd-empty {
    background: none repeat scroll 0 0 #F2FBFF;
    border: 1px dashed #B6BCBF;
    box-sizing: border-box;
    margin: 5px 0;
    min-height: 30px;
    padding: 0;
}
.dd-empty {
    background-color: #E5E5E5;
    background-image: linear-gradient(45deg, #FFFFFF 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 75%, #FFFFFF 75%, #FFFFFF), linear-gradient(45deg, #FFFFFF 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 75%, #FFFFFF 75%, #FFFFFF);
    background-position: 0 0px, 30px 30px;
    background-size: 60px 60px;
    border: 1px dashed #BBBBBB;
    min-height: 100px;
}
.dd-dragel {
    pointer-events: none;
    position: absolute;
    z-index: 9999;
}
.dd-dragel > .dd-item .dd-handle {
    margin-top: 0;
}
.dd-dragel .dd-handle {
    box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.1);
}
.nestable-lists {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD -moz-use-text-color;
    border-image: none;
    border-left: 0 none;
    border-right: 0 none;
    border-style: solid none;
    border-width: 2px 0;
    clear: both;
    display: block;
    padding: 30px 0;
    width: 100%;
}
.dd-list .dd-list button{display: none}
.dd-handle p.toggleTable{margin: 0}
{/literl}
</style>