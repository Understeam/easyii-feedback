<?php
namespace understeam\easyyii\feedback;

class FeedbackModule extends \yii\easyii\components\Module
{
    public $settings = [
        'formFields' => 'email:E-mail, name:Укажите имя, url:URL, phone:Номер телефона',
        'gridFields' => 'email, name, url, phone',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Feedback',
            'ru' => 'Обратная связь',
        ],
        'icon' => 'earphone',
        'order_num' => 60,
    ];

    private static $_formFields;

    public static function getFormFields()
    {
        if (!isset(self::$_formFields)) {
            $config = explode(',', self::setting('formFields'));
            $fields = [];
            foreach ($config as $item) {
                if (!$item) {
                    continue;
                }
                $parts = explode(':', $item);
                $name = trim($parts[0]);
                if (count($parts) === 2) {
                    $label = $parts[1];
                } else {
                    $label = $name;
                }
                $fields[$name] = $label;
            }
            self::$_formFields = $fields;
        }
        return self::$_formFields;
    }

    private static $_gridFields;

    public static function getGridFields()
    {
        if (!isset(self::$_gridFields)) {
            $formFields = self::getFormFields();
            $config = explode(',', self::setting('gridFields'));
            $fields = [];
            foreach ($config as $item) {
                if (!$item) {
                    continue;
                }
                $parts = explode(':', $item);
                $name = trim($parts[0]);
                if (count($parts) === 2) {
                    $label = $parts[1];
                } else {
                    $label = isset($formFields[$name]) ? $formFields[$name] : $name;
                }
                $fields[$name] = $label;
            }
            self::$_gridFields = $fields;
        }
        return self::$_gridFields;
    }

    public static function getGridColumns()
    {
        $columns = [];
        foreach (self::getGridFields() as $name => $label) {
            $columns[] = [
                'attribute' => $name,
                'header' => $label,
            ];
        }
        return $columns;
    }
}
