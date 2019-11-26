<!-- Begin MailChimp Signup Form -->
<?php
global $newsletter_index;
global $post;
if( !isset( $newsletter_index ) ) {
	$newsletter_index = 0;
}
$newsletter_index++;

?>

<form action="https://therevealer.us4.list-manage.com/subscribe/post?u=0d33df8173d96b4ebcfdb732d&amp;id=0f729dc19c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
	<label for="mce-EMAIL-<?php echo $newsletter_index; ?>">
		<h2 class="title">
			<?php the_field( 'newsletter_title', 'option' )?>
		</h2>
	</label>
  <div id="mc_embed_signup_scroll" class="inputs">
		<div class="field mc-field-group">
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL-<?php echo $newsletter_index; ?>" placeholder="Enter your email">
		</div>

		<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_0d33df8173d96b4ebcfdb732d_0f729dc19c" tabindex="-1" value=""></div>
    <div class="submit">
	    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
	  </div>
    <div id="mce-responses" class="clear">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>
  </div>
</form>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[5]='MMERGE5';ftypes[5]='text';fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='text';fnames[4]='MMERGE4';ftypes[4]='text';fnames[6]='MMERGE6';ftypes[6]='text';fnames[7]='MMERGE7';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='text';fnames[9]='MMERGE9';ftypes[9]='text';fnames[10]='MMERGE10';ftypes[10]='text';fnames[11]='MMERGE11';ftypes[11]='text';fnames[12]='MMERGE12';ftypes[12]='text';fnames[13]='MMERGE13';ftypes[13]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->