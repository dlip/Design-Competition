
<?php if($saved): ?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "DevloungePluginSeries");?></strong></p></div>
<?php endif ?>


<div class=wrap>
  <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <h2>Design Competition Options</h2>
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
