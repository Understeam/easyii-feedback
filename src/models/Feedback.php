<?php
namespace understeam\easyii\feedback\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use understeam\easyii\feedback\FeedbackModule;
use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class Feedback TODO: Write class description
 * @author Anatoly Rugalev
 * @link https://github.com/AnatolyRugalev
 *
 * @property integer $feedback_id
 * @property string $dataJson
 * @property integer $time
 * @property integer $status
 * @property string $answer_subject
 * @property string $answer_text
 */
class Feedback extends \yii\easyii\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;

    const FLASH_KEY = 'eaysiicms_feedback_send_result';

    public $reCaptcha;

    private $_data;

    public static function tableName()
    {
        return 'understeam_feedback';
    }

    public function rules()
    {
        $fields = FeedbackModule::getFormFields();
        if (!count($fields)) {
            return [];
        }
        return [
            [array_keys($fields), 'string'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->_data !== null) {
                $this->dataJson = Json::encode($this->_data);
                $this->_data = null;
            }
            if ($insert) {
                $this->time = time();
                $this->status = self::STATUS_NEW;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->mailAdmin();
        }
    }

    public function mailAdmin()
    {
        $moduleName = FeedbackModule::getModuleName(FeedbackModule::className());
        $settings = Yii::$app->getModule('admin')->activeModules[$moduleName]->settings;

        if (!$settings['mailAdminOnNewFeedback']) {
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewFeedback'],
            $settings['templateOnNewFeedback'],
            ['feedback' => $this, 'link' => Url::to(['/admin/' . $moduleName . '/a/view', 'id' => $this->primaryKey], true)]
        );
    }

    public function sendAnswer()
    {
        $moduleName = FeedbackModule::getModuleName(FeedbackModule::className());
        $settings = Yii::$app->getModule('admin')->activeModules[$moduleName]->settings;

        return Mail::send(
            $this->email,
            $this->answer_subject,
            $settings['answerTemplate'],
            ['feedback' => $this],
            ['replyTo' => Setting::get('admin_email')]
        );
    }

    public function attributeLabels()
    {
        return FeedbackModule::getFormFields();
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function () {
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }

    public function getData()
    {
        if (!isset($this->_data)) {
            if ($this->dataJson) {
                $this->_data = Json::decode($this->dataJson);
            } else {
                return [];
            }
        }
        return $this->_data;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function __get($name)
    {
        $fields = FeedbackModule::getFormFields();
        if (isset($fields[$name])) {
            $data = $this->getData();
            return isset($data[$name]) ? $data[$name] : null;
        }
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        $fields = FeedbackModule::getFormFields();
        if (isset($fields[$name])) {
            $data = $this->getData();
            $data[$name] = $value;
            $this->setData($data);
            return;
        }
        parent::__set($name, $value);
    }
}