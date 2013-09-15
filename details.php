<?php require 'ldapconnect.php'; ?>
<?php

    $pageTitle = " - Your Account";

  if (isset($_POST['cn'])) {
    $entry=array();
    $entry['cn']=$_POST['cn'];
    $entry['studentnumber']=$_POST['studentnumber'];
    $entry['mail']=$_POST['email'];
    switch ($_POST['loginShell']) {
      case "/bin/bash":
        $entry['loginshell']="/bin/bash";
        break;
      case "/bin/tcsh":
        $entry['loginshell']="/bin/tcsh";
        break;
      case "/bin/zsh":
        $entry['loginshell']="/bin/zsh";
        break;
    }
    
    
    ldap_modify($con,$userdn,$entry);
    $success = "Details updated successfully.";
    
    $user_search = ldap_search($con, $dn, "(uid=$user)");
    $user_get = ldap_get_entries($con, $user_search); 

    $avatar = md5( strtolower( trim($user_get[0]["mail"][0] ) ) );
  }
?>
<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>
        <div class="span10">
          <div class="row">
            <div class="span5">
              <form class="form-horizontal" action="details.php" method="post" id="form">
                <fieldset>
                  <legend>Account Details</legend>
                    <?php if (isset($success)) : ?>
                    <div class="control-group">
                      <div class="alert alert-success">
                        <?php echo "$success"; ?>
                      </div>
                    </div>
                    <?php endif; ?>
                  <div class="control-group">
                    <label class="control-label" for="uid">Account Name</label>
                    <div class="controls">
                      <span class="input-xlarge uneditable-input"><?php echo $user_get[0]["uid"][0]; ?></span>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="cn">Full Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge required" name="cn" id="cn" value="<?php echo $user_get[0]["cn"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="studentnumber">Student Number</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge digits" name="studentnumber" id="studentnumber" value="<?php echo $user_get[0]["studentnumber"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                    <input type="text" class="input-xlarge required email" name="email" id="email" value="<?php echo $user_get[0]["mail"][0]; ?>">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="loginShell">Login Shell</label>
                      <div class="controls">
                        <select id="loginShell" name="loginShell" class="required">
                          <?php $shell=$user_get[0]["loginshell"][0]?>
                          <option <?php echo($shell == "/bin/bash"?' selected="selected"':null) ?>>/bin/bash</option>
                          <option <?php echo($shell == "/bin/tcsh"?' selected="selected"':null) ?>>/bin/tcsh</option>
                          <option <?php echo($shell == "/bin/zsh"?' selected="selected"':null) ?>>/bin/zsh</option>
                        </select>
                      </div>
                    </div>              
                  <div class="control-group">
                    <label class="control-label" for="status">Account Status</label>
                    <div class="controls">
                        <span class="input-xlarge uneditable-input"><?php echo getStatus($user_get[0]['shadowexpire'][0], $user_get[0]['haspaid'][0]); ?></span>
                    </div>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Details</button>
                  </div>
                </fieldset>
              </form>
              <?php if($user_get[0]['haspaid'][0] == "FALSE") : ?>
		<br /><br />
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		  <input type="hidden" name="cmd" value="_xclick-subscriptions">
		  <input type="hidden" name="business" value="gsag@geeksoc.org">
		  <input type="hidden" name="lc" value="GB">
		  <input type="hidden" name="item_name" value="1 Year GeekSoc Membership - <?=$user_get[0]['uid'][0]?>">
		  <input type="hidden" name="item_number" value="1">
		  <input type="hidden" name="no_note" value="1">
		  <input type="hidden" name="src" value="1">
		  <input type="hidden" name="a3" value="3.00">
		  <input type="hidden" name="p3" value="1">
		  <input type="hidden" name="t3" value="Y">
		  <input type="hidden" name="currency_code" value="GBP">
		  <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
		  <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
		  <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		  <br /><br />
		  <p><strong>Any subscription payments will be verified manually, it may take some time to reflect on your account status. If you made your payment over 1 day ago - please contact us on <a href="mailto:accounts.geeksoc.org">accounts.geeksoc.org</a>.</strong></p>
		</form>
	      <?php endif; ?>
            </div><!--/span-->

						<div class="span4 offset1">
							<h3>Shell Access</h3>
							<p>The shell service can be accessed over SSH at <code>shell.geeksoc.org</code>.</p>
							<p>On Windows the program <a href="http://www.chiark.greenend.org.uk/~sgtatham/putty/">PuTTY</a> can be used to login to the SSH server, while Mac and Linux users will already have SSH installed and may connect using the 'ssh' command from a terminal.</p>
							
							<h3>IRC (Chat)</h3>
							<p>Our public IRC server can be reached at <code>irc.geeksoc.org</code>. The official channel is <code>#geeksoc</code>. The recommended way of accessing IRC is setting up a persistent connection on Shell using screen and irssi, see <a href="http://quadpoint.org/articles/irssi">here</a> for details on how to set this up.</p>
							
							<h3>Web Hosting</h3>
							<p>Anything in your <code>public_html</code> directory will be available at <a href="http://geeksoc.org/~<?php echo $user; ?>">http://geeksoc.org/~<?php echo $user; ?></a>.</p>
							
							<h3>Email</h3>
							<p>Our email service is run by Google and may be accessed at <a href="http://mail.geeksoc.org/">mail.geeksoc.org</a> if you have an account.</p>
							<p>If you would like an @geeksoc.org account then please email <a href="mailto:support@geeksoc.org">support@geeksoc.org</a>.</p>
							
						</div>
          </div><!--/row-->
        </div><!--/span-->

<?php require 'footer.php'; ?>
