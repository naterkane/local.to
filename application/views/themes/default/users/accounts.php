<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<div class="grid_9 alpha">
	   <dl>
	     <dt>Facebook</dt>
       <dd><a href="#"><img src="/img/facebook-connect.gif"/></a></dd>
	     <dt>Foursquare</dt>
	     <dd><?php 
	     if (!empty($foursquare_response) && !empty($foursquare_loginurl)):
	       ?><a href="<?php echo $foursquare_loginurl; ?>" class="button">re-authorize foursquare</a><?php 
	       echo "<pre>";
	       var_dump($foursquare_response);
	       echo "</pre>";
	     else: 
	     ?><a href="<?php echo $foursquare_loginurl; ?>"><img src="/img/signinwith-foursquare.png"/></a>
	     
	     <?php endif; ?>
	     </dd>
	     <dt>Gowalla</dt>
	     <dd>
	     <?php if (isset($user['gowalla_user'])):?>
	     you are connected as <a href="http://gowalla.com/users/<?php echo $user['gowalla_user']; ?>"><?php echo $user['gowalla_user']; ?></a> <span><a href="#gowalla_form" onclick="nc.showGowallaForm()">switch account</a></span>
	     
	       <form id="gowalla_form" action="/settings/accounts" method="POST" style="display:none">
	     <?php else: ?>
	       <form id="gowalla_form" action="/settings/accounts" method="POST">
	     <?php endif; ?>
		       <p>
		         <label for="gowalla_user">username</label>
		         <input id="gowalla_user" name="gowalla_user" type="text"/>
		       </p>
		       <p>
	           <label for="gowalla_pass">password</label>
	           <input id="gowalla_pass" name="gowalla_pass" type="password"/>
	         </p>
	         <p class="submit"><input class="button" type="submit" value="Add Gowalla Account" /></p>
	       </form>
	      
	     </dd>
	     <dt>Google Latitude</dt>
	     <dd>
	     </dd>
	   </dl>
	</div>
	
	<div class="clear"></div>
</div>