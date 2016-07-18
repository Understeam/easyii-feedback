<?php

/**
 * @link https://github.com/AnatolyRugalev
 * @copyright Copyright (c) AnatolyRugalev
 * @license https://tldrlegal.com/license/gnu-general-public-license-v3-(gpl-3)
 */
class m000000_000000_init extends \yii\db\Migration
{

    public function up()
    {
        $this->createTable('understeam_feedback', [
            'feedback_id' => $this->primaryKey(),
            'dataJson' => $this->text(),
            'time' => $this->integer()->defaultValue(0),
            'status' => $this->boolean()->defaultValue(0),
        ], 'ENGINE=MyISAM DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('understeam_feedback');
    }

}
