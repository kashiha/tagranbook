<h1>検索</h1>
<?php 
  echo $this->Form->create(false,
  array('type' => 'post', 'action' => 'index'));
  echo $this->Form->select('object', array(
    'user' => 'ユーザ名',
    'book' => '本のタイトル',
    'tag' => 'タグ名'
    ), array('empty'=>false));
  echo $this->Form->text('word');
  echo $this->Form->submit('検索');
  echo $this->Form->end();

?>