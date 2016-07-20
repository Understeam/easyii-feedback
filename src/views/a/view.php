<?php
use yii\helpers\Html;
use understeam\easyii\feedback\models\Feedback;
use yii\widgets\ActiveForm;

/**
 * @var Feedback $model
 */

$this->title = Yii::t('understeam/feedback', 'View feedback');
$this->registerCss('.feedback-view dt{margin-bottom: 10px;}');

if ($model->status == Feedback::STATUS_ANSWERED) {
    $this->registerJs('$(".send-answer").click(function(){return confirm("' . Yii::t('understeam/feedback', 'Are you sure you want to resend the answer?') . '");})');
}
?>
<?= $this->render('_menu', ['noanswer' => $model->status == Feedback::STATUS_ANSWERED]) ?>

    <dl class="dl-horizontal feedback-view">

        <?php foreach($model->getData() as $field => $value): ?>
            <dt><?=$model->getAttributeLabel($field) ?></dt>
            <dd><?=Html::encode($value) ?></dd>
        <?php endforeach; ?>

        <dt><?= Yii::t('easyii', 'Date') ?></dt>
        <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>
    </dl>

    <hr>
    <h2>
        <small><?= Yii::t('understeam/feedback', 'Answer') ?></small>
    </h2>

<?php $form = ActiveForm::begin() ?>
<?= $form->field($model, 'answer_subject') ?>
<?= $form->field($model, 'answer_text')->textarea(['style' => 'height: 250px']) ?>
<?= Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-success send-answer']) ?>
<?php ActiveForm::end() ?>