<?php echo $this->Html->css('users'); ?>

<h1>新規本登録　　　　　　　　　　　　　</h1>

<div id="input_form">
  <?php
  print(
  $this->Form->create('BookInformation') .
  $this->Form->input('bookname', array(
     'type' => 'text', 
     'label' => '書名' ,)) .
  $this->Form->input('author', array(
     'type' => 'text', 
     'label' => '著者名' ,)) .
  $this->Form->input('category', array(
     'type' => 'select', 
     'label' => 'ジャンル',
     'options' => array(
	'未設定' => '----------------------',
	'文学・評論' => '文学・評論',
	'人文・思想' => '人文・思想',
	'ノンフィクション' => 'ノンフィクション',
	'社会・政治' => '社会・政治',
	'歴史・地理' => '歴史・地理',
	'ビジネス・経済' => 'ビジネス・経済',
	'科学・テクノロジー・医学' => '科学・テクノロジー・医学',
	'コンピュータ・IT' => 'コンピュータ・IT',
	'趣味・実用' => '趣味・実用',
	'教育・学参・受験' => '教育・学参・受験',
	'コミック・ラノベ' => 'コミック・ラノベ',
))) .
  $this->Form->end('新規登録')
  ); ?>
</div>