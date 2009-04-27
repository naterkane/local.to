<a href="/groups/add">Create a group</a>
<h2><?php echo $title ?></h2>
<?php 
var_dump($groups);
if (!empty($groups))
{
foreach($groups as $group)
{
	echo "<pre>";
	var_dump($group);
	echo "</pre><hr/>";
}}
else {
	echo "there aren't any groups yet!";
}
?>
