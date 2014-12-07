<?php 
  echo "<br /><br /><br />",h($tagName),"のタグラン<br /><HR>";

  for($i=0; isset($bookName[$i]); $i++){
    $rank++;
    echo "<br />&nbsp&nbsp",$rank,"位&nbsp&nbsp&nbsp&nbsp";
    echo $this->Html->link("$bookName[$i]","/books/detail/$bookId[$i]/1")
    ,"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
    echo "登録者数：&nbsp&nbsp", $tagCount[$i], "<br /><br /><HR>";
  }

  if(!($currentPage == 1)){
    $beforePage = $currentPage - 1;
    echo $this->Html->link('前へ', "/tagrans/ranking/$tagId/$beforePage");
    echo "&nbsp&nbsp";
  }
  if(!($currentPage == $maxPage)){
    $nextPage = $currentPage + 1;
    echo $this->Html->link('次へ', "/tagrans/ranking/$tagId/$nextPage");
  }

?>