<?php
App::uses('AppModel', 'Model');

class UserInformation extends AppModel {

  //パスワードの同一性チェックをする。
  public function sameCheck($value , $field_name) {
      $v1 = array_shift($value);
      $v2 = $this->data[$this->name][$field_name];
      return $v1 == $v2;
  }

  var $name = 'UserInformation';

  //入力チェック機能
  public $validate = array(
    'username' => array(
      array(
        'rule' => 'isUnique', //重複禁止
	'required' => true,
        'message' => '既に使用されているユーザ名です。'
      ),
      array(
        'rule' => array('between', 4, 20), //4～20文字
	'required' => true,
        'message' => 'ユーザ名は4文字以上20文字以内にしてください。'
      ),
      array(
	'rule' => array('custom', '/^[a-zA-Z0-9_\-]*$/'),
	'required' => true,
	'message' => 'ユーザ名はa～z、A～Z、_、-を使用してください。'
      )
    ),
    'accountname' => array(
      array(
        'rule' => array('between', 4, 20), //4～20文字
	'required' => true,
        'message' => '名前は4文字以上20文字以内にしてください。'
      ),
      array(
	'rule' => array('custom', '/^[、-◯a-zA-Z0-9_\-]*$/'),
	'required' => true,
	'message' => '名前は全角もしくはa～z、A～Z、_、-を使用してください。'         )
    ),
    'password' => array(
      array(
        'rule' => array('custom', '/^[a-zA-Z0-9_\-]*$/'),
	'required' => true,
        'message' => 'パスワードはa～z、A～Z、_、-にしてください。'
      ),
      array(
        'rule' => array('between', 8, 20),
        'message' => 'パスワードは8文字以上20文字以内にしてください。'
      )
    ),
    'passwordagain' => array(
      array(
        'rule' => array('sameCheck','password'),
	'required' => true,
        'message' => 'パスワードが一致しません。'
      )
    )
  );

  public function beforeSave($options = array()) {
    $this->data['UserInformation']['password'] = AuthComponent::password($this->data['UserInformation']['password']);
    $this->data['UserInformation']['passwordagain'] = AuthComponent::password($this->data['UserInformation']['passwordagain']);
    return true;
  }
}