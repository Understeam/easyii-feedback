## Установка

Установить расширение можно через [composer](http://getcomposer.org/).

```
$ php composer.phar require understeam\easyii-feedback
```

## Конфигурация

Для запуска расширения необходимо добавить класс `understeam\easyii\feedback\Bootstrap` в секцию `bootstrap` конфиг-файла `web.php`:

```
return [
    'bootstrap' => [
        'log',
        'understeam\easyii\feedback\Bootstrap',
    ],
];
```

При первичной загрузке модуля, он предложит развернуть таблицу, нужно подтвердить это действие.

## Настройка модуля

Если вы желаете заменить системный модуль Feedback Easy Yii, то его следует удалить.

Создайте новый модуль с желаемым именем (предпочтительно `feedback`). Укажите путь до класса:

```
understeam\easyii\feedback\FeedbackModule
```

Иконка по умолчанию: `earphone`

Включите модуль в списке модулей, чтобы он отобразился в главном меню панели.

Перейдите в `Advanced` настройки модуля, там есть 2 поля:

1. **formFields** - перечень полей, которые необходимо принимать внутри формы в формате "имя1:подпись1, имя2:подпись2"
2. **gridFields** - перечень полей, которые необходимо отображать в таблице в админке

## Вставка формы

Вставить форму можно следующим образом:

```php

<?php
use understeam\easyii\feedback\api\Feedback;

$model = Feedback::model();
$form = Feedback::begin();
?>
<?=$form->field($model, 'name')->textInput() ?>
<?=$form->field($model, 'phone')->textInput() ?>
<?=$form->field($model, 'email')->textInput() ?>
<?php
Feedback::end();
?>
```
