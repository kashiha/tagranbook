<?php
App::uses('AppController', 'Controller');

class TagransController extends AppController {
 //読み込むモデルの指定

  var $name = 'Tagran';
  var $uses = array('BookTag', 'BookInformation','BookTagCount'
  ,'TagInformation');

  //読み込むコンポーネントの指定
  public $components = array('Session', 'Auth');
  
  public function index(){
    $tagCount = $this->TagInformation->find('count');
    for($i=0;$i<3;$i++){
      $id = rand(1, ($tagCount -1));
	  //被っていたらもう一度
	  for($j=0;$j<$i;$j++){
		while($tagId[$j] == $id){
			$id = rand(1, ($tagCount -1));
		}
	  }
      $tagId[$i] = $id;
      $tagInfo[$i] = $this->TagInformation->find('first',array(
        'conditions' => array('tagid' => $tagId[$i])
      ));
      $tagName[$i] = $tagInfo[$i]["TagInformation"]["tagname"];
    }

    $bookList1 = $this->BookTagCount->find('all', array(
      'conditions' => array('tagid' => $tagId[0]),
      'order' => array('count DESC'),
      'limit' => 3
    ));
    $bookList2 = $this->BookTagCount->find('all', array(
      'conditions' => array('tagid' => $tagId[1]),
      'order' => array('count DESC'),
      'limit' => 3
    ));
    $bookList3 = $this->BookTagCount->find('all', array(
      'conditions' => array('tagid' => $tagId[2]),
      'order' => array('count DESC'),
      'limit' => 3
    ));
    for($i=0; $i<3; $i++){
      if(isset($bookList1[$i]["BookTagCount"]["bookid"])){
	$bookNames1[$i] = $this->BookInformation->find('first',array(
	  'conditions' => array(
	    'bookid' => $bookList1[$i]["BookTagCount"]["bookid"]
	  ),
	  'fields' => array('bookname')
	));
	$bookName1[$i] = $bookNames1[$i]["BookInformation"]["bookname"];
      }else{
	$bookName1[$i] = null;
      }
      if(isset($bookList2[$i]["BookTagCount"]["bookid"])){
	$bookNames2[$i] = $this->BookInformation->find('first',array(
	  'conditions' => array(
	    'bookid' => $bookList2[$i]["BookTagCount"]["bookid"]
	  ),
	  'fields' => array('bookname')
	));
	$bookName2[$i] = $bookNames2[$i]["BookInformation"]["bookname"];
      }else{
	$bookName2[$i] = null;
      }
      if(isset($bookList3[$i]["BookTagCount"]["bookid"])){
	$bookNames3[$i] = $this->BookInformation->find('first',array(
	  'conditions' => array(
	    'bookid' => $bookList3[$i]["BookTagCount"]["bookid"]
	  ),
	  'fields' => array('bookname')
	));
	$bookName3[$i] = $bookNames3[$i]["BookInformation"]["bookname"];
      }else{
	$bookName3[$i] = null;
      }
    }
      $this->set('tagId', $tagId);
      $this->set('tagName', $tagName);
      $this->set('bookList1', $bookName1);
      $this->set('bookList2', $bookName2);
      $this->set('bookList3', $bookName3);
  }

  public function ranking($tagId, $page){
    //タグが登録されている本を登録者数で並べる
    $bookList = $this->BookTagCount->find('all', array(
      'conditions' => array('tagid' => $tagId),
      'order' => array('count DESC'),
      'limit' => 10,
      'page' => $page
    ));
    $bookListCount = $this->BookTagCount->find('count', array(
      'conditions' => array('tagid' => $tagId),
      'limit' => 10,
      'page' => $page
    ));
    $bookListCountAll = $this->BookTagCount->find('count', array(
      'conditions' => array('tagid' => $tagId)
    ));

    for($i=0; $i < $bookListCount; $i++){
      $bookId[$i] = $bookList[$i]["BookTagCount"]["bookid"];
      $bookInfo[$i] = $this->BookInformation->find('first',array(
	'conditions' => array('bookid' => $bookId[$i]
	)
      ));
      $bookName[$i] = $bookInfo[$i]["BookInformation"]["bookname"];
      $bookId[$i] = $bookInfo[$i]["BookInformation"]["bookid"];
      $tagCount[$i] = $bookList[$i]["BookTagCount"]["count"];
    }

    if($bookListCount == 0){
      $bookName = 0;
      $bookId = 0;
      $tagCount = 0;
    }

    $tagInfo = $this->TagInformation->find('first', array(
      'conditions' => array('tagid' => $tagId
      )
    ));
    $tagName = $tagInfo["TagInformation"]["tagname"];

    //最大ページ数を設定
    //表示件数が10件×nのとき最大ページはn
    if($bookListCountAll == 0){
      $maxPage = 1;
    }
    else if(($bookListCountAll % 10) == 0){
      $maxPage = $bookListCountAll / 10;
    }
    //それ以外のときは(int)件数/10+1が最大ページ数
    else{
      $maxPage = (int) ($bookListCountAll / 10);
      $maxPage++;
    }

    //順位の初期値設定
    $currentpage = intval($page);
    $rank = ($currentpage - 1) * 10;

    $this->set('tagId', $tagId);
    $this->set('tagName', $tagName);
    $this->set('bookId', $bookId);
    $this->set('bookName', $bookName);
    $this->set('tagCount', $tagCount);
    $this->set('currentPage', $page);
    $this->set('maxPage', $maxPage);
    $this->set('rank', $rank);
    
  }
}
