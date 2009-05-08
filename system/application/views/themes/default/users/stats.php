<div id="stats">
	Following: <?php echo count($this->User->getFollowing($this->userData['id']))//$following_count ?> 
	Followers: <?php echo count($this->User->getFollowers($this->userData['id']));//echo $follower_count ?>
</div>