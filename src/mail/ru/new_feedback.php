<?php
use yii\helpers\Html;

/**
 * @var \understeam\easyii\feedback\models\Feedback $feedback
 * @var string $link
 * @var string $subject
 */

$this->title = $subject;
?>
<p>Пользователь оставил сообщение в вашей гостевой книге.</p>
<dl>
    <?php foreach ($feedback->getData() as $field => $value): ?>
        <dt><?= $feedback->getAttributeLabel($field) ?></dt>
        <dd><?= Html::encode($value) ?></dd>
    <?php endforeach; ?>

    <dt><?= Yii::t('easyii', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($feedback->time, 'medium') ?></dd>
</dl>
<p>Просмотреть его вы можете <?= Html::a('здесь', $link) ?>.</p>
<hr>
<p>Это автоматическое сообщение и на него не нужно отвечать.</p>
