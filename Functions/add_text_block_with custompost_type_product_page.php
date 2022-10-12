<?php 
    
$taxonomy_objects = get_categories('taxonomy=text_block_category&post_type=text-block');  ?>
<!-- Text Block modal -->
<div class="text_block_modal modal fade" id="text_block_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><?php echo 'Add Text Block'; ?> </h4>
            <button type="button" id="text_block_hide_popup" class="btn btn-default" data-dismiss="modal">x</button>
      </div>
      <div class="modal-body" id="note_body">
            <?php if($taxonomy_objects){ ?>
                <select name="txt-category-name" onchange="getCateval(this);" id="text_block_cate">
                    <option value="" selected="selected">Choose a Text Block Category</option>
                        <?php
                        foreach ($taxonomy_objects as $t_object) {
                            if($t_object->count >0){ 
                                $category_id = $t_object->term_id; ?>
                                <option value="<?php echo $category_id; ?>"><?php echo $t_object->name; ?></option>
                                <?php
                            } 
                        } ?>
                </select>
                <?php  
            }else{
                echo '<div class="txt-wrap"><h2>No Categories Found!</h2></div>';
            } ?>
            
            <select name="txt-block-title-name"  id="cust_text_block_title"></select>
        </div>
      
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><?php // echo $Cancel;?></button> -->
        <button type="button" id="text_block_sbmt" data-text_area_idxx="" class="text_block_sbmt btn-outline"><?php echo 'Insert Block';?></button>
      </div>
    </div>
  </div>
</div>

<script>
    jQuery('#text_block_modal #text_block_hide_popup').click(function() {
             
        jQuery('#text_block_cate option').prop('selected', function() {
            return this.defaultSelected;
        });            
            jQuery('#cust_text_block_title').hide(); 
    });
   jQuery('#text_block_modal .text_block_sbmt').click(function() {
       var post_id= jQuery('#text_block_modal #cust_text_block_title').val();
       var txt_area_id = jQuery(this).data("text_area_idxx");
      
              
        if(post_id !=''){
            var curPos = document.getElementById(txt_area_id).selectionStart;
            
            let x = jQuery('#'+txt_area_id).val();
            let text_to_insert ='[cust_text_block id="'+post_id+'"]';
            jQuery('#'+txt_area_id).val(
            x.slice(0, curPos) +text_to_insert+x.slice(curPos));


            


           /* var curPos = document.getElementById('tinymce').selectionStart;
            let x = jQuery("#tinymce").val();
           
            jQuery("#tinymce").val(
            x.slice(0, curPos) + text_to_insert + x.slice(curPos));*/
            // let text_to_insert ='[cust_text_block id="'+post_id+'"]';
            // tinymce.editors[0].execCommand('mceInsertContent', false, text_to_insert);
            
            jQuery('#text_block_cate option').prop('selected', function() {
                return this.defaultSelected;
            });            
            jQuery('#cust_text_block_title').hide();                
            jQuery('#text_block_modal').modal('hide');                  
        }
        
});
   
    function getCateval(sel)
    {
            var cate_id = sel.value ;

            if(cate_id !=''){
               
                jQuery.ajax({
                            url: "<?php echo admin_url('admin-ajax.php'); ?>",
                            type: 'post',
                            data: { action: 'get_post_by_cate_id', cate_id: cate_id, post_type: 'text-block', taxonomy:'text_block_category' },
                                success: function(data) {
                                    jQuery('.text_block_modal #cust_text_block_title').show();
                                    jQuery('.text_block_modal #cust_text_block_title').html(data);
                                }
                        });
            }

                
        }
</script>