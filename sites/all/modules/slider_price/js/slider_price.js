var $dr = jQuery.noConflict();
$dr(document).ready(function(){
		
		$dr('.view-filters').prepend('<div class="test"><label for="amount">Price range</label><input type="text" id="amount"></input></div><div id="slider"></div>');
		var $a = $dr('.views-widget .form-item-sell-price-min input').val();
		var $b = $dr('.views-widget .form-item-sell-price-max input').val();
		
		function priceName ($d, $e){
			$dr( "#slider" ).slider({
			range: true,
			min:0,
			max:500,
			values: [ $d, $e ],
			slide: function( event, ui ) {
			$dr( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
			$dr('.views-widget .form-item-sell-price-min input').val($dr( "#slider" ).slider( "values", 0 ));
			$dr('.views-widget .form-item-sell-price-max input').val($dr( "#slider" ).slider( "values", 1 ));
			}
		});
		}
		
		priceName($a, $b);
		
		$dr( ".test #amount" ).val( "$" + $dr( "#slider" ).slider( "values", 0 ) + " - $" + $dr( "#slider" ).slider( "values", 1 ) );
});