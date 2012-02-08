<?php 

/*
Plugin Name: Design Competition
Plugin URI: http://net.tutsplus.com
Description: Designers can post entries to posted competitions
Version: 1.0
Author: Dane Lipscombe
Author URI: http://net.tutsplus.com
*/

if(!class_exists("DesignCompetition")) {
  class DesignCompetition {
    var $adminOptionsName = "design_competition_admin_options";
    var $showHeader = true;
    var $addContent = true;
    var $commentAuthor = true;
    var $content = '';
    function DesignCompetition() {
      //constructor
      $devOptions = $this->getAdminOptions();
      $this->showHeader = $devOptions['show_header'] == 'true';
      $this->addContent = $devOptions['add_content'] == 'true';
      $this->commentAuthor = $devOptions['comment_author'] == 'true';
      $this->content = $devOptions['content'];
    }

    function init() {
      $this->getAdminOptions();
    }

    function getAdminOptions() {
      $designCompetitionAdminOptions = array('show_header' => 'true',
        'add_content' => 'true',
        'comment_author' => 'true',
        'content' => '');

      $devOptions = get_option($this->adminOptionsName);
      if (!empty($devOptions)) {
        foreach ($devOptions as $key => $option) {
          $designCompetitionAdminOptions[$key] = $option;
        }
      }

      update_option($this->adminOptionsName, $designCompetitionAdminOptions);
      return $designCompetitionAdminOptions;
    }

    function printAdminPage() {
					$devOptions = $this->getAdminOptions();
										
					if (isset($_POST['update_devloungePluginSeriesSettings'])) { 
						if (isset($_POST['devloungeHeader'])) {
							$devOptions['show_header'] = $_POST['devloungeHeader'];
						}	
						if (isset($_POST['devloungeAddContent'])) {
							$devOptions['add_content'] = $_POST['devloungeAddContent'];
						}	
						if (isset($_POST['devloungeAuthor'])) {
							$devOptions['comment_author'] = $_POST['devloungeAuthor'];
						}	
						if (isset($_POST['devloungeContent'])) {
							$devOptions['content'] = apply_filters('content_save_pre', $_POST['devloungeContent']);
						}
						update_option($this->adminOptionsName, $devOptions);
						
						?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "DevloungePluginSeries");?></strong></p></div>
					<?php
					} ?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h2>Devlounge Plugin Series</h2>
<h3>Content to Add to the End of a Post</h3>
<textarea name="devloungeContent" style="width: 80%; height: 100px;"><?php _e(apply_filters('format_to_edit',$devOptions['content']), 'DevloungePluginSeries') ?></textarea>
<h3>Allow Comment Code in the Header?</h3>
<p>Selecting "No" will disable the comment code inserted in the header.</p>
<p><label for="devloungeHeader_yes"><input type="radio" id="devloungeHeader_yes" name="devloungeHeader" value="true" <?php if ($devOptions['show_header'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="devloungeHeader_no"><input type="radio" id="devloungeHeader_no" name="devloungeHeader" value="false" <?php if ($devOptions['show_header'] == "false") { _e('checked="checked"', "DevloungePluginSeries"); }?>/> No</label></p>

<h3>Allow Content Added to the End of a Post?</h3>
<p>Selecting "No" will disable the content from being added into the end of a post.</p>
<p><label for="devloungeAddContent_yes"><input type="radio" id="devloungeAddContent_yes" name="devloungeAddContent" value="true" <?php if ($devOptions['add_content'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="devloungeAddContent_no"><input type="radio" id="devloungeAddContent_no" name="devloungeAddContent" value="false" <?php if ($devOptions['add_content'] == "false") { _e('checked="checked"', "DevloungePluginSeries"); }?>/> No</label></p>

<h3>Allow Comment Authors to be Uppercase?</h3>
<p>Selecting "No" will leave the comment authors alone.</p>
<p><label for="devloungeAuthor_yes"><input type="radio" id="devloungeAuthor_yes" name="devloungeAuthor" value="true" <?php if ($devOptions['comment_author'] == "true") { _e('checked="checked"', "DevloungePluginSeries"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="devloungeAuthor_no"><input type="radio" id="devloungeAuthor_no" name="devloungeAuthor" value="false" <?php if ($devOptions['comment_author'] == "false") { _e('checked="checked"', "DevloungePluginSeries"); }?>/> No</label></p>

<div class="submit">
<input type="submit" name="update_devloungePluginSeriesSettings" value="<?php _e('Update Settings', 'DevloungePluginSeries') ?>" /></div>
</form>
 </div>
					<?php
				}//End function printAdminPage()

    function addHeaderCode() {
      if($this->showHeader)
        echo '<!-- Dane was here -->';
    }

    function addContent($content = '') {
      if($this->addContent)
        $content .= $this->content;
      return $content;
    }

    function authorUpperCase($author = '') {
      if($this->commentAuthor)
        $author = strtoupper($author);

      return $author;
    }

  }
}

if(class_exists("DesignCompetition")) {
  $design_competition = new DesignCompetition();
}

//Initialize the admin panel
if (!function_exists("DesignCompetition_ap")) {
	function DesignCompetition_ap() {
		global $design_competition;
		if (!isset($design_competition)) {
			return;
		}
		if (function_exists('add_options_page')) {
	add_options_page('Design Competitions', 'Competitions', 9, basename(__FILE__), array(&$design_competition, 'printAdminPage'));
		}
	}	
}
if(isset($design_competition)) {
  //Actions
  add_action('activate_design-competition/design-competition.php', array(&$design_competition, 'init'));
  add_action('wp_head', array(&$design_competition, 'addHeaderCode'), 1);
  add_action('admin_menu', 'DesignCompetition_ap');
  
  //Filters
  add_filter('the_content', array(&$design_competition, 'addContent'));
  add_filter('get_comment_author', array(&$design_competition, 'authorUpperCase'));
}
