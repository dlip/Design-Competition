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
    var $templateFolder = "templates";
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

    function render($template, $variables) {
      foreach($variables as $k => $v) {
        $$k = $v;
      }
      ob_start(); // start the output buffer
      require_once(dirname(__FILE__) . '/' . $this->templateFolder . '/' . $template . '.tpl.php'); // get the HTML template
      $output = ob_get_contents(); // store it in a variable
      ob_end_clean(); // empty the buffer and turn it off
      echo $output;
    }

    function init() {
      $this->getAdminOptions();
      $this->registerTypes();
    }

    function registerTypes() {

      $competition_args = array(
        'public' => true,
        'query_var' => 'competition',
        'rewrite' => true,
        'supports' => array(
          'title',
          'thumbnail'
        ),
        'labels' => array(
            'name' => 'Competitions',
            'singular_name' => 'Competition',
            'add_new' => 'Add New Competition',
            'add_new_item' => 'Add New Competition',
            'edit_item' => 'Edit Competition',
            'new_item' => 'New Competition',
            'view_item' => 'View Competition',
            'search_items' => 'Search Competitions',
            'not_found' => 'No Competitions Found',
            'not_found_in_trash' => 'No Competitions Found In Trash'
        ),
      );

      register_post_type('competiton', $competition_args );

      $entry_args = array(
        'public' => true,
        'query_var' => 'Entry',
        'rewrite' => true,
        'supports' => array(
          'title',
          'thumbnail'
        ),
        'labels' => array(
            'name' => 'Entries',
            'singular_name' => 'Entry',
            'add_new' => 'Add New Entry',
            'add_new_item' => 'Add New Entry',
            'edit_item' => 'Edit Entry',
            'new_item' => 'New Entry',
            'view_item' => 'View Entry',
            'search_items' => 'Search Entrys',
            'not_found' => 'No Entries Found',
            'not_found_in_trash' => 'No Entries Found In Trash'
        ),
      );

      register_post_type('entry', $entry_args );
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
      $vars = array();

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
        
        $vars['saved'] = true;
      }

      $vars['devOptions'] = $devOptions;
      $this->render('admin-page', $vars);
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

    function testShortcode($attr, $content) {
      return '<p>hello '. $attr['name'] . '</p>' . '<p>' . $content . '</p>';
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
  add_action('init', array(&$design_competition, 'init'));
  //add_action('activate_design-competition/design-competition.php', array(&$design_competition, 'init'));
  add_action('wp_head', array(&$design_competition, 'addHeaderCode'), 1);
  add_action('admin_menu', 'DesignCompetition_ap');
  
  //Filters
  add_filter('the_content', array(&$design_competition, 'addContent'));
  add_filter('get_comment_author', array(&$design_competition, 'authorUpperCase'));

  add_shortcode('danetest', array(&$design_competition, 'testShortcode'));
}
