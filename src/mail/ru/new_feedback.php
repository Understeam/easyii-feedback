<?php
use yii\helpers\Html;

$this->title = $subject;
?>
<p>Пользователь оставил сообщение в вашей гостевой книге.</p>
<p>Просмотреть его вы можете <?= Html::a('здесь', $link) ?>.</p>
<hr>
<p>Это автоматическое сообщение и на него не нужно отвечать.</p>
