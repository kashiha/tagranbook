<?php

  echo "<br /><br />";
  echo $this->Html->link(h($tagName[0]), "/tagrans/ranking/$tagId[0]/1");
  echo "<br /><br />";
  for($i=0; isset($bookList1[$i]);$i++){
    $rank = $i+1;
    echo "&nbsp&nbsp","$rank","位：";
    echo h($bookList1[$i]),"<br /><br />";
  }

  echo $this->Html->link(h($tagName[1]), "/tagrans/ranking/$tagId[1]/1");
  echo "<br /><br />";
  for($i=0; isset($bookList2[$i]);$i++){
    $rank = $i+1;
    echo "&nbsp&nbsp","$rank","位：";
    echo h($bookList2[$i]),"<br /><br />";
  }

  echo $this->Html->link(h($tagName[2]), "/tagrans/ranking/$tagId[2]/1");
  echo "<br /><br />";
  for($i=0; isset($bookList3[$i]);$i++){
    $rank = $i+1;
    echo "&nbsp&nbsp","$rank","位：";
    echo h($bookList3[$i]),"<br /><br />";
  }