<?php
/*
Plugin Name: Lead Qualify Forms
Plugin URI:  http://www.certifiedace.com/EasyQuoteDashboard
Description: Generate qualified leads. The Easy Quote widget guides your website visitors through your service directory with a smart wizard. The wizard captures contact details and presents the customer with qualifying questions based on service requested. No coding required. 
Version: 1.0
Author: Certified Ace
Author URI: http://www.certifiedace.com
*/


add_action( 'admin_init', 'jueqp_admin_init' );
function jueqp_admin_init() {
        /* Register our script. */
        wp_register_script( 'jueqp_js_script', plugins_url( 'js/colpick.js', __FILE__ ) );
		wp_register_style( 'jueqp_css_style', plugins_url( 'css/colpick.css', __FILE__ ) );
    }
add_action('wp_footer', 'jueqp_header_front');
function jueqp_header_front(){
	$jueqp_pin = NULL;
	$jueqp_refid = NULL;
	$jueqp_position = NULL;
	$jueqp_bgcolor = NULL;
	$jueqp_fontcolor = NULL;
	$jueqp_btntext = NULL;
	$jueqp_fontsize = NULL;
	
	$jueqp_valid = 0;
	
	if(get_option("jueqp_pin") && get_option("jueqp_pin") != ""){
		$jueqp_pin = get_option("jueqp_pin");	
		$jueqp_valid++;
	}
	if(get_option("jueqp_refid") && get_option("jueqp_refid") != ""){
		$jueqp_refid = get_option("jueqp_refid");	
		$jueqp_valid++;
	}
	if(get_option("jueqp_position") && get_option("jueqp_position") != ""){
		$jueqp_position = get_option("jueqp_position");	
	}
	if(get_option("jueqp_bgcolor") && get_option("jueqp_bgcolor") != ""){
		$jueqp_bgcolor = get_option("jueqp_bgcolor");	
	}
	if(get_option("jueqp_fontcolor") && get_option("jueqp_fontcolor") != ""){
		$jueqp_fontcolor = get_option("jueqp_fontcolor");	
	}
	if(get_option("jueqp_btntext") && get_option("jueqp_btntext") != ""){
		$jueqp_btntext = get_option("jueqp_btntext");	
	}
	if(get_option("jueqp_fontsize") && get_option("jueqp_fontsize") != ""){
		$jueqp_fontsize = get_option("jueqp_fontsize");	
	}
	if($jueqp_valid == 2){
		echo "<script src='http://certifiedace.com/resource/easyquote/easyquote.load.js?PN=$jueqp_pin&refid=$jueqp_refid&pos=$jueqp_position&bgcol=$jueqp_bgcolor&fontcol=$jueqp_fontcolor&btntext=$jueqp_btntext&fontsize=$jueqp_fontsize' async='async'/>";	
	}
	
}

add_action( 'admin_footer', 'jueqp_footer_javascript' ); // Write our JS below here
function jueqp_footer_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
			$('#jueqp_bgcolor').colpick({
				layout:'hex',
				submit:0,
				colorScheme:'dark',
				onChange:function(hsb,hex,rgb,el,bySetColor) {
					$(el).css('border-color','#'+hex);
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if(!bySetColor) $(el).val(hex);
				}
			}).keyup(function(){
				$(this).colpickSetColor(this.value);
			});
			
			$('#jueqp_fontcolor').colpick({
				layout:'hex',
				submit:0,
				colorScheme:'dark',
				onChange:function(hsb,hex,rgb,el,bySetColor) {
					$(el).css('border-color','#'+hex);
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if(!bySetColor) $(el).val(hex);
				}
			}).keyup(function(){
				$(this).colpickSetColor(this.value);
			});
			$("#jueqp_loading_one").hide();
			$("#jueqp_done").hide();
			$( "#jueqp_submit").click(function(event) {
				
			event.preventDefault();
			
			if($("#jueqp_pin").val() == ""){
				$("#jueqp_pin").css("border","2px solid red");	
			}
			else{
				$("#jueqp_pin").css("border","0px solid white");	
			}
			if($("#jueqp_refid").val() == ""){
				$("#jueqp_refid").css("border","2px solid red");	
			}
			else{
				$("#jueqp_refid").css("border","0px solid white");	
			}
			if($("#jueqp_pin").val() != "" && $("#jueqp_refid").val() != ""){
			 $("#jueqp_loading_one").show();
			 $("#jueqp_done").hide();
				 
				 $.ajax({
		
				  type: 'post',
				  url: ajaxurl,
				  data: {
					 /* 'post_id' : $("#jueqp_process_one_post_id").val(),
					  'jueqp_target_folder' : $("#jueqp_target_folder").val(),*/
					  	'jueqp_pin' : $("#jueqp_pin").val(),
						'jueqp_refid' : $("#jueqp_refid").val(),
						'jueqp_position' : $("#jueqp_position").val(),
						'jueqp_bgcolor' : $("#jueqp_bgcolor").val(),
						'jueqp_fontcolor' : $("#jueqp_fontcolor").val(),
						'jueqp_btntext' : $("#jueqp_btntext").val(),
						'jueqp_fontsize' : $("#jueqp_fontsize").val(),
						'action': 'jueqp_easyquote',
					  },
			
				  success: function(data) {
					$("#jueqp_loading_one").hide();
					$("#jueqp_done").show();
					},
				  error: function() {
					
				  }
			
				});
				 }
			});
			
		
		});
	</script> 
    <?php
    }
add_action( 'wp_ajax_jueqp_easyquote', 'jueqp_easyquote_callback' );
function jueqp_easyquote_callback(){
	update_option("jueqp_pin", $_POST["jueqp_pin"]);
	update_option("jueqp_refid", $_POST["jueqp_refid"]);
	update_option("jueqp_position", $_POST["jueqp_position"]);
	update_option("jueqp_bgcolor", $_POST["jueqp_bgcolor"]);
	update_option("jueqp_fontcolor", $_POST["jueqp_fontcolor"]);
	update_option("jueqp_btntext", $_POST["jueqp_btntext"]);
	update_option("jueqp_fontsize", $_POST["jueqp_fontsize"]);
}

add_action('admin_menu', 'jueqp_create_menu');

function jueqp_create_menu() {

	//create new top-level menu
	$jueqp_hook_suffix = add_options_page('Lead Qualify Forms', 'Lead Qualify Forms', 'administrator', __FILE__, 'jueqp_settings_page');
	 add_action('admin_print_scripts-' . $jueqp_hook_suffix, 'jueqp_admin_scripts');
}
 function jueqp_admin_scripts() {
        /* Link our already registered script to a page */
        wp_enqueue_style( 'jueqp_css_style' );
		wp_enqueue_script( 'jueqp_js_script' );
		
    }
function jueqp_settings_page() {
?>
<div class="wrap">
<h2>Lead Qualify Forms Settings</h2>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Pro Number:</th>
        <td>
        <input id="jueqp_pin" type="text" value="<?php echo esc_attr( get_option('jueqp_pin') ); ?>" style="width:25em;" />&nbsp;&nbsp;<font color="blue">(Required)</font>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Referral ID:</th>
        <td>
        <input id="jueqp_refid" type="text" value="<?php echo esc_attr( get_option('jueqp_refid') ); ?>" style="width:25em;" />&nbsp;&nbsp;<font color="blue">(Required)</font>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Position of Widget:</th>
        <td>
        <select id="jueqp_position">
        	<?php 
			if(get_option("jueqp_refid") && get_option("jueqp_refid") == "left"){
				echo "<option value='left' selected='selected'>Left</option>";	
			}
			else{
				echo "<option value='left'>Left</option>";	
			}
			if(get_option("jueqp_refid") && get_option("jueqp_refid") == "right"){
				echo "<option value='right' selected='selected'>Right</option>";	
			}
			else{
				echo "<option value='right'>Right</option>";	
			}
			
			?>
        </select>&nbsp;&nbsp;<font color="green">(Optional)</font>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Button Text:</th>
        <td>
        <input id="jueqp_btntext" type="text" value="<?php echo esc_attr( get_option('jueqp_btntext') ); ?>" style="width:25em;" />&nbsp;&nbsp;<font color="green">(Optional)
        </td>
        </tr>
        
        
        
        <tr valign="top">
        <th scope="row">Button Background Color:</th>
        <td>
        <input id="jueqp_bgcolor" type="text" value="<?php echo esc_attr( get_option('jueqp_bgcolor') ); ?>" style="width:25em;" />&nbsp;&nbsp;<font color="green">(Optional)
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Button Font Color:</th>
        <td>
        <input id="jueqp_fontcolor" type="text" value="<?php echo esc_attr( get_option('jueqp_fontcolor') ); ?>" style="width:25em;" />&nbsp;&nbsp;<font color="green">(Optional)
        </td>
        </tr>
        
        
        <tr valign="top">
        <th scope="row">Button Font Size:</th>
        <td>
        <select id="jueqp_fontsize">
        	<?php
				for($jueqp_counter = 15; $jueqp_counter < 31; $jueqp_counter++){
					if(get_option("jueqp_fontsize") && get_option("jueqp_fontsize") == $jueqp_counter){
						echo "<option value='$jueqp_counter' selected='selected'>$jueqp_counter</option>";	
					}
					else{
					echo "<option value='$jueqp_counter'>$jueqp_counter</option>";	
					}
				}
			?>
        </select>&nbsp;&nbsp;<font color="green">(Optional)
        </td>
        </tr>
       
       	<tr valign="top">
        <th scope="row">
        </th>
        <td>
        <span id="jueqp_loading_one"><img src="<?php echo plugins_url( '/images/loading.gif', __FILE__); ?>" /></span>
        <input type="button" id="jueqp_submit" value="submit" class="button button-primary"/>
        <span id="jueqp_done"><img src="<?php echo plugins_url( '/images/tick.png', __FILE__); ?>" /></span>
       	</td>
        </tr>
    </table>
    
        
        </div>
<?php
}
