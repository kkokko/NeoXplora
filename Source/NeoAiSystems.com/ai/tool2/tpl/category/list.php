<table>
<tr>
<td><b>Category ID</b></td>
<td><b>Name</b></td>

</tr>
<?php 
$category = new Category();
$category = $category->GetList();
foreach ($category as $cat)
{
?>
<tr>  
<td valign="top"><?= nl2br( $cat->categoryId) ?></td>
<td valign="top"><?= nl2br( $cat->name) ?></td>

  
<td valign="top"><a href="admin.php?page=category&tpl=edit&id=<?= $cat->categoryId ?>">Edit</a></td>
<!--td><a href="admin.php?page=category&tpl=delete&id=<?=$cat->categoryId?>">Delete</a></td--> 
</tr>
<?php  
}
?> 
</table> 
<a href="admin.php?page=category&tpl=new">New Category</a> 
