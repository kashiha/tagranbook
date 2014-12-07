<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>TagRanBook ～本をタグでランキングするサイト</title>
    <?php echo $this->Html->css('main'); ?>
    <?php echo $this->Html->script('jquery-1.4.2.min.js'
      , array( 'inline' => 'false')); ?>
    <?php echo $this->Js->writeBuffer( array( 'inline' => 'true')); ?>
  </head>
  <body>
  <div id="container">
    <div id="header">
      <div id="logo_menu">
	<div id="logo">
          <?php
            echo $this->Html->link('TagRanBook', '/users/index');
	  ?>
	</div>
        <?php
	  echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	  echo $this->Html->link('TagRan', '/tagrans/');
	  echo "&nbsp&nbsp";
	  echo $this->Html->link('Search', '/searches/');
	?>
      </div>
      <div id="header_menu">
        <?php
          if(isset($user)):
            echo $this->Html->link('MyPage', '/users/mypage/1');
	    echo "&nbsp&nbsp";
            echo $this->Html->link('ログアウト', '/users/logout');
          else:
            echo $this->Html->link('新規登録', '/users/register');
	    echo "&nbsp&nbsp";
            echo $this->Html->link('ログイン', '/users/login');
          endif;
        ?>
      </div>

      <div id="content">
        <?php echo $this->fetch('content'); ?>
      </div>
    </div>
  </div>
  </body>
</html>