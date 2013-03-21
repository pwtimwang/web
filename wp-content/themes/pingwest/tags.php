<?php
/*
Template Name:tags
*/
?>


<?php get_header(); ?>

<style type="text/css">
.alllinks {
width: 960px;
border-bottom: 2px double #D0D0D0;
overflow: hidden;
padding: 10px 0;
background-color: rgb(245, 245, 253);
font-size: 12px;
margin-bottom: 10px;
}
.alllinks ul {
margin: 6px 0px 6px 20px;
width: 500px;
overflow: hidden;
padding-left: 100px;
position: relative;
list-style: none;
border-bottom: 1px solid #ddd;
}
.alllinks li.li-top {
position: absolute;
left: 0;
top: 0;
font-weight: bold;
font-size:16px;
width:80px;
}
.alllinks li {
float: left;
width: 145px;
line-height: 26px;
margin:3px 10px 3px 10px;
font-size:13px;
}

.alllinks li a 
{
color: #444;
}


.ft-c {
background-attachment: scroll;
background-clip: border-box;
background-origin: padding-box;
background-position: 0 0;
background-repeat: repeat-x;
background-size: auto auto;
color: #000000;
height: 100px;
line-height: 30px;
margin: 0 auto;
text-align: center;
width: 960px;
}

.pipe {
margin: 0 5px;
color: #CCC;
}

.ft-c a
{
color: #444;
}

</style>

		<div id="container">
			<div id="content" class="alllinks" role="main">

			
			<?php 
			global $wpdb;
			$sql = "SELECT wp_term_taxonomy.*, wp_terms.name FROM `wp_term_taxonomy` join wp_terms on wp_term_taxonomy.term_id =wp_terms.term_id where wp_term_taxonomy.taxonomy='post_tag'"
				."ORDER BY wp_term_taxonomy.count DESC LIMIT 0,160";
			$results = $wpdb->get_results($sql,'ARRAY_A');
			foreach($results as $tag)
			{
				//$color=dechex(rand(0,425));
				$color='0xffffff';
				$tag_link=get_tag_link($tag['term_id']);
				if(stristr($tag['description'],'创业', 0))
				{
					$startup.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
					continue;
				}
				if(stristr($tag['description'],'公司', 0))
				{
					$company.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
					continue;
				}
				if(stristr($tag['description'],'App', 0))
				{
					$app.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
					continue;
				}
				if(stristr($tag['description'],'智能手机', 0))
				{
					$phone.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
					continue;
				}
				if(stristr($tag['description'],'人物', 0))
				{
					$people.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
					continue;
				}
				$others.='<li><a href="' . $tag_link. '" title="'. $tag['name'].' Tag" style="color:#'. $color. '">'.$tag['name']. '('.$tag['count'].')</a></li>';
				

				
			}
			
			?>
			
			<?php 
				if($company){
					echo "<ul>";
					echo '<li class="li-top">公司</li>';
					echo $company;
					echo '</ul>';
				}
				
				if($phone){
					echo "<ul>";
					echo '<li class="li-top">智能手机</li>';
					echo $phone;
					echo '</ul>';
				}
			
				if($startup){
					echo "<ul>";
					echo '<li class="li-top">创业</li>';
					echo $startup;
					echo '</ul>';
				}
			
				if($app){
					echo "<ul>";
					echo '<li class="li-top">App</li>';
					echo $app;
					echo '</ul>';
				}
			
				if($people){
					echo "<ul>";
					echo '<li class="li-top">人物</li>';
					echo $people;
					echo '</ul>';
				}
			
				if($others){
					echo "<ul style='border-bottom:none'>";
					echo '<li class="li-top">其它</li>';
					echo $others;
					echo '</ul>';
				}
			?>
			
			

			</div><!-- #content -->
            <div id="side">
				<?php get_sidebar(); ?>
	    	</div>
		</div><!-- #container -->

<?php get_footer(); ?>
