<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Red Hat Blog Theme
 */
?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
	<div class="modal fade" tabindex="-1" role="dialog" id="get_in_touch_footer">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Get in touch!</h4>
						</div>
						<div class="modal-body">
							<?php if (function_exists('Ninja_Forms')) { Ninja_Forms()->display(/* $FOOTER_CONTACT_ID) FIXME*/ 1); } ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
	<footer class="site-footer" role="contentinfo">
		<div class="container">
			<div class="site-info row">
				<div class="col-md-9 text-center"><p style="font-size: 1.8em; color: white; margin: 0px;">Didn't find what you were looking for? <a id="footer_touch" data-toggle="modal" data-target="#get_in_touch_footer"><strong>Get in touch!</strong></a></p></div>
				<div class="col-md-3 text-left">
						<div class="text-left social-footer">
							<a title="Red Hat" href="http://www.redhat.com">
									<img alt="Red Hat logo" style="margin-bottom: 5px;" src="<?php echo esc_url( get_template_directory_uri() );?>/img/l_redhat-reverse.png">
							</a><br/>
							<a href="https://plus.google.com/113358324864673318624/posts" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
							<a href="https://www.facebook.com/RedHatCzech" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
							<a href="https://www.youtube.com/channel/UCIHsqY_4eWeInVQnxZ7WSjg" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i></a>
							<a href="https://twitter.com/redhatcz" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
						</div>
				</div>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->


<!-- SiteCatalyst code version: H.21.
Copyright 1996-2010 Adobe, Inc. All Rights Reserved
More info available at https://www.omniture.com -->

<div id="oTags">
	<script type="text/javascript" src="https://www.redhat.com/assets/js/noncms_s_code.js"></script>
	<script type="text/javascript"><!--
	//get php vars
	<?php
	    //Change the content type to reflect the type of content.
	    //i.e. blog, microsite, landing page, etc...
	    $omniContentType = esc_html('blog');
	if (is_single()) {
	    $omniID = $post->ID;
	    $omniIDLength = 100 - (strlen($omniID) + 3);
	    $omniURL = parse_url(esc_url(site_url()));
	    $omniBlogName = substr($omniURL['host'],0,strpos($omniURL['host'],'.')); //assumes a wordpress subdomain url
	    $omniPageName = 'rh | '.$omniContentType.' | '.$omniBlogName.' | '.$post->post_title;
	    $omniPageName = ((strlen($omniPageName) > $omniIDLength) ? substr($omniPageName,0,$omniIDLength) : $omniPageName) . ' | ' . $omniID;
	    $omniFMS = 'blog posts';
	} else if (is_page()) {
	        $omniURL = parse_url(esc_url(site_url()));
	        $omniBlogName = substr($omniURL['host'],0,strpos($omniURL['host'],'.')); //assumes a wordpress subdomain url
	        $omniPageName = 'rh | '.$omniContentType.' | '.$omniBlogName.' | '.$post->post_title;
	} else if (is_home() || is_front_page()) {
	        $omniURL = parse_url(esc_url(site_url()));
	        $omniBlogName = substr($omniURL['host'],0,strpos($omniURL['host'],'.')); //assumes a wordpress subdomain url
	        $omniPageName = 'rh | ' .$omniContentType.  ' | '  .$omniBlogName.' | home';
	} else if (is_archive()) {
	        $omniURL = parse_url(esc_url(site_url()));
	        $omniBlogName = substr($omniURL['host'],0,strpos($omniURL['host'],'.')); //assumes a wordpress subdomain url
	        $omniPageName = 'rh | '.$omniContentType.' | '.$omniBlogName.' | '.wp_title('',false);
	} ?>

	var omniPageName = '<?php echo esc_js( $omniPageName ); ?>';
	var omniFMS = '<?php echo esc_js( $omniFMS ); ?>';
	var omniContentType = '<?php echo esc_js( $omniContentType ); ?>';

	//then get js vars
	var wlh = window.location.href;
	var sectionName = ["","",""];
	if (omniFMS.length > 0) {
	sectionName[0] = omniFMS;
	} else {
	sectionName = wlh.slice(wlh.indexOf('.com/')).replace('.com/','');
	sectionName = sectionName.slice(0,sectionName.lastIndexOf('/')).split('/');
	if (sectionName[1] == undefined) { sectionName[1] = ""; }
	if (sectionName[2] == undefined) { sectionName[2] = ""; }
	}
	if (wlh.indexOf('?') > -1) {
	    ev18=wlh.slice(0,wlh.indexOf('?'));
	    pp21=wlh.slice(0,wlh.indexOf('?'));
	} else {
	    ev18=wlh;
	    pp21=wlh;
	};
	/* You may give each page an identifying name, server, and channel on
	the next lines. */
	var wlh = window.location.href;
	s.pageName=omniPageName
	s.server=""
	s.channel=omniContentType
	s.pageType=""
	s.prop1=""
	s.prop2="en"
	s.prop3="us"
	s.prop4=wlh
	s.prop21=pp21
	s.prop14=sectionName[0]
	s.prop15=sectionName[1]
	s.prop16=sectionName[2]
	/* E-commerce Variables */
	s.campaign=""
	s.eVar1=""
	s.eVar3=""
	s.eVar18=ev18
	s.eVar19="us"
	s.eVar22="en"
	s.eVar23=wlh
	s.eVar27=sectionName[0]
	s.eVar28=sectionName[1]
	s.eVar29=sectionName[2]
	s.events=""
	s.products=""
	s.state=""
	s.zip=""
	s.purchaseID=""
	--></script>

	<script type="text/javascript" src="https://www.redhat.com/j/rh_omni_footer.js"></script>
	<script language="JavaScript" type="text/javascript"><!--
	if(navigator.appVersion.indexOf('MSIE')>=0)document.write(unescape('%3C')+'\!-'+'-')
	//--></script><noscript><a href="https://www.omniture.com" title="Web Analytics"><img
	src="https://smtrcs.redhat.com/b/ss/redhatcom,redhatglobal/1/H.21--NS/0?[AQB]&cdp=3&[AQE]"
	height="1" width="1" border="0" alt="" /></a></noscript><!--/DO NOT REMOVE/-->
	<!-- End SiteCatalyst code version: H.21. -->
</div>

<?php wp_footer(); ?>

</body>
</html>
