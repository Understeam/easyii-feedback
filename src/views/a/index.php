<?php
use understeam\easyii\feedback\FeedbackModule;
use understeam\easyii\feedback\models\Feedback;

$this->title = Yii::t('understeam/feedback', 'Feedback');
$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php
$columns = [
    [
        'attribute' => 'primaryKey',
        'header' => '#'
    ],
];
$gridColumns = FeedbackModule::getGridColumns();
if (count($gridColumns)) {
    $columns = array_merge($columns, $gridColumns);
}
$columns = array_merge($columns, [
    [
        'attribute' => 'time',
        'format' => ['datetime', 'short'],
    ],
    [
        'attribute' => 'answer',
        'format' => 'raw',
        'value' => function (Feedback $model) {

            if ($model->status == Feedback::STATUS_ANSWERED) {
                return "<span class=\"text-success\">" . Yii::t('easyii', 'Yes') . "</span>";
            } else {
                return "<span class=\"text-danger\">" . Yii::t('easyii', 'No') . "</span>";
            }
        },
    ],
    [
        'format' => 'raw',
        'value' => function (Feedback $model) use ($module) {
            return \yii\helpers\Html::a("", ['/admin/' . $module . '/a/view', 'id' => $model->primaryKey], [
                'class' => 'glyphicon glyphicon-eye-open',
                'title' => Yii::t('easyii', 'View item'),
            ]);
        },
    ],
    [
        'format' => 'raw',
        'value' => function (Feedback $model) use ($module) {
            return \yii\helpers\Html::a("", ['/admin/' . $module . '/a/delete', 'id' => $model->primaryKey], [
                'class' => 'glyphicon glyphicon-remove confirm-delete',
                'title' => Yii::t('easyii', 'Delete item'),
            ]);
        },
    ],
]);
?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => $data,
    'layout' => '{items}{pager}',
    'tableOptions' => [
        'class' => 'table table-hover',
    ],
    'columns' => $columns,
]); ?>