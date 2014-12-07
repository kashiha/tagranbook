<?php 
  echo "<br /><br /><br /><br />";
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

  echo h($word),"の検索結果";
  echo "<br /><HR><HR>";

  switch($change){
    case "user":
      for($i=0;isset($userName[$i]);$i++){
	echo "ユーザ名：&nbsp",$this->Html->link(h($userName[$i])
	, "/users/home/$userId[$i]/1"),"<br /><br />";
	echo "アカウント名：&nbsp",h($accountName[$i]),"<br /><HR>";
      }
      break;
    case "book":
      for($i=0;isset($bookName[$i]);$i++){
	echo "本のタイトル：&nbsp", $this->Html->link(h($bookName[$i])
	, "/books/detail/$bookId[$i]/1");
	echo "&nbsp&nbsp&nbsp&nbsp著者：&nbsp",h($author[$i]);
	echo "<br /><br />";
	echo $this->Html->link("$favoriteMes[$i]"
	  , "/searches/favorite/$bookId[$i]/$favorite[$i]/$change/$word");
	echo "<br /><HR>";
      }
      break;
    case "tag":
      for($i=0;isset($tagName[$i]);$i++){
	echo "タグ名：&nbsp", $this->Html->link(h($tagName[$i])
	, "/tagrans/ranking/$tagId[$i]/1"),"<br /><br /><HR>";
      }
      break;
  }
  if(!($currentPage == 1)){
    $beforePage = $currentPage - 1;
    echo $this->Html->link('前へ'
    , "/searches/resulet/$change/$word/$beforePage");
    echo "&nbsp&nbsp";
  }
  if(!($currentPage == $maxPage)){
    $nextPage = $currentPage + 1;
    echo $this->Html->link('次へ'
    , "/searches/resulet/$change/$word/$nextPage");
  }