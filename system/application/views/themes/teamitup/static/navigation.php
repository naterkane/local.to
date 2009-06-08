			<?php
			if (!empty($user)) {
				?>
				<li<?php echo ($this->util->isSection("/home"))?' class="active"':""; ?>><a href="/home">Home</a></li>
				<li<?php echo ($this->util->isSection("/".$user["username"]))?' class="active"':""; ?>><a href="/<?php echo $user["username"] ?>">Profile</a></li>
				<li<?php echo ($this->util->isSection("/group"))?' class="active"':""; ?>><a href="/groups">Groups</a></li>
				<li<?php echo ($this->util->isSection("/public_timeline"))?' class="active"':""; ?>><a href="/public_timeline">Everyone</a></li>
				
				<?php
			} else {
				?>
				<li><a href="/">Home</a></li>
				<li><a href="/about">About</a></li>
				<li><a href="/users/signin">Sign In</a></li>
				<li><a href="/users/signup">Sign Up</a></li>
				<?php
			}
			?>