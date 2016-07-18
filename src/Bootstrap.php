<?php
/**
 * @link https://github.com/AnatolyRugalev
 * @copyright Copyright (c) AnatolyRugalev
 * @license https://tldrlegal.com/license/gnu-general-public-license-v3-(gpl-3)
 */

namespace understeam\easyyii\feedback;

use understeam\easyyii\feedback\models\Feedback;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\easyii\helpers\WebConsole;
use yii\helpers\Url;

/**
 * Class Bootstrap TODO: Write class description
 * @author Anatoly Rugalev
 * @link https://github.com/AnatolyRugalev
 */
class Bootstrap extends Component implements BootstrapInterface
{

    public function bootstrap($app)
    {
        Yii::setAlias('@understeam', dirname(__DIR__));
        if (!isset(Yii::$app->i18n->translations['understeam/feedback'])) {
            Yii::$app->i18n->translations['understeam/feedback'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@understeam/easyyii-feedback/src/messages',
            ];
        }
        try {
            Feedback::getTableSchema();
        } catch (InvalidConfigException $e) {
            if (Yii::$app->request->get('install-understeam-feedback') === '1') {
                ob_start();
                WebConsole::console()->runAction('migrate', [
                    'migrationPath' => __DIR__ . '/migrations',
                    'migrationTable' => 'understeam_feedback_migration',
                    'interactive' => false
                ]);
                echo "<h1>Table successfully created!</h1>";
                echo "<p><a href=\"" . strtr(Yii::$app->request->url, ['install-understeam-feedback=1' => 'installed=1']) . "\">Continue!</a></p>";
                Yii::$app->end();
            } else {
                echo "<h1>Understeam feedback module is not installed yet!</h1>";
                echo "<p><a href=\"?install-understeam-feedback=1\">Install now!</a></p>";
                Yii::$app->end();
            }
        }
    }
}
