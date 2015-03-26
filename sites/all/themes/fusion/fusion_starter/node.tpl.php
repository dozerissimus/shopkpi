<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
//debug($node->url);?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> blok clearfix"<?php print $attributes; ?>>

	<?php
	global $language;
	?>
	<?php print $user_picture; ?>

	<?php print render($title_prefix); ?>
	<?php if (!$page): ?>
		<h2 style="text-align: center;"<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
	<?php endif; ?>
	<?php print render($title_suffix); ?>

	<?php if ($display_submitted): ?>
		<div class="submitted">
			<?php print $submitted; ?>
		</div>
	<?php endif; ?>

	<?php
		$result = db_query('SELECT b.discount_amount FROM {uc_discounts_products} AS a, {uc_discounts} AS b 
					WHERE ((a.product_id = '.$node->nid.') AND (a.discount_id = b.discount_id))')->fetchField();
		
		//debug($result);
		switch ($language->language)
		{
			case 'uk':{$discount_text = '<div class="discount_text">Акція!</div>'; break;}
			case 'ru':{$discount_text = '<div class="discount_text">Акция!</div>'; break;}
		}
		//debug($result);
	
		/*$is_discount = false;
		$flags = flag_get_flags();
		foreach ($flags as $flag)
		{ 
			if (($flag->is_flagged($node->nid)) && (strpos($flag->name, 'akcii') !== false))
			{
				$discount = variable_get($flag->name);
				$discount_text = variable_get($flag->name.'_text');
				$is_discount = true;
				if ($discount && ($node->sell_price != $node->cost))
				{
					if (strpos($discount, '%') !== false)
					{
						$discount = explode('%', $discount);
						$node->sell_price = $node->sell_price / 100 * $discount[0];
						//db_query("UPDATE tbl_uc_products SET cost = sell_price WHERE nid = '".$node->nid."'");
						//db_query("UPDATE tbl_uc_products SET sell_price = '".$node->sell_price."' WHERE nid = '".$node->nid."'");
					}
					else
					{
						if (is_numeric($discount))
						{
							$node->sell_price = $node->sell_price - $discount;
							//db_query("UPDATE tbl_uc_products SET cost = sell_price WHERE nid = '".$node->nid."'");
							//db_query("UPDATE tbl_uc_products SET sell_price = '".$node->sell_price."' WHERE nid = '".$node->nid."'");
						}
					}
				}
			}
		}*/
	?>

	<?php if ($teaser): ?>
		<div class="content teaser"<?php print $content_attributes; ?>>
			<?php
				$upstatus = db_query('SELECT upstatus FROM {node_update_status} WHERE nid = '.$node->nid)->fetchField();
				if (!$upstatus)
				{
					db_query('INSERT into {node_update_status} (nid, upstatus) VALUES ('.$node->nid.', 1)');
					$upstatus = 1;
				}
				if ($upstatus == 1)
				{
					$html = file_get_contents($node->field_url['und'][0]['value']);
					if (preg_match_all('|<ul class="thumbnail">(.+)</ul>|Uis', $html, $matches))
						if (preg_match_all('|<li(.+)>(.+)</li>|Uis', $matches[1][0], $matches))
							if (preg_match_all('|<a href=\"(.+)">|Uis', $matches[2][0], $matches))
						{
							$matches = 'http://brain.com.ua'.$matches[1][0];
							$img = file_get_contents($matches);
							$locfile = explode('/', $matches);
							$locfile = $locfile[count($locfile)-1];
							file_put_contents('public://import_images/'.$locfile, $img); 
					
							$file_path = 'public://import_images/'.$locfile;
							$file = (object) array(
								'uid' => 1,
								'uri' => $file_path,
								'filemime' => file_get_mimetype($file_path),
								'status' => 1,
							);

							// сохраняем файл
							$file = file_copy($file, 'public://product_images'); // можно указать поддерикторию, например: public://foo/

							// добавляем к материалу
							$node->uc_product_image['und'][] = (array) $file;
						}
					
					preg_match_all('|<div class="description">(.+)</div>|Uis', $html, $matches);
					$matches = preg_replace('|<h2>(.+)</h2>|Uis', '', $matches[1][0]);
					$matches = trim($matches);
					$node->body['und'][0]['value'] = $matches;
					
					preg_match_all('|<div class="product_specifications">(.+)</div>|Uis', $html, $matches);
					$matches = preg_replace('|<h4>(.+)</h4>|Uis', '', $matches[1][0]);
					$matches = trim($matches);
					$node->field_info_table['und'][0]['value'] = $matches;
					//debug($matches); 
					node_save($node);
					
					db_query('UPDATE {node_update_status} SET upstatus = 2 WHERE nid = '.$node->nid);
				}
				
				hide($content['comments']);
				hide($content['links']);
				if ($result)
				{
					print $discount_text;
					
					print '<div class="old_price">';
					print ceil($node->sell_price).' грн.';
					print '</div>';
					print '<div style="clear: both"></div>';
					print '<div class="discount_price">';
					if (($result > 0) and ($result < 1))
						print ceil($node->sell_price - $node->sell_price * $result).' грн.';
					else
						print ceil($node->sell_price - $result).' грн.';
					print '</div>';
				}
				else
				{
					print '<div class="price">';
					print ceil($node->sell_price)." грн.";
					print '</div>';
				}
			?>
			<div class="teaser-img">
				<?php 
					print render($content['uc_product_image']['0']);
				?>
			</div>
			<div style="clear: both"></div>
				<?php 
					print render($content['body']);
					print render($content['field_rating']);
					print '<div style="clear:both"></div>';
					print '<div class="favorite-teaser">';
					print render($content['flag_favorite']);
					print '</div>';
					print '<div class="compare-teaser">';
					print render($content['flag_compare_products']);
					print '</div>';
					/*$str = $content['flag_favorite']['#markup'];
					$str = strstr($str, '<a href="/');
					$str = explode('>', $str);
					$str[0].='>';*/
				?>
			</div>
   
	<?php else: ?>
		<div class="content full_node"<?php print $content_attributes; ?>>
			<?php
			// We hide the comments and links now so that we can render them later.
			hide($content['comments']);
			hide($content['links']);
			hide($content['display_price']);
			if ($result)
			{
				print $discount_text;
					
				print '<div class="old_price">';
				print ceil($node->sell_price).' грн.';
				print '</div>';
				print '<div style="clear: both"></div>';
				print '<div class="discount_price">';
				if (($result > 0) and ($result < 1))
					$node->sell_price = $node->sell_price - $node->sell_price * $result;
				else
					$node->sell_price = $node->sell_price - $result;
				//variable_set('discount_price', $node->sell_price);
				print ceil($node->sell_price).' грн.';
				print '</div>';
			}
			else
			{
				print '<div class="price">';
				print ceil($node->sell_price)." грн.";
				print '</div>';
			}

      print render($content);

      $node_im = explode('//', $node->uc_product_image['und'][0]['uri']);
      //$node_im = 'http://'.$_SERVER['HTTP_HOST'].'/sites/default/files/styles/uc_product_full/public/'.$node_im[1];

    ?>
    <?php
    switch ($language->language)
    {
		case 'uk':
		{
			$share_text = 'Поділись з друзями: ';
			$comment_text = 'Відгуки';
			break;
		}
		case 'ru':
		{
			$share_text = 'Поделись с друзьями: ';
			$comment_text = 'Отзывы';
			break;
		}
	}
    
    $description = str_replace('"', '', strip_tags($node->body['und'][0]['value']));
	$curr_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$urlfb = 'http://www.facebook.com/sharer.php?u='.$curr_url;
	$urlvk = 'http://vk.com/share.php?url='.$curr_url.'&image='.$node_im.'&title='.$title.'('.ceil($node->sell_price).' грн)'.'&description='.$description;
	$urlmm = 'http://connect.mail.ru/share?share_url='.$curr_url.'&image='.$node_im.'&title='.$title.'('.ceil($node->sell_price).' грн)'.'&description='.$description;
	$urlok = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='.$curr_url;
	$urltw = 'https://twitter.com/share?text='.urlencode($title).'&url=http://'.$curr_url;
	
	echo '<span class="soc-icon">'.$share_text.'</span>
		<a href="'.$urlfb.'" class="soc-icon"><img src="/sites/default/files/source/facebook.png" width="20" height="20"></a>
		<a href="'.$urlvk.'" class="soc-icon"><img src="/sites/default/files/source/vk.png" width="20" height="20"></a>
		<a href="'.$urlmm.'" class="soc-icon"><img src="/sites/default/files/source/mm.png" width="20" height="20"></a>
		<a href="'.$urltw.'" class="soc-icon"><img src="/sites/default/files/source/twitter.png" width="20" height="20"></a>';
	?>
	<div class="section">  
		<ul class="mytabs">  
			<li class="current">Характеристики</li>  
			<li><?php print $comment_text ?></li>  
		</ul>  
		<div class="box visible">  

     <?php 
      /*if (isset($node->field_info_table['und']))
		$table = explode(PHP_EOL, $node->field_info_table['und'][0]['value']);
	  else
		$table = array();
      print '<table>';
      foreach ($table as $row)
      {
		  $cols = explode('--', $row);
		  print '<tr>';
		  foreach ($cols as $col)
			print '<td>'.$col.'</td>';
		  print '</tr>';
	  }
	  print '</table>';*/
	  print $node->field_info_table['und'][0]['value'];
    ?>
 
		</div>  
		<div class="box">  
			 <?php print render($content['comments']); //debug($content['comments']); ?> 
			 <div style="clear: both"></div> 
		</div>  
		
	</div>
  </div>	

  <?php //print render($content['links']); ?>

 

  <?php endif; ?>

</div>

