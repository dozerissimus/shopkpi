
var $dr = jQuery.noConflict();
$dr(document).ready(function(){
//-----------------------------------------------------------------------------
	alert('gfhfgghf');
	var $args = '';
	var $id = '';
		$dr('[type=checkbox]').each(function(){
			this.checked = false;
		});	
//-----------------------------------------------------------------------------	
	$dr('#addviewfilters-form').change(function(){
		$args = '';
		$dr('[type=checkbox]').each(function(){
			if (this.checked)
			{
				$id = this.id;
				$args += $id.substring(9, $id.length)+'+';
			}
			
		});
		
		if ($args[$args.length-1] == '+')
		{
			$args = $args.substring(0, args.length-1);
		}
	
		$dr.post('?q=ajax',{args: $args},function(data){
			$dr('.view-products').html(data);
		});
	});
//-----------------------------------------------------------------------------
});

