<?php
session_start();
require('../dbconnect.php');


if (!empty($_POST)){
  //エラー項目の確認
  if($_POST['name']==''){
    $error['name'] = 'blank';
  }
  if($_POST['email']==''){
    $error['email'] = 'blank';
  }
  if(strlen($_POST['password'])<4 || strlen($_POST['password'])>10 ){
    $error['password'] = 'length';
  }
  if($_POST['password']==''){
    $error['password'] = 'blank';
  }
  $fileName = $_FILES['image']['name'];
  if(!empty($fileName)){
    $ext = substr($fileName,-3);
    if ($ext != 'jpg' && $ext != 'gif'){
      $error['image']='type';
    }
  }


  if(empty($error)){
    $sql = sprintf('SELECT COUNT(*) AS cnt FROM members WHERE email="%s"',
      mysqli_real_escape_string($db, $_POST['email']));
      $record = mysqli_query($db, $sql) or die(mysqli_error($db));
      $table = mysqli_fetch_assoc($record);
      if ($table['cnt']>0){
        $error['email']='duplicate';
      }
  }


  if(empty($error)){
    //画像をアップロードする
    $image=date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/'.$image);

    $_SESSION['join']= $_POST;
    $_SESSION['join']['image']= $image;
    header('Location: check.php');
    exit();
  }
}

//修正するが選択された場合
if ($_REQUEST['action']=='rewrite'){
  $_POST = $_SESSION['join'];
  $error['rewrite'] = true;
}
?>
 <title>会員登録画面</title>


 <p>次のフォームに必要事項を記入してください</p>
<form enctype="multipart/form-data" method = "post" action ="">
  <dl>
    <dt>ニックネーム<span class="required">必須</span></dt>
    <dd>
      <input name="name" type="text" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES,'UTF-8'); ?>"/>
      <?php if ($error['name']=='blank'): ?>
        <p class="error">*ニックネームを入力してください</p>
      <?php endif; ?>
    </dd>
      <dt>メールアドレス<span class="required">必須</span></dt>
      <dd>
        <input name="email" type="text" size="35" maxlength="255"
        value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES,'UTF-8'); ?>"/>
        <?php if ($error['email']=='blank'): ?>
          <p class="error">*メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if ($error['email']=='duplicate'): ?>
          <p class="error">*指定されたメールアドレスはすでに使用されています</p>
        <?php endif; ?>
      </dd>
      <dt>パスワード<span class="required">必須</span></dt>
      <dd>
        <input name="password" type="password" size="10" maxlength="20"
        value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES,'UTF-8'); ?>"/>
        <?php if ($error['password']=='blank'): ?>
          <p class="error">*パスワードを入力してください</p>
        <?php endif; ?>
        <?php if ($error['password']=='length'): ?>
          <p class="error">*パスワードは半角5~10文字以内で入力してください</p>
        <?php endif; ?>
      </dd>
    <dt>写真・画像</dt>
    <dd>
      <input name="image" type="file" size="35"/>
      <?php if ($error['image']=='type'): ?>
        <p class="error">*「.jpg」「.gif」の画像を選択してください</p>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <p class="error">*恐れ入りますが、画像を改めて指定してください</p>
      <?php endif; ?>
    </dd>
  </dl>
  <div><input type="submit" value="入力内容を確認する" /></div>
</form>
