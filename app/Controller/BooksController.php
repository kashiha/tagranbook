<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'myfunc');

class BooksController extends AppController {
 //読み込むモデルの指定

  var $name = 'Books';
  var $uses = array('BookInformation' ,'BookTag' ,'BookTagCount'
		   ,'Review' ,'TagInformation', 'FavoriteBook');
 
  //読み込むコンポーネントの指定
  public $components = array('Session');

  public function register(){
    //$this->requestにPOSTされたデータが入っている
    //POSTメソッドかつ本の追加が成功したら,
    if($this->request->is('post')
       && $this->BookInformation->save($this->request->data)){

      $this->redirect("/users/index");
    }
  }

  public function detail($bookId, $page){

    //初期値設定（モード初期値はリンクモード）
    $mood = $this->Session->read('Book.mood');
    if($mood == NULL){
      $this->Session->write('Book.mood', 'link');
      $mood = $this->Session->read('Book.mood');
    }

    if($this->request->is('post')){
      //本へのコメントをしたユーザとその内容を保存
      if(isset($this->request->data["Review"]["comment"])){
	$reviewDetail = array(
	  'bookid' => $bookId,
	  'username' => $this->Session->read('User.name'),
	  'comment' => $this->request->data["Review"]["comment"],
	  'valuation' =>$this->request->data["Review"]["valuation"]
	);
	$this->Review->save($reviewDetail);
      }

      //本に新しいタグを新規登録
      if(isset($this->request->data["BookTag"]["newTag"])){
	$newTag = $this->request->data["BookTag"]["newTag"];
	$tagCount = $this->TagInformation->find('count', array(
        'conditions' => array('tagname' => $newTag)
        ));
	//新規登録するタグがすでに存在しているかどうか判断
	//ない場合は新しく登録
	if($tagCount == 0){
	  $this->TagInformation->saveField('tagname', $newTag);
	}
	$tagId = $this->TagInformation->find('first', array(
          'conditions' => array('tagname' => $newTag)
        ));
	//タグがすでに本に登録されていないか確認
	$tagConfirm = $this->BookTag->find('count', array(
          'conditions' => array(
	    'tagid' => $tagId["TagInformation"]["tagid"],
	    'bookid'=> $bookId
	  )
        ));
	//タグは登録されているが、本にタグ付けされていないのでタグ付けする
        if($tagConfirm == 0){
	  $tagCounts = array(
	    'bookid' => $bookId,
	    'tagid' => $tagId["TagInformation"]["tagid"],
	    'tagname' => $newTag,
	  );
	  $tagRegister = array(
	    'bookid' => $bookId,
	    'tagid' => $tagId["TagInformation"]["tagid"],
	    'tagname' => $newTag,
	    'userid' => $this->Session->read('User.id')
	  );
	  $this->BookTag->save($tagRegister);
	  $this->BookTagCount->save($tagCounts);
	}
	//タグ登録されるごとにカウントされる
	$registerCount = $this->BookTagCount->find('first', array(
          'conditions' => array(
	    'tagid' => $tagId["TagInformation"]["tagid"],
	    'bookid'=> $bookId
	  )
        ));
	$counter = $registerCount["BookTagCount"]["count"];
	$counter++;
	$field = array('count');
	$updateCount = array(
	    'id' => $registerCount["BookTagCount"]["id"],
	    'count' => $counter
	);

	$this->BookTagCount->save($updateCount, false, $field);
      }
    }

    //表示する本の書名と著者名、ジャンル情報の取得
    $bookInfo = $this->BookInformation->find('first' ,array(
      'conditions' => array('bookid' => $bookId)
    ));

    //表示している本のidをセッション変数に保存
    $this->Session->write('Book.id', "$bookId");

    $bookTag = $this->BookTagCount->find('all', array(
          'conditions' => array('bookid' => $bookId),
          'order' => array('count DESC')
    ));
    $booktagCount = $this->BookTagCount->find('count', array(
          'conditions' => array('bookid' => $bookId),
          'order' => array('count DESC')
    ));

    $userId = $this->Session->read('User.id');

    //ユーザが表示している本をお気に入り登録しているか判定
    $favoriteRegister = $this->FavoriteBook->find('count', array(
      'conditions' => array(
        'userid' => $userId,
	'bookid' => $bookId )
    ));

    //レビューした内容を１０件ずつ表示
    $reviewList = $this->Review->find('all', array(
      'conditions' => array('bookid' => $bookId),
      'order' => array('registrationtime DESC'),
      'limit' => 10,
      'page' => $page
    ));
    $reviewListCount = $this->Review->find('count', array(
      'conditions' => array('bookid' => $bookId),
      )
    );
    //最大ページ数を設定
    //表示件数が10件×nのとき最大ページはn
    if($reviewListCount == 0){
      $maxPage = 1;
    }
    else if(($reviewListCount % 10) == 0){
      $maxPage = $reviewListCount / 10;
    }
    //それ以外のときは(int)件数/10+1が最大ページ数
    else{
      $maxPage = (int) ($reviewListCount / 10);
      $maxPage++;
    }

    //モード名設定
    switch($mood) {
      case "reg":
	$moodname = "タグの登録・削除モード";
	break;
      case "ran":
	$moodname = "ランダム表示モード";
	break;
      case "link":
	$moodname = "TagRanリンクモード";
	break;
    }

    //お気に入りリンク表示文字設定
    switch($favoriteRegister){
      case 0:
	$favoriteMes = "この本をお気に入り登録する";
	break;
      case 1:
	$favoriteMes = "この本をお気に入りから解除する";
	break;
    }

    $page = intval($page);

    $this->set('message' ,$favoriteMes);
    $this->set('config', $favoriteRegister);
    $this->set('bookTag' ,$bookTag);
    $this->set('booktagCount' ,$booktagCount);
    $this->set('bookInfo' ,$bookInfo);
    $this->set('bookId', $bookId);
    $this->set('category' ,$bookInfo["BookInformation"]["category"]);
    $this->set('mood', $mood);
    $this->set('moodName', $moodname);
    $this->set('review', $reviewList);
    $this->set('maxPage', $maxPage);
    $this->set('currentPage', $page);
  }

  public function change($mood){
    $this->Session->write('Book.mood', $mood);
    $bookId = $this->Session->read('Book.id');
    $this->redirect("/books/detail/$bookId/1");
  }

  function random($category){
    if(!($category == "0")){
      //指定したジャンルの本の数をカウント
      $bookList = $this->BookInformation->find('all', array(
        'conditions' => array('category' => $category)
      ));
      var_dump($bookList);
      $bookCount = $this->BookInformation->find('count', array(
        'conditions' => array('category' => $category)
      ));
      //その中からランダム関数で遷移する本を決定
      $random = rand(0, ($bookCount-1));

      $bookId = $bookList[$random]["BookInformation"]["bookid"];

      $this->redirect("/books/detail/$bookId/1");
    }
    else{
      $bookList = $this->BookInformation->find('all');
      $bookCount = $this->BookInformation->find('count');

      $random = rand(0, ($bookCount-1));

      $bookId = $bookList["$random"]["BookInformation"]["bookid"];

      $this->redirect("/books/detail/$bookId/1");
    }
  }

  public function button($mood, $tagId){
    switch($mood){
      case "ran": 
	//指定したタグで登録されている本をカウント
        $tagBookList = $this->BookTagCount->find('all', array(
          'conditions' => array('tagid' => $tagId)
        ));
        $tagBookListCount = $this->BookTagCount->find('count', array(
          'conditions' => array('tagid' => $tagId)
        ));
        //その中からランダム関数で遷移する本を決定
        $tagRandom = rand(0, ($tagBookListCount-1));

        $bookId = $tagBookList["$tagRandom"]["BookTagCount"]["bookid"];
        $this->redirect("/books/detail/$bookId/1");
	
      case "reg":
	$bookId = $this->Session->read('Book.id');
	$userId = $this->Session->read('User.id');
	$registerCount = $this->BookTagCount->find('first', array(
          'conditions' => array(
	    'tagid' => $tagId,
	    'bookid'=> $bookId
	  )
        ));

	$counter = $registerCount["BookTagCount"]["count"];

	//ユーザが表示している本にそのタグをタグ付けしているかどうかの判定
	$deleteKeyCount = $this->BookTag->find('count' ,array(
	  'conditions' => array(
	    'bookid' => $bookId,
	    'tagid' => $tagId,
	    'userid' => $userId
	  )
	));

	//ユーザがタグを登録していない場合
	if($deleteKeyCount == 0){
	  $tagInfo = $this->TagInformation->find('first', array(
	    'conditions' => array('tagid' => $tagId
	    )
	  ));
	  //ユーザがタグを付けたことを記録
	  $tagRegister = array(
	      'bookid' => $bookId,
	      'tagid' => $tagId,
	      'tagname' => $tagInfo["TagInformation"]["tagname"],
	      'userid' => $userId
	  );
	  $this->BookTag->save($tagRegister);

  	  //タグ登録されるごとにカウントされる
	  $counter++;
	  $field = array('count');
	  $updateCount = array(
	    'id' => $registerCount["BookTagCount"]["id"],
	    'count' => $counter
	  );
	  $this->BookTagCount->save($updateCount, false, $field);
	}
	else{
	  //既に登録されている場合は登録を解除する
	  $tagDelete = array(
	      'bookid' => $bookId,
	      'tagid' => $tagId,
	      'userid' => $userId
	  );
	  $this->BookTag->deleteAll($tagDelete, false);

	  $counter--;
	  var_dump($counter);
	  //登録者が0人になった場合タグを削除する
	  if($counter == 0){
	    $tagCountDeleteKey = array(
	      'bookid' => $bookId,
	      'tagid' => $tagId,
	    );
	    $this->BookTagCount->deleteAll($tagCountDeleteKey, false);
	  }
	  //登録者が0人以外のときは登録者を１人減らして更新
	  else{
	    $field = array('count');
	    $updateCount = array(
	      'id' => $registerCount["BookTagCount"]["id"],
	      'count' => $counter
	    );
	    $this->BookTagCount->save($updateCount, false, $field);
	  }
	}
	$this->redirect("/books/detail/$bookId/1");

      case "link":
	$this->redirect("/tagrans/ranking/$tagId/1");
    }
  }

  public function favoritebook($bookId, $config){
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
    $this->redirect("/books/detail/$bookId/1");
  }
}