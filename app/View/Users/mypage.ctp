  <h1> <?php echo h($userName),"さんのMyPage"; ?> </h1>

<?php 
  echo "<br />&nbsp&nbspお気に入り本の登録：&nbsp&nbsp";
  echo $this->Html->link("$bookCount","/users/myfavorite/1");
  echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
  echo "本へのタグ付け：&nbsp&nbsp";
  echo $this->Html->link("$tagCount","/users/mytag/1")
  ,"<br /><br /><br />";

  echo "&nbsp&nbspタイムライン<br /><HR><HR>";

  for($i=0; isset($bookName[$i]); $i++){
    echo "<br />&nbsp&nbsp&nbsp&nbsp";
    echo $this->Html->link("$tagUserName[$i]"
      , "/users/userpage/$tagUserId[$i]/1"),"が";

    echo $this->Html->link("$bookName[$i]"
      , "/books/detail/$bookId[$i]/1"),"に";

    echo $this->Html->link("$tagName[$i]", "/tagrans/ranking/$tagId[$i]/1");
    echo "をタグ付けしました。<br />";

    echo "<br />&nbsp&nbsp&nbsp&nbsp登録時刻：", $time[$i];
    echo "<br /><br /><HR>";
  }
  if(!($currentPage == 1)){
    $beforePage = $currentPage - 1;
    echo $this->Html->link('前へ'
    , "/users/mypage/$beforePage");
    echo "&nbsp&nbsp";
  }
  if(!($currentPage == $maxPage)){
    $nextPage = $currentPage + 1;
    echo $this->Html->link('次へ'
    , "/users/mypage/$nextPage");
  }


?>