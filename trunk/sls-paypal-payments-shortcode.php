<?php
/*
	Plugin Name: Paypal Payment Shortcode Multi
	Description: Usage with Shortcode to Create Multiple PayPal Payment Widget
	Author: Son Lam
	Author URI: http://www.thietkewebre.org/
	Version:1.0
*/
add_shortcode('paypal-shortcode','sls_paypal_payment_shortcode');
function sls_paypal_payment_shortcode($args){
	extract(shortcode_atts(array(
		'email' => '',	
		'currency' => 'USD',
		'options' => 'Payment for Service 1:15.50|Payment for Service 2:30.00|Payment for Service 3:47.00',
		'return' => site_url(),
		'reference' => 'Your Email Address',
		'other_amount' => '',
		'country_code' => '',
		'payment_subject' => '',
		'buttom_image' => '',
		'cancel_url' => '',
	),$args));
	$output = "";
	$options = explode( '|' , $options);
	$html_options = '';
	foreach( $options as $option ) {
		$option = explode( ':' , $option );
		$name = esc_attr( $option[0] );
		$price = esc_attr( $option[1] );
		$html_options .= "<option data-product_name='{$name}' value='{$price}'>{$name} - {$price}</option>";
	}
	$paypal_button_image = "";
	if(!empty($buttom_image)){
		$paypal_button_image = $buttom_image;
	}else{
		$paypal_button_image = "https://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif";		
	}
	if(empty($email)){
		$output = '<p style="color: red;">Error! Please enter your PayPal email address for the payment using the "email" parameter in the shortcode</p>';
		return $output;
	}?>
	
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.wp_accept_pp_button_form').submit(function(e){	
		var form_obj = $(this);
		var options_name = form_obj.find('.wp_paypal_button_options :selected').attr('data-product_name');
		form_obj.find('input[name=item_name]').val(options_name);
		
		var options_val = form_obj.find('.wp_paypal_button_options').val();
		var other_amt = form_obj.find('input[name=other_amount]').val();
		if (!isNaN(other_amt) && other_amt.length > 0){
			options_val = other_amt;
		}
		form_obj.find('input[name=amount]').val(options_val);
		return;
	});
});
</script>
<?php
	$output .='<div class="sls_paypal_payment">';
	$output .= '<form name="_xclick" class="wp_accept_pp_button_form" action="https://www.paypal.com/cgi-bin/webscr" method="post">';
	$output .= '<select class="wp_paypal_button_options">';
	$output .= $html_options;
	$output .= '</select>';
	if(!empty($other_amount)){
		$output .= '<div class="wp_pp_button_other_amt_section">';
		$output .= 'Other Amount: <input type="text" name="other_amount" value="" size="4">';
		$output .= '</div>';
	}
	$output .= '<input type="hidden" name="cmd" value="_xclick">';
	$output .=	'<input type="hidden" name="business" value="'.$email.'">';
	$output .=	'<input type="hidden" name="currency_code" value="'.$currency.'">';
	$output .=	'<input type="hidden" name="item_name" value="">';
	$output .=	'<input type="hidden" name="amount" value="">';
	$output .=	'<input type="hidden" name="email" value="" /><br/><br/>';
	$output .='<input type="image" id="buy_now_button" src="'.$paypal_button_image.'" border="0" name="submit" alt="Make payments with PayPal">';
	$output .='</form>';
	$output .='</div>';
	return $output;
}
?>