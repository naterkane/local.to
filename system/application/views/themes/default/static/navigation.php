			<?php
			if (!empty($User)) {
				?>
				<li><a href="/home">Home</a></li>
				<li><a href="/<?php echo $User["username"] ?>">Profile</a></li>
				<li><a href="/groups">Groups</a></li>
				<li><a href="/settings">Settings</a></li>
				<li><a href="/public_timeline">Everyone</a></li>
				<li><a href="/users/signout">Sign Out</a></li>
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