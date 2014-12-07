<?php
  echo "<br /><br /><br />書名："
	, h($bookInfo["BookInformation"]["bookname"]);
  echo "&nbsp&nbsp&nbsp&nbsp";
  echo "著者：",h($bookInfo["BookInformation"]["author"]);
  echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";

  echo $this->Html->link("$message", "/books/favoritebook/$bookId/$config");
  echo "<br /><br />";

  echo "ジャンル： ",$category,"<br /><br />";

  echo "ランダム表示： ";
  echo $this->Html->link("$category", "/books/random/$category");
  echo "&nbsp&nbsp&nbsp&nbsp";
  echo $this->Html->link("全ての本から", "/books/random/0"),"<br /><br />";


  echo "タグスイッチ設定：　","現在","$moodName","に設定中<br /><br />";
  echo $this->Html->image('tagreg.png',array(
    'name' => 'tagreg',
    'alt' => 'タグに共感!',
    'url' => array('controller' => 'books', 'action' => 'change', 'reg')));
  echo "&nbsp&nbsp&nbsp&nbsp";

  echo $this->Html->image('tagran.png',array(
    'name' => 'tagran',
    'alt' => 'タグでランダム表示',
    'url' => array('controller' => 'books', 'action' => 'change', 'ran')));
  echo "&nbsp&nbsp&nbsp&nbsp";

  echo $this->Html->image('taglink.png',array(
    'name' => 'taglink',
    'alt' => 'TagRanにリンク',
    'url' => array('controller' => 'books', 'action' => 'change', 'link')));

  echo "<br /><br />タグ：", "&nbsp&nbsp";

  for($i =0; $i < $booktagCount; $i++){
    $tagName = $bookTag[$i]["BookTagCount"]["tagname"];
    $tagId = $bookTag[$i]["BookTagCount"]["tagid"];
    echo $this->Html->link("$tagName", "/books/button/$mood/$tagId");
    echo "&nbsp&nbsp";
    echo $bookTag[$i]["BookTagCount"]["count"];
    echo "&nbsp&nbsp";
  }

  echo "<br /><br />";
  echo $this->Form->create("BookTag");
  echo $this->Form->text('newTag');
  echo $this->Form->end('タグを新規登録');

  echo "この本への一言。：<br />";

  echo $this->Form->create('Review');
  echo $this->Form->input('comment', array(
	 'type' => 'textarea',
	 'cols' => '50',
	 'rows' => '4',
	 'label' => 'コメント欄'
       ));
  echo $this->Form->input('valuation', array(
	 'type' => 'select',
	 'options' => array(
           '未評価' => '未評価',
	   '星1つ' => '星1つ',
	   '星2つ' => '星2つ',
	   '星3つ' => '星3つ',
	   '星4つ' => '星4つ',
	   '星5つ' => '星5つ'
	 ),
	 'selected' => '未設定',
	 'label' => '評価'
       ));
  echo $this->Form->end('投稿する');

  echo "<HR>";
  for($i = 0; isset($review[$i]); $i++){
    echo $review[$i]["Review"]["username"],"&nbsp&nbsp";
    echo "評価：&nbsp&nbsp",$review[$i]["Review"]["valuation"];
    echo "<br /><br />コメント：", $review[$i]["Review"]["comment"];
    echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
    echo "<br /><br />投稿時間：",$review[$i]["Review"]["registrationtime"];
    echo "<HR>";
  }

  if(!($currentPage == 1)){
    $beforePage = $currentPage - 1;
    echo $this->Html->link('前へ', "/books/detail/$bookId/$beforePage");
    echo "&nbsp&nbsp";
  }
  if(!($currentPage == $maxPage)){
    $nextPage = $currentPage + 1;
    echo $this->Html->link('次へ', "/books/detail/$bookId/$nextPage");
  }

