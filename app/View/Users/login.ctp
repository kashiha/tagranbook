<h1>ログイン</h1>
<?php print(
  $this->Form->create('UserInformation') .
  $this->Form->input('username', array(
    'type' => 'text',
    'label' => 'ユーザ名')) .
  $this->Form->input('password', array(
    'type' => 'password',
    'label' => 'パスワード')) .
  $this->Form->end('Login')
); ?>