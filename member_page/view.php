<?php
session_start();
require('../dbconnect.php');

if(empty($_REQUEST)){
  header('Location: index.php');
  exit();

}

//投稿を取得する
$sql=sprintf('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id
  ORDER BY p.created DESC',
  mysqli_real_escape_string($db,$_REQUEST['id'])
);
  $posts = mysqli_query($db,$sql) or die(mysqli_error($db));
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Mini 掲示板</title>
</head>
<body>
  <div id="wrap">
  <div id="head">
    <h1>Mini掲示板</h1>
  </div>
  <div id="content">
    <p>&laquo;<a href="index.php">一覧に戻る</a></p>
<?php
if ($post = mysqli_fetch_assoc($posts)):
 ?>

 <div class="msg">
 <img src="../member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'UTF-8'); ?>" width="70" height="70"
  alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>"/>
  <p><?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'UTF-8'); ?><span class="name">
    (<?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>)</span>
  [<a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES,'UTF-8'); ?>">Re</a>]</p>

    <p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'UTF-8'); ?></p>
 </div>

<?php
else:
  ?>

<p>その投稿は削除されたかURLが間違っています。</p>
<?php endif;
 ?>
</div>

</form>

<?php while($post = mysqli_fetch_assoc($posts)): ?>



<?php endwhile; ?>
</div>
<div id="foot">
</div>
</body>
</html>
