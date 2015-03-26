function socialopen(url){
var winpar='width=500,height=400,left=' + ((window.innerWidth - 500)/2) + ',top=' + ((window.innerHeight - 400)/2) ;
window.open(url,'tvkw',winpar);
}

var $dr = jQuery.noConflict();
$dr(document).ready(function(){
//-----------------------------------------------------------------------------
	var $args = '';
	var $id = '';
		$dr('[type=checkbox]').each(function(){
			this.checked = false;
		});	
//-----------------------------------------------------------------------------
	//alert($dr('#filters #filters_isset').val());
	if ($dr('#filters #filters_isset').val() != '0')
	{
		//$dr('#edit-sell-price-min').val($dr('#filters #minprice').val());
		//$dr('#edit-sell-price-max').val($dr('#filters #maxprice').val());
	}
	else
	{
		$dr('#block-addviewfilters-addviewfilters-form-block').remove();
		$dr('#edit-sell-price-wrapper').remove();
		$dr('#block-views-exp-products-page').remove();
	}
//-----------------------------------------------------------------------------
	$dr('#filters').change(function(){
		var $args = '';
		
		$dr('#filters ul').each(function(){
			$flag = false;
			$dr('#filters #'+this.id+' > li > input:checkbox').each(function(){
				if (this.checked)
				{
					$id = this.id;
					$args += $id.substring(4, $id.length)+'+';
					$flag = true;
				}
			});
			if ($args[$args.length-1] == '+')
				$args = $args.substring(0, $args.length-1);
			if ($flag)
				$args += ',';
		});
		if ($args[$args.length-1] == ',')
		{
			$args = $args.substring(0, $args.length-1);
		}
		$dr.post('?q=ajax', {args: $args}, function(data){
			$dr('#content').html(data);
		});
	});
//-----------------------------------------------------------------------------	
	$dr('#quotes-pane input:submit').remove();
	$dr('#uc_discounts-pane').remove();
//-----------------------------------------------------------------------------	
	$dr(function() {  
		$dr('ul.mytabs').on('click', 'li:not(.current)', function() {  
			$dr(this).addClass('current').siblings().removeClass('current')  
			.parents('div.section').find('div.box').eq($dr(this).index()).fadeIn(150).siblings('div.box').hide();  
		});  
	});  
//-----------------------------------------------------------------------------	
	//$msg = $dr('#quotes-pane input:submit').remove();
	//alert($msg);
//-----------------------------------------------------------------------------
	/*$dr('#filters p:first').addClass('active');
		$dr('#filters ul').hide();

		$dr('#filters p').click(function(){
			$dr(this).next('ul').slideToggle('slow');
			$dr(this).toggleClass('active');
			$dr(this).siblings('p').removeClass('active');
	});*/
//------------------------------------------------------------------------------
	/*$dr('#block-taxonomy-menu-block-1 li').hover(
        function () {
            //показать подменю
            $dr('ul', this).slideDown(100);  

        },
        function () {
            //скрыть подменю
            $dr('ul', this).slideUp(100);
        }
    ); */
//------------------------------------------------------------------------------- 

	/*$dr('#block-taxonomy-menu-block-1 ul li').hover(
        function() {
            $dr(this).find('ul:first').stop(true, true);
            $dr(this).find('ul:first').slideDown();
        },
        function() {           
            $dr(this).find('ul:first').slideUp('fast');
        }
    );
    // всем элементам меню с вложенностью добавить символ &raquo;
    $dr('#block-taxonomy-menu-block-1 li:has(ul)').find('a:first').append('&raquo;');*/
//--------------------------------------------------------------------------------    
   $dr('#block-taxonomy-menu-block-1 ul').addClass('menu_vert');
//--------------------------------------------------------------------------------
	$dr(function(){
		$dr('.menu_vert').liMenuVert({
			delayShow:0,    //Задержка перед появлением выпадающего меню (ms)
			delayHide:0     //Задержка перед исчезанием выпадающего меню (ms)
		});
	});
//--------------------------------------------------------------------------------  
	$dr('a.soc-icon').click(function(){
		var url = $dr(this).attr('href');
           socialopen(url);
        return false;
	});
//--------------------------------------------------------------------------------	
	$dr('.views-widget label').remove();
	//$dr('#edit-sell-price-min').attr('size', 10);
	//$dr('#edit-sell-price-max').attr('size', 10);
//--------------------------------------------------------------------------------
	var $str = window.location.href;
	var $arr = $str.split('/');
	
	if ($arr[$arr.length-1] == 'checkout') {
		var $review_table = $dr('.cart-review').html();
		$dr.cookie('review_table', $review_table);
	}
	else if ($arr[$arr.length-1] == 'review') {
		var $review_table = $dr.cookie('review_table');
		$dr('.cart-review').html($review_table);
	}
	else {
		$dr.cookie('review_table', null);
	}
//--------------------------------------------------------------------------------
});



