<?php
namespace understeam\easyii\feedback\api;

use Yii;
use understeam\easyii\feedback\FeedbackModule;
use understeam\easyii\feedback\models\Feedback as FeedbackModel;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Feedback module API
 * @package understeam\easyii\feedback\api
 *
 * @method static FeedbackModel model()
 * @method static ActiveForm begin($options = [])
 * @method static end()
 */
class Feedback extends \yii\easyii\components\API
{
    const SENT_VAR = 'feedback_sent';

    private $_defaultFormOptions = [
        'errorUrl' => '',
        'successUrl' => ''
    ];

    public function api_begin($options = [])
    {
        $options = array_merge($this->_defaultFormOptions, $options);
        $moduleName = FeedbackModule::getModuleName(FeedbackModule::className());
        if (isset($options['successUrl'])) {
            $successUrl = $options['successUrl'];
            unset($options['successUrl']);
        } else {
            $successUrl = Url::current();
        }
        if (isset($options['errorUrl'])) {
            $errorUrl = $options['errorUrl'];
            unset($options['errorUrl']);
        } else {
            $errorUrl = Url::current();
        }
        $form = ActiveForm::begin(ArrayHelper::merge($options, [
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/' . $moduleName . '/send'])
        ]));
        echo Html::hiddenInput('errorUrl', $errorUrl);
        echo Html::hiddenInput('successUrl', $successUrl);
        return $form;
    }

    public function api_end()
    {
        ActiveForm::end();
    }

    public function api_model()
    {
        return new FeedbackModel();
    }

    public function api_save($data)
    {
        $model = new FeedbackModel($data);
        if ($model->save()) {
            return ['result' => 'success'];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}
