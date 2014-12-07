<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
 //読み込むモデルの指定

  var $name = 'Users';
  var $uses = array('UserInformation', 'BookInformation', 'BookTag'
  , 'FavoriteBook');
 
  //読み込むコンポーネントの指定
  public $components = array('Session', 'Auth', 'RequestHandler');
  public $helper = array('Javascript');


  //どのアクションが呼ばれてもはじめに実行される関数
  public function beforeFilter()
  {
    parent::beforeFilter();

    $this->Auth->authenticate = array(
                 // フォーム認証を利用
                 'Form' => array(
                     // 認証に利用するモデルの変更
                     'userModel' => 'UserInformation')
    );

    if ($this->RequestHandler->isAjax()) {
	// action が jsonの場合
	if ($this->action === "json") {
	  $this->layout = "ajax";
	  Configure::write("debug" , 0);
	  $this->RequestHandler->setContent("json");
	  $this->RequestHandler->respondAs('application/json; charset=UTF-8');
	}
    }

    //未ログインでアクセスできるアクションを指定
    //これ以外のアクションへのアクセスはloginにリダイレクトされる規約になっている
    $this->Auth->allow('register', 'login');
  }

  public function index(){

  }

  public function login(){

    if($this->request->is('post')) {
      if($this->Auth->login()){
	$userId = $this->Auth->user(['userid']);
	$userName = $this->Auth->user(['username']);
	$this->Session->write('User.id', $userId);
	$this->Session->write('User.name', $userName);
        return $this->redirect('/users/mypage/1');
      }
      else
        $this->Session->setFlash('ログイン失敗');
    }
  }

  public function logout(){
    $this->Auth->logout();
    $this->redirect('login');
  }

  public function register(){
    //$this->requestにPOSTされたデータが入っている
    //POSTメソッドかつユーザ追加が成功したら,
    if($this->request->is('post')
       && $this->UserInformation->save($this->request->data)){

      $name = $this->data['UserInformation']['username'];
      //登録完了画面へリダイレクト
      $this->redirect("register_complete/$name");
    }
  }

  public function register_complete($name){
    $this->set('namae',$name);
  }

  public function mypage($page){
    //ログインユーザのid取得
    $userId = $this->Auth->user(["userid"]);

    //ログインユーザのお気に入り本数、タグ数をカウント
    $myFavoriteCount = $this->FavoriteBook->find('count', array(
      'conditions' => array('userid' => $userId)
    ));
    //var_dump($myFavoriteCount);
    $myFavoriteList = $this->FavoriteBook->find('all', array(
      'conditions' => array('userid' => $userId)
    ));
    //var_dump($myFavoriteList);
    $myTag = $this->BookTag->find('count', array(
      'conditions' => array('userid' => $userId)
    ));

    for($i= 0; isset($myFavoriteList[$i]); $i++){
      $myFavoriteBooks[$i] = $myFavoriteList[$i]["FavoriteBook"]["bookid"];
    }
    //お気に入りの本にタグ付けされたタグをタイムラインに表示する
    if(isset($myFavoriteBooks)){
      $timeLine = $this->BookTag->find('all', array(
        'conditions' => array('bookid' => $myFavoriteBooks),
        'order' => array('registrationtime DESC'),
        'limit' => 10,
        'page' => $page
      ));

      for($i= 0; isset($timeLine[$i]); $i++){
        $timeLineBook[$i] = $this->BookInformation->find('first',array(
	  'conditions' => array('bookid' => $timeLine[$i]["BookTag"]["bookid"]),
	  'fields' => array('bookname')
        ));
	$timeLineBooks[$i] = $timeLineBook[$i]["BookInformation"]["bookname"];
	$timeLineBookId[$i] = $timeLine[$i]["BookTag"]["bookid"];
        $timeLineUser[$i] = $this->UserInformation->find('first',array(
	  'conditions' => array('userid' => $timeLine[$i]["BookTag"]["userid"]),
	  'fields' => array('username')
        ));
	$timeLineUserId[$i] = $timeLine[$i]["BookTag"]["userid"];
	$timeLineUsers[$i] = $timeLineUser[$i]["UserInformation"]["username"];
        $timeLineTags[$i] = $timeLine[$i]["BookTag"]["tagname"];
        $timeLineTagId[$i] = $timeLine[$i]["BookTag"]["tagid"];
        $registerTime[$i] = $timeLine[$i]["BookTag"]["registrationtime"];
      }
      if( !(isset($timeLineBook)) ){
        $timeLineTags = 0;
        $timeLineTagId = 0;
        $timeLineUsers = 0;
	$timeLineUserId = 0;
        $timeLineBooks = 0;
	$timeLineBookId = 0;
        $registerTime = 0;
      }

    }
    else{
      $timeLineTags = 0;
      $timeLineTagId = 0;
      $timeLineUsers = 0;
      $timeLineUserId = 0;
      $timeLineBooks = 0;
      $timeLineBookId = 0;
      $registerTime = 0;
    }

    //最大ページ数を設定
    //表示件数が10件×nのとき最大ページはn
    if($myFavoriteCount == 0){
      $maxPage = 1;
    }
    else if(($myFavoriteCount % 10) == 0){
      $maxPage = $myFavoriteCount / 10;
    }
    //それ以外のときは(int)件数/10+1が最大ページ数
    else{
      $maxPage = (int) ($myFavoriteCount / 10);
      $maxPage++;
    }

    $page = intval($page);

    $this->set('userId', $userId);
    $this->set('userName', $this->Auth->user(["username"]));
    $this->set('bookCount', $myFavoriteCount);
    $this->set('tagCount', $myTag);

    $this->set('bookName', $timeLineBooks);
    $this->set('bookId', $timeLineBookId);

    $this->set('tagUserName', $timeLineUsers);
    $this->set('tagUserId', $timeLineUserId);

    $this->set('tagName', $timeLineTags);
    $this->set('tagId', $timeLineTagId);

    $this->set('time', $registerTime);
    $this->set('maxPage', $maxPage);
    $this->set('currentPage', $page);

  }

}