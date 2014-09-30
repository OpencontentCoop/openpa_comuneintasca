{def $children = $root.children}
{ezcss_require( array( 'app-comuneintasca.css' ) )}
<div class="app-container float-break">
  
  <div class="app-left">
	
	<div class="carousel">
	  <small class="zone">{$children[0].name|wash()}</small>
	  {include uri="design:parts/comuneintasca/carousel.tpl" root=$children[0]}
	</div>
	
	<div class="buttons">
	  <small class="zone">{$children[2].name|wash()}</small>
	  {include uri="design:parts/comuneintasca/buttons.tpl" root=$children[2]}
	</div>
	
  </div>
  
  <div class="app-right">
	
	<div class="menu">
	  <small class="zone">{$children[1].name|wash()}</small>
	  {include uri="design:parts/comuneintasca/menu.tpl" root=$children[1]}
	</div>
	
  </div>
  
</div>