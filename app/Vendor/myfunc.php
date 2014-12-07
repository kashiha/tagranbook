<?php
  //最大ページ数を設定
  function page($count){
    //表示件数が10件×nのとき最大ページはn
    if($count == 0){
      $maxPage = 1;
    }
    else if(($count % 10) == 0){
      $maxPage = $count / 10;
    }
    //それ以外のときは(int)件数/10+1が最大ページ数
    else{
      $maxPage = (int) ($count / 10);
      $maxPage++;
    }
    return $maxPage;
  }

?>