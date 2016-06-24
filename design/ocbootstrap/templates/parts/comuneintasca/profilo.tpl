{def $children = $root.children}
<div class="row">
  
  <div class="col-md-6">
	
	<div class="carousel">
	  <h3>{$children[0].name|wash()}</h3>
	  {include uri="design:parts/comuneintasca/carousel.tpl" root=$children[0]}
	</div>
	
	<div class="buttons">
	  <h3>{$children[2].name|wash()}</h3>
	  {include uri="design:parts/comuneintasca/buttons.tpl" root=$children[2]}
	</div>
	
  </div>
  
  <div class="col-md-6">
	
	<div class="menu">
	  <h3>{$children[1].name|wash()}</h3>
	  {include uri="design:parts/comuneintasca/menu.tpl" root=$children[1]}
	</div>
	
  </div>
  
</div>