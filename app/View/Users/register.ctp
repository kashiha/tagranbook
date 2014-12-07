<?php echo $this->Html->css('users'); ?>

<h1>TagRanBooksに参加しませんか？　　　　　　　　　　　　　</h1>

<?php print($this->Html->link('ログイン', '/users/login')); ?>
<div id="input_form">
  <?php
  print(
  $this->Form->create('UserInformation') .
  $this->Form->input('username', array(
     'type' => 'text', 
     'label' => 'ユーザ名' ,)) .
  $this->Form->input('accountname', array(
     'type' => 'text', 
     'label' => 'アカウント名' ,)) .
  $this->Form->input('password', array(
     'type' => 'password', 
     'label' => 'パスワード',)) .
  $this->Form->input('passwordagain', array(
     'type' => 'password', 
     'label' => 'パスワード（確認用）' ,)) .
  $this->Form->input('email', array(
     'type' => 'text', 
     'label' => 'メールアドレス',)) .
  $this->Form->end('新規登録')
  ); ?>
</div>