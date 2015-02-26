<?php
/*
Plugin Name: SpiceForms Form Builder
Description: SpiceForms lets you create Responsive WebForms without coding.
Author: a.ankit, nareshsuman, renuka
Version: 1.0
*/
$sf_path ='http://spiceforms.com/app/public/';
//$sf_path ='http://localhost/spiceforms/public/';

global $sf_path;
add_action('admin_menu', 'spice_form_setup_menu');
add_shortcode('spiceforms', 'add_spiceform_shortcode');
	function spice_form_setup_menu(){
		global $sf_path;
		$fc_image = 'dashicons-format-aside' ;
		add_menu_page('Spiceforms','Spiceforms','manage_options', 'spiceforms','sf_init' ,$fc_image,32.2035 );
	}
	 
	function sf_init(){
		global $sf_path;
		if(!isset($_SERVER['HTTPS']))
		{	
			$response =3;
			if(isset($_GET['disconnect']) && $_GET['disconnect']=='true')
			{
				update_option('spiceforms_linkup','');
			}
			if(isset($_POST['email']) && $_POST['email']!='')
			{
				$email=$_POST['email'];
				$result = wp_remote_get( $sf_path."checkemail?email=$email" );
				$response = (int)$result['body'];
				if($response==1)
				update_option('spiceforms_linkup',$email);
			}
			else if(get_option('spiceforms_linkup')!='')
			{
				$response =1;
			}
			unset($_REQUEST);
			?>
			<?php
				wp_enqueue_style( 'style',   plugins_url('/css/style.css',__FILE__) );
				wp_enqueue_script( 'spiceforms_admin',   plugins_url('/js/spiceforms_admin.js',__FILE__) );
			?>
			<div class="wrap">
				<div class="">
					<h2>Set up your Spiceforms Account Now</h2>
					<h4 class="description">Congratulations on successfully installing the Spiceforms WordPress plugin!</h4>
				</div>
					<div id="existingform" style="width:100%">
						<div class="metabox-holder" style="width:50%;float:left">
							<div class="postbox">
								<h3 class="hndle"><span>Linkup with your Spiceforms Account</span></h3>
								<div style="padding:10px;">
									<form name="frm" method="post" action="admin.php?page=spiceforms">
										<table class="form-table">
											<tbody>
												<tr valign="top">
													<th scope="row">Email Address</th>
													<td><input type="email" name="email" value="<?php echo get_option('spiceforms_linkup');?>"></td>
												</tr>

											</tbody>
										</table>
										<p class="submit">
											<?php if($response==1){?><label id="connect-lbl" class='button-primary'><img src="<?php echo plugins_url('/img/check.png',__FILE__);?>"/>Account Connected</label><?php }?><button type="button" onclick="submit_btn_func()" class='button-primary' id="connect" style="display:<?php if($response!=1){ echo'inline-block'; }else{echo'none';}?>" ><img height="16px" width="16px" src="<?php echo plugins_url('/img/loading.gif',__FILE__);?>" id="img" style="display:none"></img>&nbsp Linkup</button><button id="disconnect" type="button" class='button-primary'  onclick="disconnect_link() <?php unset($_POST)?>" style="display:
									<?php if($response==1){ echo 'inline-block';}else{echo'none';}?>">Disconnect  <img src="<?php echo plugins_url('/img/delete.png',__FILE__);?>" height="11px"
width="12px" /></button>
											&nbsp;Don't have an account? <a target="_blank" href="http://spiceforms.com/app/public/signup">Sign up</a>
											
										</p>

									</form>
									
									<label id="msg">
									<?php if($response==0){ echo '<h3 style="color:#ff0000 !important">Incorrect Email Id.' . '<br>'. ' Pls use the same Email id which you used to cerate an account on SpiceForms.</h3>';}?>
									<?php if($response==2){ echo '<h3 style="color:#ff0000 !important">Your account is not activeted. Please activate your account first. </h3>';}?>
									</label>
								</div>
							</div>
						</div>
						<div class="metabox-holder" style="width:50%;display:inline-block">
							<div class="postbox">
								<h3 class="hndle"><span>Create Responsive Web Forms without Coding</span></h3>
								<div style="padding:10px;">
									<p>
									1. Create a <a href = "http://spiceforms.com/app/public/signup" target = "_blank">Free account</a> on SpiceForms
									</p>
									<p>
									2. Build Form using our Drag and Drop Form Builder
									</p>
									<p>
									3. Publish the Form
									</p>
									<p>
									4. Use the shortcode [spiceforms id=XX] to embed the form on your site. 'id' is the Form ID
									</p>
									<p>
									5. Start receiving submissions. 
									</p>
									<p>
									6. You can create Rule based Forms, Add Custom Thank you Message, Create Rule Based notification etc. Refer to <a href= "http://www.spiceforms.com/blog/overview-features-first-release/" target = "_blank">this Blog Post</a> 
									</p>
									<p>
									7. For any queries or suggestion you may use the Wordpress Forums or you can directly contact us via this <a href= "http://spiceforms.com/contactus.html" target = "_blank"> contact form </a> 
									</p>
								</div>
							</div>
						</div>
					</div>
			</div>
	<?php
		}
		else
		{
			echo '<h1>Form cannot be loaded due to security reason</h1>';
		}
	}
	
	function add_spiceform_shortcode( $atts, $content = null ){
		extract(shortcode_atts( array('id' => '1'), $atts ) );
		global $sf_path;
		
		wp_enqueue_script( 'iframeResizer',  $sf_path . '/js/iframeResizer.min.js' );
		wp_register_script( 'embedmanager', plugins_url('js/embedmanager.js',__FILE__));
		$param=array(
			'id'=>$id,
			'sf_path'=>$sf_path
		);
		wp_localize_script( 'embedmanager', 'spiceform', $param );
		wp_enqueue_script( 'embedmanager');
		?>
		<div name="form<?php echo $id; ?>" id="formAnchor<?php echo $id; ?>" ></div>
<?php } ?>
