<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'myfunc');

class SearchesController extends AppController {
 //読み込むモデルの指定

  var $name = 'Searches';
  var $uses = array('UserInformation', 'BookInformation', 'BookTagCount'
  , 'FavoriteBook', 'TagInformation');

  //読み込むコンポーネントの指定
  public $components = array('Session');

  public function index(){
    if($this->request->is('post')){
      $word = $this->data['word'];
      $object = $this->data['object'];
      if($word == null){
	$word = "not_exist_word!!";
      }
      $this->redirect("/searches/result/$object/$word/1");
    }
  }

  public function result($config, $keyword, $page){

    if($this->request->is('post')){
      $word = $this->data['word'];
      $object = $this->data['object'];
      if($word == null){
	$word = "not_exist_word!!";
      }
      $this->redirect("/searches/result/$object/$word/1");
    }

    switch($config){
      //ユーザ名、アカウント名でlike検索、ユーザ名とアカウント名を表示
      case "user":
	$userInfo = $this->UserInformation->find('all', array(
          'conditions' => array(
            'or' => array(
              "username like" => "%$keyword%",
              "accountname like" => "%$keyword%"
          )),
	  'limit' => 10,
	  'page' => $page
	));
	$userCount = $this->UserInformation->find('count', array(
          'conditions' => array(
            'or' => array(
              "username like" => "%$keyword%",
              "accountname like" => "%$keyword%"
          ))
	));
	for($i=0; isset($userInfo[$i]); $i++){
	  $userName[$i] = $userInfo[$i]["UserInformation"]["username"];
	  $accountName[$i] = $userInfo[$i]["UserInformation"]["accountname"];
	  $userId[$i] = $userInfo[$i]["UserInformation"]["userid"];
	}
	$maxPage = page($userCount);

	if($userInfo == null){
	  $userName = 0;
	  $userId = 0;
	  $accountName = 0;
	}

	$this->set('userName', $userName);
	$this->set('accountName', $accountName);
	$this->set('userId', $userId);

	break;

      //本のタイトル、著者名でlike検索
      //タイトル、著者名、お気に入りボタンを表示
      case "book":
	$bookInfo = $this->BookInformation->find('all', array(
          'conditions' => array(
            'or' => array(
              "bookname like" => "%$keyword%",
              "author like" => "%$keyword%"
          )),
	  'limit' => 10,
	  'page' => $page
	));
	$bookCount = $this->BookInformation->find('count', array(
          'conditions' => array(
            'or' => array(
              "bookname like" => "%$keyword%",
              "author like" => "%$keyword%"
          ))
	));
	for($i=0; isset($bookInfo[$i]); $i++){
	  $bookName[$i] = $bookInfo[$i]["BookInformation"]["bookname"];
	  $author[$i] = $bookInfo[$i]["BookInformation"]["author"];
	  $bookId[$i] = $bookInfo[$i]["BookInformation"]["bookid"];
	  $favorite[$i] = $this->FavoriteBook->find('count', array(
	    'conditions' => array(
	      'bookid' => $bookId[$i],
	      'userid' => $this->Session->read('User.id')
	    )
	  ));
	  if($favorite[$i] == 0){
	    $mes[$i] = "この本をお気に入り登録する";
	  }
	  else{
	    $mes[$i] = "この本をお気に入りから解除する";
	  }
	}
	$maxPage = page($bookCount);

	if($bookInfo == null){
	  $bookName = 0;
	  $bookId = 0;
	  $author = 0;
	  $favorite = 0;
	  $mes = 0;
	}

	$this->set('bookName', $bookName);
	$this->set('author', $author);
	$this->set('bookId', $bookId);
	$this->set('favorite' ,$favorite);
	$this->set('favoriteMes', $mes);
	break;

      case "tag":
	$tagInfo = $this->TagInformation->find('all', array(
          'conditions' => array("tagname like" => "%$keyword%"),
	  'limit' => 10,
	  'page' => $page,
	  'fields' => array('tagname', 'tagid')
	));

	$tagCount = $this->TagInformation->find('count', array(
          'conditions' => array("tagname like" => "%$keyword%")
	));
	$maxPage = page($tagCount);

	for($i=0; isset($tagInfo[$i]); $i++){
	  $tagName[$i] = $tagInfo[$i]["TagInformation"]["tagname"];
	  $tagId[$i] = $tagInfo[$i]["TagInformation"]["tagid"];
        }

	if($tagInfo == null){
	  $tagName = 0;
	  $tagId = 0;
	}

        $this->set('tagName', $tagName);
        $this->set('tagId', $tagId);
        break;
    }//switch文終了

    $this->set('currentPage', $page);
    $this->set('maxPage', $maxPage);
    $this->set('word', $keyword);
    $this->set('change', $config);
  }

  public function favorite($bookId, $config, $mood, $word){
    $userId = $this->Session->read('User.id');
    $bookName = $this->BookInformation->find('first', array(
      'conditions' => array('bookid' => $bookId),
      'fields' => array('bookname')
    ));
    $books = array(
	'userid' => $userId,
	'bookid' => $bookId,
	'bookname' => $bookName["BookInformation"]["bookname"]
    );
    //$config==0(登録設定)のとき
    if($config == 0){
      $this->FavoriteBook->save($books);
    }
    //お気に入り削除($config == 1)設定
    else if($config == 1){
      $this->FavoriteBook->deleteAll($books, false);
    }
    $this->redirect("/searches/result/$mood/$word/1");
  }

}