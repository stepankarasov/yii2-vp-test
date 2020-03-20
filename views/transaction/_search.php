<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

$pickerRanges = [
    'Сегодня' => [
        new JsExpression('moment()'),
        new JsExpression('moment()')
    ],
    'Вчера'   => [
        new JsExpression('moment().subtract(1,"days")'),
        new JsExpression('moment().subtract(1,"days")')
    ],
    'Неделя'  => [
        new JsExpression('moment().startOf(\'isoWeek\')'),
        new JsExpression('moment().endOf(\'isoWeek\')')
    ],
    'Месяц'   => [
        new JsExpression('moment().startOf(\'month\')'),
        new JsExpression('moment().endOf(\'month\')')
    ],
];
?>

<div class="transaction-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'user_id') ?>
            <?= $form->field($model, 'status')->dropDownList([
                1 => 'Исполнено',
                0 => 'Отменено',
            ], [
                'prompt' => 'Все'
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'userPhone') ?>
            <?= $form->field($model, 'created_at', [
                'options' => ['class' => 'drp-container form-group',]
            ])->widget(DateRangePicker::class, [
                'convertFormat' => true,
                'options'       => ['placeholder' => 'Даты', 'class' => 'form-control'],
                'pluginOptions' => [
                    'locale' => [
                        'format' => 'd.m.Y'
                    ],
                    'ranges' => $pickerRanges
                ]
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', ['/transactions'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
