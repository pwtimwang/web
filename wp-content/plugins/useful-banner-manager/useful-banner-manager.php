<?php
/*
Plugin Name: Useful Banner Manager
Plugin URI: http://rubensargsyan.com/wordpress-plugin-useful-banner-manager/
Description: This banner manager plugin helps to manage the banners easily over the WordPress blog. It works with BuddyPress too. <a href="admin.php?page=useful-banner-manager.php">Banner Manager</a>
Version: 1.1
Author: Ruben Sargsyan
Author URI: http://rubensargsyan.com/
*/

/*  Copyright 2011 Ruben Sargsyan (email: info@rubensargsyan.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

$useful_banner_manager_plugin_url = WP_PLUGIN_URL."/".str_replace(basename(__FILE__),"",plugin_basename(__FILE__));
$useful_banner_manager_plugin_title = "Useful Banner Manager";
$useful_banner_manager_plugin_prefix = "useful_banner_manager_";
$useful_banner_manager_table_name = $wpdb->prefix."useful_banner_manager_banners";

function useful_banner_manager_load(){
	global $wpdb;
    $useful_banner_manager_table_name = $wpdb->prefix."useful_banner_manager_banners";
    $useful_banner_manager_plugin_prefix = "useful_banner_manager_";
    $useful_banner_manager_version = "1.1";

	$charset_collate = "";
	if($wpdb->supports_collation()){
		if(!empty($wpdb->charset)){
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)){
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}

    require_once(ABSPATH."wp-admin/includes/upgrade.php");

    if($wpdb->get_var("SHOW TABLES LIKE '$useful_banner_manager_table_name'")!=$useful_banner_manager_table_name){
	    $create_useful_banner_manager_table = "CREATE TABLE $useful_banner_manager_table_name(".
			"id INT(11) NOT NULL auto_increment,".
			"banner_name VARCHAR(255) NOT NULL,".
            "banner_type VARCHAR(4) NOT NULL,".
            "banner_title VARCHAR(255) NOT NULL,".
            "banner_alt TEXT NOT NULL,".
            "banner_link VARCHAR(255) NOT NULL,".
            "link_target VARCHAR(7) NOT NULL,".
            "link_rel VARCHAR(8) NOT NULL,".
            "banner_width INT(11) NOT NULL,".
            "banner_height INT(11) NOT NULL,".
            "added_date VARCHAR(10) NOT NULL,".
            "active_until VARCHAR(10) NOT NULL,".
            "banner_order INT(11) NOT NULL DEFAULT 0,".
            "is_visible VARCHAR(3) NOT NULL,".
            "banner_added_by VARCHAR(50) NOT NULL,".
            "banner_edited_by TEXT NOT NULL,".
            "last_edited_date VARCHAR(10) NOT NULL,".
            "PRIMARY KEY (id)) $charset_collate;";

        dbDelta($create_useful_banner_manager_table);
    }

    if(get_option("useful_banner_manager_version")===false){
        add_option("useful_banner_manager_version",$useful_banner_manager_version);
    }elseif(get_option("useful_banner_manager_version")=="1.0"){
        $create_useful_banner_manager_not_exists_fields = "ALTER TABLE $useful_banner_manager_table_name ADD banner_alt TEXT NOT NULL AFTER banner_title, ADD link_rel VARCHAR(8) NOT NULL AFTER link_target";

        $wpdb->query($create_useful_banner_manager_not_exists_fields);

        update_option("useful_banner_manager_version",$useful_banner_manager_version);
    }

    if(!file_exists(ABSPATH."wp-content/uploads")){
        mkdir(ABSPATH."wp-content/uploads");
    }

    if(!file_exists(ABSPATH."wp-content/uploads/useful_banner_manager_banners")){
        mkdir(ABSPATH."wp-content/uploads/useful_banner_manager_banners");
    }
}

function useful_banner_manager_menu(){
    if(function_exists("add_menu_page")){
		add_menu_page(__("Banner Manager", "useful-banner-manager"), __("Banner Manager", "useful-banner-manager"), "manage_options", basename(__FILE__), "useful_banner_manager_manage_banners");
	}
}

function useful_banner_manager_manage_banners(){
    global $useful_banner_manager_plugin_url, $useful_banner_manager_plugin_title, $useful_banner_manager_plugin_prefix;
    ?>
    <div class="wrap">
      <div style="float: right; margin: 20px 0 0 0"><a href="http://blorner.com" target="_blank"><img src="http://banners.blorner.com/blorner.com-468x60.jpg" alt="Blorner" style="border: none" /></a></div>
      <h1><?php echo $useful_banner_manager_plugin_title; ?></h1>
      <h2>Banners</h2>
      <?php
      if(isset($_GET[$useful_banner_manager_plugin_prefix."banner_id"]) && is_numeric($_GET[$useful_banner_manager_plugin_prefix."banner_id"]) && intval($_GET[$useful_banner_manager_plugin_prefix."banner_id"])>0){
          if($_GET["page"]==basename(__FILE__)){
              if(isset($_POST[$useful_banner_manager_plugin_prefix."save_banner"])){
                  $banner_id = intval($_GET[$useful_banner_manager_plugin_prefix."banner_id"]);

                  $banner_old_name = $_POST[$useful_banner_manager_plugin_prefix."banner_name"];
                  $banner_old_type = $_POST[$useful_banner_manager_plugin_prefix."banner_type"];
                  $banner_old_file = $banner_id."-".$banner_old_name.".".$banner_old_type;

                  $errors = array();
                  if($_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["error"]==0){
                      $banner_name_parts = explode(".",$_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["name"]);
                      array_pop($banner_name_parts);
                      $banner_name = implode(".",$banner_name_parts);
                      $banner_type = array_pop(explode(".",$_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["name"]));
                      if(strtolower($banner_type)!="jpg" && strtolower($banner_type)!="jpeg" && strtolower($banner_type)!="gif" && strtolower($banner_type)!="png" && strtolower($banner_type)!="swf"){
                          $errors[] = "banner_type";
                      }
                      $banner_tmp_file = $_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["tmp_name"];
                  }else{
                      $banner_name = $banner_old_name;
                      $banner_type = $banner_old_type;
                  }

                  if(trim($_POST[$useful_banner_manager_plugin_prefix."banner_title"])==""){
                      $errors[] = "banner_title";
                  }else{
                      $banner_title = htmlentities(trim(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_title"])),ENT_QUOTES);
                  }

                  $banner_alt = htmlentities(trim(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_alt"])),ENT_QUOTES);

                  $banner_link = trim(str_replace(array("\"","'"),array("",""),stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_link"])));
                  if($banner_link!=""){
                      switch($_POST[$useful_banner_manager_plugin_prefix."link_target"]){
                          case "_self":
                          $link_target = "_self";
                          break;
                          case "_top":
                          $link_target = "_top";
                          break;
                          case "_blank":
                          $link_target = "_blank";
                          break;
                          case "_parent":
                          $link_target = "_parent";
                          break;
                          default:
                          $link_target = "_self";
                      }
                  }else{
                      $link_target = "";
                  }

                  if($banner_link!=""){
                      switch($_POST[$useful_banner_manager_plugin_prefix."link_rel"]){
                          case "nofollow":
                          $link_rel = "nofollow";
                          break;
                          default:
                          $link_rel = "dofollow";
                      }
                  }else{
                      $link_rel = "";
                  }

                  if(isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"]) && !in_array("banner_type",$errors)){
                      if($banner_type!="swf"){
                        list($banner_width,$banner_height) = getimagesize($banner_tmp_file);
                      }else{
                        $errors[] = "swf_auto_sizes";
                      }
                  }elseif(!isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){
                      if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_width"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_width"])>0){
                          $banner_width = intval($_POST[$useful_banner_manager_plugin_prefix."banner_width"]);
                      }else{
                          $errors[] = "banner_width";
                      }
                      if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_height"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_height"])>0){
                          $banner_height = intval($_POST[$useful_banner_manager_plugin_prefix."banner_height"]);
                      }else{
                          $errors[] = "banner_height";
                      }
                  }
                  $added_date = date("Y-m-d");
                  if($_POST[$useful_banner_manager_plugin_prefix."active_until"]!=""){
                      if(preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/",trim($_POST[$useful_banner_manager_plugin_prefix."active_until"])) && trim($_POST[$useful_banner_manager_plugin_prefix."active_until"])>=date("Y-m-d")){
                          $active_until = trim($_POST[$useful_banner_manager_plugin_prefix."active_until"]);
                      }else{
                          $errors[] = "active_until";
                      }
                  }else{
                      $active_until = -1;
                  }
                  if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_order"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_order"])>=0){
                      $banner_order = intval($_POST[$useful_banner_manager_plugin_prefix."banner_order"]);
                  }else{
                      $errors[] = "banner_order";
                  }
                  switch($_POST[$useful_banner_manager_plugin_prefix."is_visible"]){
                      case "yes":
                      $is_visible = "yes";
                      break;
                      case "no":
                      $is_visible = "no";
                      break;
                      default:
                      $is_visible = "yes";
                  }
                  $current_user = wp_get_current_user();
                  $banner_edited_by = $current_user->user_login;
                  $last_edited_date = date("Y-m-d");

                  if(empty($errors)){
                      useful_banner_manager_update_banner($banner_id,$banner_name,$banner_type,$banner_title,$banner_alt,$banner_link,$link_target,$link_rel,$banner_width,$banner_height,$active_until,$banner_order,$is_visible,$banner_edited_by,$last_edited_date);
                      if($_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["error"]==0){
                          if(file_exists(ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$banner_old_file)){
                            unlink(ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$banner_old_file);
                          }

                          move_uploaded_file($banner_tmp_file,ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$banner_id."-".$banner_name.".".$banner_type);
                      }

                      echo('<div id="message" class="updated fade"><p><strong>The banner is edited.</strong></p></div>');

                  }else{
                      echo('<div id="message" class="updated fade"><p><strong>The following field');
                      if(count($errors)>1){
                        echo('s are');
                      }else{
                        echo(' is');
                      }
                      echo(' wrong:');
                      foreach($errors as $error){
                        echo(' "'.ucwords(str_replace("_"," ",$error)).'"');
                      }
                      echo('.</strong></p></div>');
                  }
              }
          }

          $banner_id = intval($_GET[$useful_banner_manager_plugin_prefix."banner_id"]);
          $banner = useful_banner_manager_get_banner($banner_id);

          if(!empty($banner)){
          ?>
          <form method="post" enctype="multipart/form-data">
            <table id="useful_banner_manager_edit_banner">
                <tr>
                  <td colspan="2"><h3>Edit the banner "<?php echo($banner->banner_title); ?>"</h3></td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Banner File</strong></td>
                    <td width="75%">
                        <p>
                        <?php
                        if($banner->banner_type!="swf"){
                        ?>
                          	<img src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>" alt="<?php echo($banner->banner_alt); ?>" />
                          <?php
                          }else{
                          ?>
                              <object width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                                  <param name="movie" value="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>">
                                  <embed src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                                  </embed>
                              </object>
                        <?php
                        }
                        ?>
                        </p>
                        <input type="hidden" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_name" value="<?php echo($banner->banner_name); ?>" />
                        <input type="hidden" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_type" value="<?php echo($banner->banner_type); ?>" />
                        <input type="file" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_file" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_file" /> <small>The banner type can be jpg, jpeg, gif, png or swf.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Banner Title</strong></td>
                    <td width="75%">
                        <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_title" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_title" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_title.'"'); }else{ echo('value="'.$banner->banner_title.'"'); } ?> /> (required)
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Image Alt</strong></td>
                    <td width="75%">
                        <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_alt" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_alt" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_alt.'"'); }else{ echo('value="'.$banner->banner_alt.'"'); } ?> /> <small>Not for swf files.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Banner Link</strong></td>
                    <td width="75%">
                        <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_link" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_link" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_link.'"'); }else{ echo('value="'.$banner->banner_link.'"'); } ?> /> <small>Not for swf files.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Link Target</strong></td>
                    <td width="75%">
                        <select id="<?php echo($useful_banner_manager_plugin_prefix); ?>link_target" name="<?php echo($useful_banner_manager_plugin_prefix); ?>link_target" style="width: 80px">
                            <option value="_self" <?php if(isset($errors) && !empty($errors) && $link_target=="_self"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_target=="_self"){ echo('selected="selected"'); } ?>>_self</option>
                            <option value="_top" <?php if(isset($errors) && !empty($errors) && $link_target=="_top"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_target=="_top"){ echo('selected="selected"'); } ?>>_top</option>
                            <option value="_blank" <?php if(isset($errors) && !empty($errors) && $link_target=="_blank"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_target=="_blank"){ echo('selected="selected"'); } ?>>_blank</option>
                            <option value="_parent" <?php if(isset($errors) && !empty($errors) && $link_target=="_parent"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_target=="_parent"){ echo('selected="selected"'); } ?>>_parent</option>
                        </select> <small>Not for swf files.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Link Rel</strong></td>
                    <td width="75%">
                        <select id="<?php echo($useful_banner_manager_plugin_prefix); ?>link_rel" name="<?php echo($useful_banner_manager_plugin_prefix); ?>link_rel" style="width: 80px">
                            <option value="dofollow" <?php if(isset($errors) && !empty($errors) && $link_target=="dofollow"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_rel=="dofollow"){ echo('selected="selected"'); } ?>>dofollow</option>
                            <option value="nofollow" <?php if(isset($errors) && !empty($errors) && $link_target=="nofollow"){ echo('selected="selected"'); }elseif((!isset($errors) || empty($errors)) && $banner->link_rel=="nofollow"){ echo('selected="selected"'); } ?>>nofollow</option>
                        </select> <small>Not for swf files.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Banner Sizes</strong></td>
                    <td width="75%">
                        <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes">Auto:</label> <input type="checkbox" name="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes" id="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes" onclick="if(this.checked){ document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width').setAttribute('disabled','disabled'); document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height').setAttribute('disabled','disabled'); }else{ document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width').removeAttribute('disabled'); document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height').removeAttribute('disabled'); } " <?php if(isset($errors) && !empty($errors) && isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('checked="checked"'); } ?> /> <small>Check this to set the original sizes of the banner, not for swf files.</small>
                        <table>
                          <tr>
                            <td><label for="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width">Width:</label></td>
                            <td><input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width" style="width: 50px" <?php if(isset($errors) && !empty($errors) && !isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('value="'.$banner_width.'"'); }else{ echo('value="'.$banner->banner_width.'"'); } ?> />px (required if the banner is swf file)</td>
                          </tr>
                          <tr>
                            <td><label for="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height">Height:</label></td>
                            <td><input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height" style="width: 50px" <?php if(isset($errors) && !empty($errors) && !isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('value="'.$banner_height.'"'); }else{ echo('value="'.$banner->banner_height.'"'); } ?> />px (required if the banner is swf file)</td>
                          </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Active Until</strong></td>
                    <td width="75%">
                        <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>active_until" id="<?php echo($useful_banner_manager_plugin_prefix); ?>active_until" style="width: 100px" <?php if(isset($errors) && !empty($errors)){ if(in_array("active_until",$errors)){ echo('value="'.htmlentities(stripslashes($_POST[$useful_banner_manager_plugin_prefix."active_until"]),ENT_QUOTES).'"'); }elseif($active_until!=-1){ echo('value="'.$active_until.'"'); } }elseif($banner->active_until!=-1){ echo('value="'.$banner->active_until.'"'); } ?> /> <small>Date format is YYYY-MM-DD. Leave empty if there is no date.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Banner Order</strong></td>
                    <td width="75%">
                        <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_order" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_order" style="width: 50px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.htmlentities(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_order"]),ENT_QUOTES).'"'); }else{ echo('value="'.$banner->banner_order.'"'); } ?> /> <small>Set the number depends on which the banner will be shown on more top places.</small>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="middle"><strong>Is Visible</strong></td>
                    <td width="75%">
                        <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>yes">Yes:</label><input type="radio" name="<?php echo($useful_banner_manager_plugin_prefix); ?>is_visible" id="<?php echo($useful_banner_manager_plugin_prefix); ?>yes" value="yes" <?php if(isset($errors) && !empty($errors) && $is_visible!="no"){ echo('checked="checked"'); }elseif((!isset($errors) || empty($errors)) && $banner->is_visible=="yes"){ echo('checked="checked"'); } ?> /> <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>no">No:</label><input type="radio" name="<?php echo($useful_banner_manager_plugin_prefix); ?>is_visible" id="<?php echo($useful_banner_manager_plugin_prefix); ?>no" value="no" <?php if(isset($errors) && !empty($errors) && $is_visible=="no"){ echo('checked="checked"'); }elseif((!isset($errors) || empty($errors)) && $banner->is_visible=="no"){ echo('checked="checked"'); } ?> />
                    </td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
            </table>
            <p class="submit">
                <input name="<?php echo($useful_banner_manager_plugin_prefix); ?>save_banner" type="submit" value="Save" /> <a href="admin.php?page=useful-banner-manager.php">Cancel</a>
            </p>
          </form>
      <?php
          }else{
                echo("<p>The banner ID is wrong.</p>");
          }
      }else{
        if($_GET["page"]==basename(__FILE__)){
            if(isset($_POST[$useful_banner_manager_plugin_prefix."add_banner"])){
                $errors = array();
                $banner_name_parts = explode(".",$_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["name"]);
                array_pop($banner_name_parts);
                $banner_name = implode(".",$banner_name_parts);
                $banner_type = array_pop(explode(".",$_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["name"]));
                if(strtolower($banner_type)!="jpg" && strtolower($banner_type)!="jpeg" && strtolower($banner_type)!="gif" && strtolower($banner_type)!="png" && strtolower($banner_type)!="swf"){
                    $errors[] = "banner_type";
                }
                $banner_tmp_file = $_FILES[$useful_banner_manager_plugin_prefix."banner_file"]["tmp_name"];
                if(trim($_POST[$useful_banner_manager_plugin_prefix."banner_title"])==""){
                    $errors[] = "banner_title";
                }else{
                    $banner_title = htmlentities(trim(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_title"])),ENT_QUOTES);
                }

                $banner_alt = htmlentities(trim(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_alt"])),ENT_QUOTES);

                $banner_link = trim(str_replace(array("\"","'"),array("",""),stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_link"])));
                if($banner_link!=""){
                    switch($_POST[$useful_banner_manager_plugin_prefix."link_target"]){
                        case "_self":
                        $link_target = "_self";
                        break;
                        case "_top":
                        $link_target = "_top";
                        break;
                        case "_blank":
                        $link_target = "_blank";
                        break;
                        case "_parent":
                        $link_target = "_parent";
                        break;
                        default:
                        $link_target = "_self";
                    }
                }else{
                    $link_target = "";
                }

                if($banner_link!=""){
                      switch($_POST[$useful_banner_manager_plugin_prefix."link_rel"]){
                          case "nofollow":
                          $link_rel = "nofollow";
                          break;
                          default:
                          $link_rel = "dofollow";
                      }
                  }else{
                      $link_rel = "";
                  }

                if(isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"]) && !in_array("banner_type",$errors)){
                    if($banner_type!="swf"){
                      list($banner_width,$banner_height) = getimagesize($banner_tmp_file);
                    }else{
                      $errors[] = "swf_auto_sizes";
                    }
                }elseif(!isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){
                    if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_width"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_width"])>0){
                        $banner_width = intval($_POST[$useful_banner_manager_plugin_prefix."banner_width"]);
                    }else{
                        $errors[] = "banner_width";
                    }
                    if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_height"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_height"])>0){
                        $banner_height = intval($_POST[$useful_banner_manager_plugin_prefix."banner_height"]);
                    }else{
                        $errors[] = "banner_height";
                    }
                }
                $added_date = date("Y-m-d");
                if($_POST[$useful_banner_manager_plugin_prefix."active_until"]!=""){
                    if(preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/",trim($_POST[$useful_banner_manager_plugin_prefix."active_until"])) && trim($_POST[$useful_banner_manager_plugin_prefix."active_until"])>=date("Y-m-d")){
                        $active_until = trim($_POST[$useful_banner_manager_plugin_prefix."active_until"]);
                    }else{
                        $errors[] = "active_until";
                    }
                }else{
                    $active_until = -1;
                }
                if(is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_order"]) && intval($_POST[$useful_banner_manager_plugin_prefix."banner_order"])>=0){
                    $banner_order = intval($_POST[$useful_banner_manager_plugin_prefix."banner_order"]);
                }else{
                    $errors[] = "banner_order";
                }
                switch($_POST[$useful_banner_manager_plugin_prefix."is_visible"]){
                    case "yes":
                    $is_visible = "yes";
                    break;
                    case "no":
                    $is_visible = "no";
                    break;
                    default:
                    $is_visible = "yes";
                }
                $current_user = wp_get_current_user();
                $banner_added_by = $current_user->user_login;

                if(empty($errors)){
                    $added_banner_id = useful_banner_manager_add_banner($banner_name,$banner_type,$banner_title,$banner_alt,$banner_link,$link_target,$link_rel,$banner_width,$banner_height,$added_date,$active_until,$banner_order,$is_visible,$banner_added_by);
                    move_uploaded_file($banner_tmp_file,ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$added_banner_id."-".$banner_name.".".$banner_type);
                    echo('<div id="message" class="updated fade"><p><strong>New banner added.</strong></p></div>');

                }else{
                    echo('<div id="message" class="updated fade"><p><strong>The following field');
                    if(count($errors)>1){
                      echo('s are');
                    }else{
                      echo(' is');
                    }
                    echo(' wrong:');
                    foreach($errors as $error){
                      echo(' "'.ucwords(str_replace("_"," ",$error)).'"');
                    }
                    echo('.</strong></p></div>');
                }
            }

            if(isset($_POST[$useful_banner_manager_plugin_prefix."delete"])){
                if(isset($_POST[$useful_banner_manager_plugin_prefix."banner_id"]) && is_numeric($_POST[$useful_banner_manager_plugin_prefix."banner_id"])){
                    $banner_id = intval($_POST[$useful_banner_manager_plugin_prefix."banner_id"]);

                    useful_banner_manager_delete_banner($banner_id);

                    echo('<div id="message" class="updated fade"><p><strong>The banner is deleted.</strong></p></div>');
                }
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
          <table id="useful_banner_manager_add_banner">
              <tr>
                <td colspan="2"><h3>Add banner</h3></td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Banner File</strong></td>
                  <td width="75%">
                      <input type="file" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_file" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_file" /> (required) <small>The banner type can be jpg, jpeg, gif, png or swf.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Banner Title</strong></td>
                  <td width="75%">
                      <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_title" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_title" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_title.'"'); } ?> /> (required)
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Image Alt</strong></td>
                  <td width="75%">
                      <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_alt" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_alt" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_alt.'"'); } ?> /> <small>Not for swf files.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Banner Link</strong></td>
                  <td width="75%">
                      <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_link" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_link" style="width: 300px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.$banner_link.'"'); } ?> /> <small>Not for swf files.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Link Target</strong></td>
                  <td width="75%">
                      <select id="<?php echo($useful_banner_manager_plugin_prefix); ?>link_target" name="<?php echo($useful_banner_manager_plugin_prefix); ?>link_target" style="width: 80px">
                          <option value="_self" <?php if(isset($errors) && !empty($errors) && $link_target=="_self"){ echo('selected="selected"'); }elseif(!isset($errors) || empty($errors)){ echo('selected="selected"'); } ?>>_self</option>
                          <option value="_top" <?php if(isset($errors) && !empty($errors) && $link_target=="_top"){ echo('selected="selected"'); } ?>>_top</option>
                          <option value="_blank" <?php if(isset($errors) && !empty($errors) && $link_target=="_blank"){ echo('selected="selected"'); } ?>>_blank</option>
                          <option value="_parent" <?php if(isset($errors) && !empty($errors) && $link_target=="_parent"){ echo('selected="selected"'); } ?>>_parent</option>
                      </select> <small>Not for swf files.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Link Rel</strong></td>
                  <td width="75%">
                      <select id="<?php echo($useful_banner_manager_plugin_prefix); ?>link_rel" name="<?php echo($useful_banner_manager_plugin_prefix); ?>link_rel" style="width: 80px">
                          <option value="dofollow" <?php if(isset($errors) && !empty($errors) && $link_rel=="dofollow"){ echo('selected="selected"'); }elseif(!isset($errors) || empty($errors)){ echo('selected="selected"'); } ?>>dofollow</option>
                          <option value="nofollow" <?php if(isset($errors) && !empty($errors) && $link_rel=="nofollow"){ echo('selected="selected"'); } ?>>nofollow</option>
                      </select> <small>Not for swf files.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Banner Sizes</strong></td>
                  <td width="75%">
                      <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes">Auto:</label> <input type="checkbox" name="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes" id="<?php echo($useful_banner_manager_plugin_prefix); ?>auto_sizes" onclick="if(this.checked){ document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width').setAttribute('disabled','disabled'); document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height').setAttribute('disabled','disabled'); }else{ document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width').removeAttribute('disabled'); document.getElementById('<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height').removeAttribute('disabled'); } " <?php if(isset($errors) && !empty($errors) && isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('checked="checked"'); } ?> /> <small>Check this to set the original sizes of the banner, not for swf files.</small>
                      <table>
                        <tr>
                          <td><label for="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width">Width:</label></td>
                          <td><input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_width" style="width: 50px" <?php if(isset($errors) && !empty($errors) && !isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('value="'.$banner_width.'"'); } ?> />px (required if the banner is swf file)</td>
                        </tr>
                        <tr>
                          <td><label for="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height">Height:</label></td>
                          <td><input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_height" style="width: 50px" <?php if(isset($errors) && !empty($errors) && !isset($_POST[$useful_banner_manager_plugin_prefix."auto_sizes"])){ echo('value="'.$banner_height.'"'); } ?> />px (required if the banner is swf file)</td>
                        </tr>
                      </table>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Active Until</strong></td>
                  <td width="75%">
                      <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>active_until" id="<?php echo($useful_banner_manager_plugin_prefix); ?>active_until" style="width: 100px" <?php if(isset($errors) && !empty($errors)){ if(in_array("active_until",$errors)){ echo('value="'.htmlentities(stripslashes($_POST[$useful_banner_manager_plugin_prefix."active_until"]),ENT_QUOTES).'"'); }elseif($active_until!=-1){ echo('value="'.$active_until.'"'); } } ?> /> <small>Date format is YYYY-MM-DD. Leave empty if there is no date.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Banner Order</strong></td>
                  <td width="75%">
                      <input type="text" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_order" id="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_order" style="width: 50px" <?php if(isset($errors) && !empty($errors)){ echo('value="'.htmlentities(stripslashes($_POST[$useful_banner_manager_plugin_prefix."banner_order"]),ENT_QUOTES).'"'); }else{ echo('value="0"'); } ?> /> <small>Set the number depends on which the banner will be shown on more top places.</small>
                  </td>
              </tr>
              <tr>
                  <td width="25%" valign="middle"><strong>Is Visible</strong></td>
                  <td width="75%">
                      <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>yes">Yes:</label><input type="radio" name="<?php echo($useful_banner_manager_plugin_prefix); ?>is_visible" id="<?php echo($useful_banner_manager_plugin_prefix); ?>yes" value="yes" <?php if(isset($errors) && !empty($errors) && $is_visible!="no"){ echo('checked="checked"'); }elseif(!isset($errors) || empty($errors)){ echo('checked="checked"'); } ?> /> <label for="<?php echo($useful_banner_manager_plugin_prefix); ?>no">No:</label><input type="radio" name="<?php echo($useful_banner_manager_plugin_prefix); ?>is_visible" id="<?php echo($useful_banner_manager_plugin_prefix); ?>no" value="no" <?php if(isset($errors) && !empty($errors) && $is_visible=="no"){ echo('checked="checked"'); } ?> />
                  </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
          </table>
          <p class="submit">
              <input name="<?php echo($useful_banner_manager_plugin_prefix); ?>add_banner" type="submit" value="Add banner" />
          </p>
        </form>
        <br />
        <?php $banners = useful_banner_manager_get_banners(); ?>
        <style>
          .widefat td{
          	padding: 3px 7px;
          	vertical-align: middle;
          }

          .widefat tbody th.check-column{
          	padding: 7px 0;
              vertical-align: middle;
          }
        </style>
        <h3>Manage banners</h3>
        <table class="widefat fixed" cellspacing="0" id="useful_banner_manager_manage_banners" width="100%">
              <thead>
              	<tr>
                    <th scope="col" width="3%">ID</th>
                    <th scope="col" width="5%">Type</th>
                    <th scope="col" width="12%">Title</th>
                    <th scope="col" width="23%">Link</th>
                    <th scope="col" width="6%">Rel</th>
                    <th scope="col" width="9%">Added Date</th>
                    <th scope="col" width="9%">Active Until</th>
                    <th scope="col" width="5%">Order</th>
                    <th scope="col" width="7%">Is Visible</th>
                    <th scope="col" width="8%">Added By</th>
                    <th scope="col" width="5%"></th>
                    <th scope="col" width="8%"></th>
              	</tr>
          	</thead>
          	<tfoot>
              	<tr>
                    <th scope="col">ID</th>
                    <th scope="col">Type</th>
                    <th scope="col">Title</th>
                    <th scope="col">Link</th>
                    <th scope="col">Rel</th>
                    <th scope="col">Added Date</th>
                    <th scope="col">Active Until</th>
                    <th scope="col">Order</th>
                    <th scope="col">Is Visible</th>
                    <th scope="col">Added By</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
              	</tr>
          	</tfoot>
            <tbody>
            <?php
            foreach($banners as $banner){
            ?>
                <tr class="alternate">
                    <td><?php echo($banner->id); ?></td>
                    <td><?php echo($banner->banner_type); ?></td>
                    <td><?php echo($banner->banner_title); ?></td>
                    <td><?php echo($banner->banner_link); ?></td>
                    <td><?php echo($banner->link_rel); ?></td>
                    <td><?php echo($banner->added_date); ?></td>
                    <td><?php if($banner->active_until==-1){ echo("No date"); }else{ echo($banner->active_until); } ?></td>
                    <td><?php echo($banner->banner_order); ?></td>
                    <td><?php echo($banner->is_visible); ?></td>
                    <td><?php echo($banner->banner_added_by); ?></td>
                    <td>
                      <form method="get">
                          <p class="submit">
                            <input type="submit" value="Edit" />
                            <input type="hidden" name="page" value="useful-banner-manager.php" />
                            <input type="hidden" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_id" value="<?php echo($banner->id); ?>" />
                          </p>
                      </form>
                    </td>
                    <td>
                      <form method="post">
                          <p class="submit">
                            <input name="<?php echo($useful_banner_manager_plugin_prefix); ?>delete" type="submit" value="Delete" onclick="javascript:if(!confirm('Are you sure you want to delete the banner &quot;<?php echo($banner->banner_title); ?>&quot;?')){ return false; }" />
                            <input type="hidden" name="<?php echo($useful_banner_manager_plugin_prefix); ?>banner_id" value="<?php echo($banner->id); ?>" />
                          </p>
                      </form>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
      <?php
      }
      ?>
      <br />
      <p><strong style="color: #FF0000">To have more features in this plugin (statistics of the impressions and the clicks of the banners, etc.) get the premium version of it. Read more about the premium version on its <a href="http://rubensargsyan.com/wordpress-plugin-ubm-premium/" target="_blank">homepage</a>.</strong></p>
    </div>
    <?php
}

function useful_banner_manager_add_banner($banner_name,$banner_type,$banner_title,$banner_alt,$banner_link,$link_target,$link_rel,$banner_width,$banner_height,$added_date,$active_until,$banner_order,$is_visible,$banner_added_by){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "INSERT INTO ".$useful_banner_manager_table_name." (banner_name,banner_type,banner_title,banner_alt,banner_link,link_target,link_rel,banner_width,banner_height,added_date,active_until,banner_order,is_visible,banner_added_by) VALUES ('".$banner_name."','".$banner_type."','".$banner_title."','".$banner_alt."','".$banner_link."','".$link_target."','".$link_rel."','".$banner_width."','".$banner_height."','".$added_date."','".$active_until."','".$banner_order."','".$is_visible."','".$banner_added_by."');";
    $wpdb->query($query);

    $banner_id = $wpdb->insert_id;

    return $banner_id;
}

function useful_banner_manager_update_banner($banner_id,$banner_name,$banner_type,$banner_title,$banner_alt,$banner_link,$link_target,$link_rel,$banner_width,$banner_height,$active_until,$banner_order,$is_visible,$banner_edited_by,$last_edited_date){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "UPDATE ".$useful_banner_manager_table_name." SET banner_name='".$banner_name."', banner_type='".$banner_type."', banner_title='".$banner_title."', banner_alt='".$banner_alt."', banner_link='".$banner_link."', link_target='".$link_target."', link_rel='".$link_rel."', banner_width='".$banner_width."', banner_height='".$banner_height."', active_until='".$active_until."', banner_order='".$banner_order."', is_visible='".$is_visible."', banner_edited_by='".$banner_edited_by."', last_edited_date='".$last_edited_date."' WHERE id='".$banner_id."';";
    $wpdb->query($query);
}

function useful_banner_manager_delete_banner($banner_id){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "SELECT banner_name,banner_type FROM ".$useful_banner_manager_table_name." WHERE id=".$banner_id.";";
    $banner = $wpdb->get_row($query);

    $delete_query = "DELETE FROM ".$useful_banner_manager_table_name." WHERE id=".$banner_id.";";
    $wpdb->query($delete_query);

    if(file_exists(ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$banner_id."-".$banner->banner_name.".".$banner->banner_type)){
        unlink(ABSPATH."wp-content/uploads/useful_banner_manager_banners/".$banner_id."-".$banner->banner_name.".".$banner->banner_type);
    }
}

function useful_banner_manager_get_banners(){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "SELECT id,banner_type,banner_title,banner_link,link_rel,added_date,active_until,banner_order,is_visible,banner_added_by FROM ".$useful_banner_manager_table_name." ORDER BY id ASC;";
    $banners = $wpdb->get_results($query);

    return $banners;
}

function useful_banner_manager_get_banner($banner_id){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "SELECT * FROM ".$useful_banner_manager_table_name." WHERE id='".$banner_id."';";
    $banner = $wpdb->get_row($query);

    return $banner;
}

function useful_banner_manager_get_available_years(){
    global $wpdb, $useful_banner_manager_table_name;

    $query = "SELECT MIN(added_date) as earliest_date FROM ".$useful_banner_manager_table_name.";";
    $earliest_date = $wpdb->get_row($query);
    $earliest_date = substr($earliest_date->earliest_date,0,4);

    $available_years = array();

    for($i=date("Y"); $i>=$earliest_date; $i--){
        $available_years[] = $i;
    }

    return $available_years;
}

class Useful_Banner_Manager_Widget extends WP_Widget{
     function Useful_Banner_Manager_Widget(){
        $widget_opions = array('classname' => 'useful_banner_manager_widget', 'description' => __('Useful banner manager banners'));
		$this->WP_Widget('useful-banner-manager-banners', 'UBM banners', $widget_opions);
     }

     function widget($args, $instance){
        global $wpdb, $useful_banner_manager_table_name;

        extract($args);

        $title = $instance["title"];
        $banners_ids = $instance["banners_ids"];
        $count = $instance["count"];

        echo($before_widget);
        if(!empty($title)){
            echo($before_title.$title.$after_title);
        }

        if(!empty($banners_ids)){
            $query = "SELECT * FROM (SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
            $banners_ids_query = array();

            foreach($banners_ids as $banner_id){
                $banners_ids_query[] = "id='".$banner_id."'";
            }

            $query .= implode(" OR ",$banners_ids_query);

            $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND is_visible='yes' ORDER BY RAND() LIMIT ".$count.") as banners ORDER BY banner_order DESC;";

            $banners = $wpdb->get_results($query);

            foreach($banners as $banner){
            ?>
                <div class="useful_banner_manager_banner">
                <?php
                if($banner->banner_type!="swf"){
                    if($banner->banner_link!=""){
                    ?>
                        <a href="<?php echo($banner->banner_link); ?>" target="<?php echo($banner->link_target); ?>" rel="<?php echo($banner->link_rel); ?>">
                    <?php
                    }
                ?>
                	<img src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>" alt="<?php echo($banner->banner_alt); ?>" />
                    <?php
                    if($banner->banner_link!=""){
                    ?>
                        </a>
                    <?php
                    }
                }else{
                ?>
                    <object width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                        <param name="movie" value="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>">
                        <embed src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                        </embed>
                    </object>
                <?php
                }
                ?>
                </div>
            <?php
            }
        }

        echo($after_widget);
     }

     function update($new_instance, $old_instance){
        $instance = $old_instance;
		$instance["title"] = strip_tags($new_instance["title"]);
		$instance["banners_ids"] = $new_instance["banners_ids"];
        if(is_numeric($new_instance["count"]) && intval($new_instance["count"])>0){
            $instance["count"] = intval($new_instance["count"]);
        }elseif(is_numeric($old_instance["count"]) && intval($old_instance["count"])>0){
            $instance["count"] = intval($old_instance["count"]);
        }else{
            $instance["count"] = 1;
        }

		return $instance;
     }

     function form($instance){
        global $wpdb, $useful_banner_manager_table_name;

        $instance = wp_parse_args((array)$instance,array("title"=>"","banners_ids"=>""));
		$title = strip_tags($instance["title"]);
		$banners_ids = $instance["banners_ids"];
        if($instance["count"]){
            $count =  intval($instance["count"]);
        }else{
            $count =  1;
        }


        $banners = $wpdb->get_results("SELECT id, banner_name, banner_type, banner_title FROM ".$useful_banner_manager_table_name." WHERE is_visible='yes' ORDER BY id ASC;");

        if($banners){
            ?>
            <p><label for="<?php echo($this->get_field_id("title")); ?>">Title:</label>
        	<input class="widefat" id="<?php echo($this->get_field_id("title")); ?>" name="<?php echo($this->get_field_name("title")); ?>" type="text" value="<?php echo(esc_attr($title)); ?>" /></p>
            <table width="100%" style="border-collapse: collapse">
            <caption>Banners</caption>
            <?php
            foreach($banners as $banner){
            ?>
                <tr><td width="90%" style="border: 1px solid #f1f1f1; text-align: left; padding: 2px 5px"><label for="<?php echo($this->get_field_id("banners_ids")); ?>_<?php echo($banner->id); ?>"><?php echo($banner->banner_title); ?></label></td><td width="10%" style="border: 1px solid #f1f1f1; text-align: center; padding: 2px 0"><input class="checkbox" id="<?php echo($this->get_field_id("banners_ids")); ?>_<?php echo($banner->id); ?>" name="<?php echo($this->get_field_name("banners_ids")); ?>[]" type="checkbox" value="<?php echo($banner->id); ?>" <?php if(is_array($banners_ids)){ if(in_array($banner->id,$banners_ids)){ echo('checked="checked"'); } } ?> /></td></tr>
            <?php
            }
            ?>
            </table><br />
            <p><label for="<?php echo($this->get_field_id("count")); ?>">Number of banners to show:</label>
        	<input id="<?php echo($this->get_field_id("count")); ?>" name="<?php echo($this->get_field_name("count")); ?>" type="text" value="<?php echo(esc_attr($count)); ?>" size="2" /></p>
            <?php
        }else{
        ?>
            <p>There is no visible banner. <a href="admin.php?page=useful-banner-manager.php">Settings</a></p>
        <?php
        }
     }
}

class Useful_Banner_Manager_Rotation_Widget extends WP_Widget{
     function Useful_Banner_Manager_Rotation_Widget(){
        $widget_opions = array('classname' => 'useful_banner_manager_rotation_widget', 'description' => __('Useful banner manager banners rotation'));
		$this->WP_Widget('useful-banner-manager-banners-rotation', 'UBM banners rotation', $widget_opions);
     }

     function widget($args, $instance){
        global $wpdb, $useful_banner_manager_table_name;

        extract($args);

        $title = $instance["title"];
        $banners_ids = $instance["banners_ids"];
        $interval = $instance["interval"];
        $width = $instance["width"];
        $height = $instance["height"];
        if($instance["orderby"]=="rand"){
            $orderby = "RAND()";
        }else{
            $orderby = "banner_order, id DESC";
        }


        echo($before_widget);
        if(!empty($title)){
            echo($before_title.$title.$after_title);
        }

        if(!empty($banners_ids)){
            $query = "SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
            $banners_ids_query = array();

            foreach($banners_ids as $banner_id){
                $banners_ids_query[] = "id='".$banner_id."'";
            }

            $query .= implode(" OR ",$banners_ids_query);

            $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND is_visible='yes' ORDER BY ".$orderby.";";

            $banners = $wpdb->get_results($query);

            ?>
            <div id="<?php echo($args["widget_id"]); ?>" class="useful_banner_manager_banners_rotation" style="overflow: hidden; width: <?php echo($width); ?>px; height: <?php echo($height); ?>px;">
            <?php
            $first_banner = true;
            foreach($banners as $banner){
                ?>
                <div class="useful_banner_manager_rotating_banner"<?php if($first_banner){ $first_banner = false; }else{ echo(' style="display: none"'); } ?>>
                    <?php
                    if($banner->banner_link!=""){
                    ?>
                        <a href="<?php echo($banner->banner_link); ?>" target="<?php echo($banner->link_target); ?>" rel="<?php echo($banner->link_rel); ?>">
                    <?php
                    }
                ?>
                	<img src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($width); ?>" height="<?php echo($height); ?>" alt="<?php echo($banner->banner_alt); ?>" />
                    <?php
                    if($banner->banner_link!=""){
                    ?>
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            </div>
            <script type="text/javascript">
            jQuery(function($){
                $(document).ready(function(){
                    var useful_banner_manager_banners_rotation_block = "<?php echo($args['widget_id']); ?>";
                    var interval_between_rotations = <?php echo(($interval*1000)); ?>;
                    if($("#"+useful_banner_manager_banners_rotation_block+" .useful_banner_manager_rotating_banner").length>1){
                        setTimeout("useful_banner_manager_rotate_banners('"+useful_banner_manager_banners_rotation_block+"',"+interval_between_rotations+")",interval_between_rotations);
                    }
                });
            });
            </script>
            <?php
        }

        echo($after_widget);
     }

     function update($new_instance, $old_instance){
        $instance = $old_instance;
		$instance["title"] = strip_tags($new_instance["title"]);
		$instance["banners_ids"] = $new_instance["banners_ids"];
        if(is_numeric($new_instance["interval"]) && intval($new_instance["interval"])>0){
            $instance["interval"] = intval($new_instance["interval"]);
        }elseif(is_numeric($old_instance["interval"]) && intval($old_instance["interval"])>0){
            $instance["interval"] = intval($old_instance["interval"]);
        }else{
            $instance["interval"] = 10;
        }
        if(is_numeric($new_instance["width"]) && intval($new_instance["width"])>0){
            $instance["width"] = intval($new_instance["width"]);
        }elseif(is_numeric($old_instance["width"]) && intval($old_instance["width"])>0){
            $instance["width"] = intval($old_instance["width"]);
        }else{
            $instance["width"] = 180;
        }
        if(is_numeric($new_instance["height"]) && intval($new_instance["height"])>0){
            $instance["height"] = intval($new_instance["height"]);
        }elseif(is_numeric($old_instance["height"]) && intval($old_instance["height"])>0){
            $instance["height"] = intval($old_instance["height"]);
        }else{
            $instance["height"] = 180;
        }
        if($new_instance["orderby"]=="rand"){
            $instance["orderby"] = "rand";
        }else{
            $instance["orderby"] = "banner_order, id";
        }


		return $instance;
     }

     function form($instance){
        global $wpdb, $useful_banner_manager_table_name;

        $instance = wp_parse_args((array)$instance,array("title"=>"","banners_ids"=>""));
		$title = strip_tags($instance["title"]);
		$banners_ids = $instance["banners_ids"];
        if($instance["interval"]){
            $interval =  intval($instance["interval"]);
        }else{
            $interval =  10;
        }
        if($instance["width"]){
            $width =  intval($instance["width"]);
        }else{
            $width =  180;
        }
        if($instance["height"]){
            $height =  intval($instance["height"]);
        }else{
            $height =  180;
        }


        $banners = $wpdb->get_results("SELECT id, banner_name, banner_type, banner_title FROM ".$useful_banner_manager_table_name." WHERE is_visible='yes' ORDER BY id ASC;");

        if($banners){
            ?>
            <p><label for="<?php echo($this->get_field_id("title")); ?>">Title:</label>
        	<input class="widefat" id="<?php echo($this->get_field_id("title")); ?>" name="<?php echo($this->get_field_name("title")); ?>" type="text" value="<?php echo(esc_attr($title)); ?>" /></p>
            <table width="100%" style="border-collapse: collapse">
            <caption>Banners</caption>
            <?php
            foreach($banners as $banner){
                if($banner->banner_type=="swf"){
                    continue;
                }
            ?>
                <tr><td width="90%" style="border: 1px solid #f1f1f1; text-align: left; padding: 2px 5px"><label for="<?php echo($this->get_field_id("banners_ids")); ?>_<?php echo($banner->id); ?>"><?php echo($banner->banner_title); ?></label></td><td width="10%" style="border: 1px solid #f1f1f1; text-align: center; padding: 2px 0"><input class="checkbox" id="<?php echo($this->get_field_id("banners_ids")); ?>_<?php echo($banner->id); ?>" name="<?php echo($this->get_field_name("banners_ids")); ?>[]" type="checkbox" value="<?php echo($banner->id); ?>" <?php if(is_array($banners_ids)){ if(in_array($banner->id,$banners_ids)){ echo('checked="checked"'); } } ?> /></td></tr>
            <?php
            }
            ?>
            </table><br />
            <p><label for="<?php echo($this->get_field_id("interval")); ?>">Interval:</label>
        	<input id="<?php echo($this->get_field_id("interval")); ?>" name="<?php echo($this->get_field_name("interval")); ?>" type="text" value="<?php echo(esc_attr($interval)); ?>" size="2" /> seconds</p>
            <p><label for="<?php echo($this->get_field_id("width")); ?>">Width of rotating banners:</label>
        	<input id="<?php echo($this->get_field_id("width")); ?>" name="<?php echo($this->get_field_name("width")); ?>" type="text" value="<?php echo(esc_attr($width)); ?>" size="2" /></p>
            <p><label for="<?php echo($this->get_field_id("height")); ?>">Height of rotating banners:</label>
        	<input id="<?php echo($this->get_field_id("height")); ?>" name="<?php echo($this->get_field_name("height")); ?>" type="text" value="<?php echo(esc_attr($height)); ?>" size="2" /></p>
            <p><label for="<?php echo($this->get_field_id("orderby")); ?>">Order by rand:</label>
            <input class="checkbox" id="<?php echo($this->get_field_id("orderby")); ?>" name="<?php echo($this->get_field_name("orderby")); ?>" type="checkbox" value="rand" <?php if($instance["orderby"]=="rand"){ echo('checked="checked"'); } ?> />
            </p>
            <?php
        }else{
        ?>
            <p>There is no visible banner. <a href="admin.php?page=useful-banner-manager.php">Settings</a></p>
        <?php
        }
     }
}

function useful_banner_manager_widget_init(){
	if(!is_blog_installed()){
	    return;
	}

    register_widget('Useful_Banner_Manager_Widget');
    register_widget('Useful_Banner_Manager_Rotation_Widget');
}

$banners_rotation_id = 1;

function add_useful_banner_manager_banners($content){
    global $wpdb, $useful_banner_manager_table_name, $banners_rotation_id;

    if(preg_match_all("/\[useful_banner_manager banners=(.[^\]]*) count=([0-9]+)\]/i",$content,$matches,PREG_SET_ORDER)){
        foreach($matches as $match){
            $banners_ids = explode(",",$match[1]);
            $count = $match[2];

            $query = "SELECT * FROM (SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
            $banners_ids_query = array();

            foreach($banners_ids as $banner_id){
                $banners_ids_query[] = "id='".trim($banner_id)."'";
            }

            $query .= implode(" OR ",$banners_ids_query);

            $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND is_visible='yes' ORDER BY RAND() LIMIT ".$count.") as banners ORDER BY banner_order DESC;";

            $banners = $wpdb->get_results($query);

            if($banners){
                $the_banner = "";

                foreach($banners as $banner){
                    $the_banner .= '<div class="useful_banner_manager_banner">';

                    if($banner->banner_type!="swf"){
                        if($banner->banner_link!=""){
                            $the_banner .= '<a href="'.$banner->banner_link.'" target="'.$banner->link_target.'">';
                        }
                    	$the_banner .= '<img src="'.get_home_url().'/wp-content/uploads/useful_banner_manager_banners/'.$banner->id."-".$banner->banner_name.'.'.$banner->banner_type.'" width="'.$banner->banner_width.'" height="'.$banner->banner_height.'" alt="'.$banner->banner_title.'" />';

                        if($banner->banner_link!=""){
                            $the_banner .= '</a>';
                        }
                    }else{
                        $the_banner .= '<object width="'.$banner->banner_width.'" height="'.$banner->banner_height.'">
                            <param name="movie" value="'.get_home_url().'/wp-content/uploads/useful_banner_manager_banners/'.$banner->id."-".$banner->banner_name.'.'.$banner->banner_type.'">
                            <embed src="'.get_home_url().'/wp-content/uploads/useful_banner_manager_banners/'.$banner->id."-".$banner->banner_name.'.'.$banner->banner_type.'" width="'.$banner->banner_width.'" height="'.$banner->banner_height.'">
                            </embed>
                        </object>';
                    }
                    $the_banner .= '</div>';
                }

                $content = str_replace($match[0],$the_banner,$content);
            }
        }
    }

    if(preg_match_all("/\[useful_banner_manager_banner_rotation banners=(.[^\]]*) interval=([0-9]+) width=([0-9]+) height=([0-9]+) orderby=(rand)\]/i",$content,$matches,PREG_SET_ORDER)){
        foreach($matches as $match){
            $banners_ids = explode(",",$match[1]);
            $interval = $match[2];
            $width = $match[3];
            $height = $match[4];
            if($match[5]=="rand"){
                $orderby = "RAND()";
            }else{
                $orderby = "banner_order, id DESC";
            }

            $query = "SELECT * FROM (SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
            $banners_ids_query = array();

            foreach($banners_ids as $banner_id){
                $banners_ids_query[] = "id='".trim($banner_id)."'";
            }

            $query .= implode(" OR ",$banners_ids_query);

            $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND banner_type!='swf' AND is_visible='yes' ORDER BY ".$orderby.") as banners ORDER BY banner_order DESC;";

            $banners = $wpdb->get_results($query);

            if($banners){
                $the_banner = '<div id="useful-banner-manager-banners-rotation-n'.$banners_rotation_id.'" class="useful_banner_manager_banners_rotation" style="overflow: hidden; width: '.$width.'px; height: '.$height.'px;">';

                $first_banner = true;
                foreach($banners as $banner){
                    $the_banner .= '<div class="useful_banner_manager_rotating_banner"';
                    if($first_banner){
                        $first_banner = false;
                    }else{
                        $the_banner .= ' style="display: none"';
                    }
                    $the_banner .= '>';

                    if($banner->banner_link!=""){
                        $the_banner .= '<a href="'.$banner->banner_link.'" target="'.$banner->link_target.'" rel="'.$banner->link_rel.'">';
                    }

                    $the_banner .= '<img src="'.get_home_url().'/wp-content/uploads/useful_banner_manager_banners/'.$banner->id.'-'.$banner->banner_name.'.'.$banner->banner_type.'" width="'.$width.'" height="'.$height.'" alt="'.$banner->banner_alt.'" />';

                    if($banner->banner_link!=""){
                        $the_banner .= '</a>';
                    }

                    $the_banner .= '</div>';
                }

                $the_banner .= '</div>';

                $the_banner .= '<script type="text/javascript">
                jQuery(function($){
                    $(document).ready(function(){
                        var useful_banner_manager_banners_rotation_block = "useful-banner-manager-banners-rotation-n'.$banners_rotation_id.'";
                        var interval_between_rotations = '.($interval*1000).';
                        if($("#"+useful_banner_manager_banners_rotation_block+" .useful_banner_manager_rotating_banner").length>1){
                            setTimeout("useful_banner_manager_rotate_banners(\'"+useful_banner_manager_banners_rotation_block+"\',"+interval_between_rotations+")",interval_between_rotations);
                        }
                    });
                });
                </script>';

                $banners_rotation_id++;

                $content = str_replace($match[0],$the_banner,$content);
            }
        }
    }

    return $content;
}

function useful_banner_manager_banners($banners,$count){
    global $wpdb, $useful_banner_manager_table_name;

    if(!is_numeric($count) || intval($count)<1){
        return false;
    }

    $banners_ids = explode(",",$banners);

    foreach($banners_ids as $key => $banner_id){
        if(!is_numeric($banner_id) || intval($banner_id)<1){
            unset($banners_ids[$key]);
        }
    }

    if(empty($banners_ids)){
        return false;
    }

    $query = "SELECT * FROM (SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
    $banners_ids_query = array();

    foreach($banners_ids as $banner_id){
        $banners_ids_query[] = "id='".trim($banner_id)."'";
    }

    $query .= implode(" OR ",$banners_ids_query);

    $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND is_visible='yes' ORDER BY RAND() LIMIT ".$count.") as banners ORDER BY banner_order DESC;";

    $banners = $wpdb->get_results($query);

    if($banners){
        foreach($banners as $banner){
        ?>
            <div class="useful_banner_manager_banner">
            <?php
            if($banner->banner_type!="swf"){
                if($banner->banner_link!=""){
                ?>
                    <a href="<?php echo($banner->banner_link); ?>" target="<?php echo($banner->link_target); ?>" rel="<?php echo($banner->link_rel); ?>">
                <?php
                }
            ?>
            	<img src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>" alt="<?php echo($banner->banner_alt); ?>" />
                <?php
                if($banner->banner_link!=""){
                ?>
                    </a>
                <?php
                }
            }else{
            ?>
                <object width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                    <param name="movie" value="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>">
                    <embed src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($banner->banner_width); ?>" height="<?php echo($banner->banner_height); ?>">
                    </embed>
                </object>
            <?php
            }
            ?>
            </div>
        <?php
        }
    }
}

function useful_banner_manager_banners_rotation($banners,$interval,$width,$height,$orderby=""){
    global $wpdb, $useful_banner_manager_table_name, $banners_rotation_id;

    if($orderby=="rand"){
        $orderby = "RAND()";
    }else{
        $orderby = "banner_order, id DESC";
    }

    if(!is_numeric($interval) || intval($interval)<=0){
        return false;
    }

    if(!is_numeric($width) || intval($width)<=0){
        return false;
    }

    if(!is_numeric($height) || intval($height)<=0){
        return false;
    }

    $banners_ids = explode(",",$banners);

    foreach($banners_ids as $key => $banner_id){
        if(!is_numeric($banner_id) || intval($banner_id)<1){
            unset($banners_ids[$key]);
        }
    }

    if(empty($banners_ids)){
        return false;
    }

    $query = "SELECT * FROM (SELECT * FROM ".$useful_banner_manager_table_name." WHERE (";
    $banners_ids_query = array();

    foreach($banners_ids as $banner_id){
        $banners_ids_query[] = "id='".trim($banner_id)."'";
    }

    $query .= implode(" OR ",$banners_ids_query);

    $query .= ") AND (active_until=-1 OR active_until>='".date("Y-m-d")."') AND banner_type!='swf' AND is_visible='yes' ORDER BY ".$orderby.") as banners ORDER BY banner_order DESC;";

    $banners = $wpdb->get_results($query);

    if($banners){
        ?>
        <div id="useful-banner-manager-banners-rotation-n<?php echo($banners_rotation_id); ?>" class="useful_banner_manager_banners_rotation" style="overflow: hidden; width: <?php echo($width); ?>px; height: <?php echo($height); ?>px;">
        <?php
        $first_banner = true;
        foreach($banners as $banner){
            ?>
            <div class="useful_banner_manager_rotating_banner"<?php if($first_banner){ $first_banner = false; }else{ echo(' style="display: none"'); } ?>>
                <?php
                if($banner->banner_link!=""){
                ?>
                    <a href="<?php echo($banner->banner_link); ?>" target="<?php echo($banner->link_target); ?>" rel="<?php echo($banner->link_rel); ?>">
                <?php
                }
            ?>
            	<img src="<?php bloginfo("url"); ?>/wp-content/uploads/useful_banner_manager_banners/<?php echo($banner->id."-".$banner->banner_name); ?>.<?php echo($banner->banner_type); ?>" width="<?php echo($width); ?>" height="<?php echo($height); ?>" alt="<?php echo($banner->banner_alt); ?>" />
                <?php
                if($banner->banner_link!=""){
                ?>
                    </a>
                <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
        </div>
        <script type="text/javascript">
        jQuery(function($){
            $(document).ready(function(){
                var useful_banner_manager_banners_rotation_block = "useful-banner-manager-banners-rotation-n<?php echo($banners_rotation_id); ?>";
                var interval_between_rotations = <?php echo(($interval*1000)); ?>;
                if($("#"+useful_banner_manager_banners_rotation_block+" .useful_banner_manager_rotating_banner").length>1){
                    setTimeout("useful_banner_manager_rotate_banners('"+useful_banner_manager_banners_rotation_block+"',"+interval_between_rotations+")",interval_between_rotations);
                }
            });
        });
        </script>
        <?php
        $banners_rotation_id++;
    }
}

add_filter('the_excerpt', 'add_useful_banner_manager_banners');
add_filter('the_content', 'add_useful_banner_manager_banners');

wp_enqueue_script('jquery');
wp_enqueue_script('useful_banner_manager_scripts',$useful_banner_manager_plugin_url."scripts.js");
add_action('widgets_init', 'useful_banner_manager_widget_init');
add_action('plugins_loaded','useful_banner_manager_load');
add_action("admin_menu", "useful_banner_manager_menu");
?>