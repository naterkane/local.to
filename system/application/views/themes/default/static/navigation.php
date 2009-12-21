			<?php
			
			if (!empty($User)) {
				?>
				<li<?php echo ($this->util->isSection("/home"))?' class="current"':""; ?>><a href="/home" id="nav-home">Home</a></li>
				<li<?php echo ($this->util->isSection($_SERVER['HTTP_HOST']."/".$User['username']))?' class="current"':""; ?>><a href="/<?php echo $User["username"] ?>" id="nav-profile">My Profile</a></li>
				<li<?php echo ($this->util->isSection($_SERVER['HTTP_HOST']."/group"))?' class="current"':""; ?>><a href="/groups" id="nav-groups"><?php echo ucfirst($this->config->item('group'))?>s</a></li>
				<?php /*<li<?php echo ($this->util->isSection($_SERVER['HTTP_HOST']."/settings"))?' class="current"':""; ?>><a href="/settings">Settings</a></li> */ ?>
				<li<?php echo ($this->util->isSection($_SERVER['HTTP_HOST']."/public_timeline"))?' class="current"':""; ?>><a href="/public_timeline" id="nav-everyone">Everyone</a></li>
				<?php /*<li><a href="/users/signout">Sign Out</a></li>*/ ?>
				<?php
			} else {
				/*?>
				<li><a href="/">Home</a></li>
				<li<?php echo ($this->util->isSection("/about"))?' class="current"':""; ?>><a href="/about">About</a></li>
				<li<?php echo ($this->util->isSection("/signin"))?' class="current"':""; ?>>
					<?php if (!empty($sendMeHere)): ?>
						<a href="/signin<?php echo $html->sendMeHere(); ?>">Sign In</a>
					<?php else: ?>
						<a href="/signin">Sign In</a>
					<?php endif ?>
				</li>
				<li<?php echo ($this->util->isSection("/request_invite"))?' class="current"':""; ?>><a href="/request_invite">Sign Up</a></li>
				<?php*/
			}