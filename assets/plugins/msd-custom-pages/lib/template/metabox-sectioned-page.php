<?php global $wpalchemy_media_access; ?>

<?php 

$postid = is_admin()?$_GET['post']:$post->ID;
$template_file = get_post_meta($postid,'_wp_page_template',TRUE);
  // check for a template type
if (is_admin() && $template_file == 'page-sectioned.php') { ?>
<style>
    .msdlab_meta_control .table {display: block; width: 100%;}
    .msdlab_meta_control .row {display: block;cursor: move;border-bottom: 1px solid #333;}
    .msdlab_meta_control .row:before,
.msdlab_meta_control .row:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.msdlab_meta_control .row:after {
    clear: both;
}

/**
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */
.msdlab_meta_control .row {
    *zoom: 1;
}
.msdlab_meta_control .cell {display: block; clear: both;margin-left: 1rem;}
    .even {background: #eee;}
    .odd {background: #fff;}
    .file input[type="text"] {width: 75%}
    .msdlab_meta_control label{ display:block; font-weight:bold; margin-right: 1%;float: left; width: 14%; text-align: right;}
 .input_container{width: 85%;float: left;}
.msdlab_meta_control textarea, .msdlab_meta_control input[type='text'], .msdlab_meta_control select,.msdlab_meta_control .wp-editor-wrap
{ display:inline;margin-bottom:3px; width: 90%;
     }
     .msdlab_meta_control .file input[type='text']{width: 70%;}
     
</style>
<div class="msdlab_meta_control">
 <p id="warning" style="display: none;background:lightYellow;border:1px solid #E6DB55;padding:5px;">Order has changed. Please click Save or Update to preserve order.</p>
    <div class="table">
    <?php $i = 1; ?>
    <?php while($mb->have_fields_and_multi('sections')): ?>
    <?php $mb->the_group_open(); ?>
    <div class="row <?php print $i%2==1?'even':'odd'; ?>">
        <div class="cell"><h3>Section <?php print $i ?> </h3></div>
        <div class="cell">
        <?php $mb->the_field('title'); ?>
        <label>Section <?php print $i ?> Content Area Title</label>            
        <div class="input_container">
            <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/></div>
        </div>
        <div class="cell">
            <label>Section <?php print $i ?> Subtitle</label>
            <div class="input_container">
                <?php 
                $mb->the_field('subtitle');
                $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
                $mb_editor_id = sanitize_key($mb->get_the_name());
                $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
                wp_editor( $mb_content, $mb_editor_id, $mb_settings );
                ?>
           </div>
        </div>
        <div class="cell">
            <label>Section <?php print $i ?> Content Area Content</label>
            <div class="input_container">
                <?php 
                $mb->the_field('content');
                $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
                $mb_editor_id = sanitize_key($mb->get_the_name());
                $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
                wp_editor( $mb_content, $mb_editor_id, $mb_settings );
                ?>
           </div>
        </div>
        <div class="cell file">
            <label>Section <?php print $i ?> Feature Image</label>
            <div class="input_container">
        <?php $mb->the_field('image'); ?>
        <?php if($mb->get_the_value() != ''){
            print '<img src="'.$mb->get_the_value().'">';
        } ?>
        <?php $group_name = 'feature-img-'. $mb->get_the_index(); ?>
        <?php $wpalchemy_media_access->setGroupName($group_name)->setInsertButtonLabel('Insert This')->setTab('gallery'); ?>
        <?php echo $wpalchemy_media_access->getField(array('name' => $mb->get_the_name(), 'value' => $mb->get_the_value())); ?>
        <?php echo $wpalchemy_media_access->getButton(array('label' => 'Add Image')); ?>
            </div>
        </div>
        <div class="cell">
            <a href="#" class="dodelete button alignright">Remove Section <?php print $i ?></a>
        </div>
    </div>
    <?php $i++; ?>
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
    </div>
    <p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-sections button">Add Section</a>
</div>
<script>
jQuery(function($){
    $("#wpa_loop-sections").sortable({
        change: function(){
            $("#warning").show();
        }
    });
    $("#postdivrich").after($("#_page_sectioned_metabox"));
});</script>
<?php } else {
    print "Select \"Sectioned Page\" template and save to activate.";
} ?>
