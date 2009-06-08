			<?php
			if (!empty($user)) {
				?>
				<li<?php echo ($this->util->isSection("/home"))?' class="current"':""; ?>><a href="/home">Home</a></li>
				<li<?php echo ($this->util->isSection("/".$user["username"]))?' class="current"':""; ?>><a href="/<?php echo $user["username"] ?>">Profile</a></li>
				<?php /*<li<?php echo ($this->util->isSection("/group"))?' class="current"':""; ?>><a href="/groups">Groups</a></li> */ ?>
				<li<?php echo ($this->util->isSection("/settings"))?' class="current"':""; ?>><a href="/settings">Settings</a></li>
				<li<?php echo ($this->util->isSection("/public_timeline"))?' class="current"':""; ?>><a href="/public_timeline">Everyone</a></li>
				<li><a href="/users/signout">Sign Out</a></li>
				<?php
			} else {
				?>
				<li><a href="/">Home</a></li>
				<li><a href="/about">About</a></li>
				<li><a href="/signin">Sign In</a></li>
				<li><a href="/request_invite">Sign Up</a></li>
				<?php
			}
			?>