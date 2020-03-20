<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">
    <?php $form = ActiveForm::begin(['id' => 'addUserForm']); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask'          => '+99999999999',
        'options'       => [
            'class' => 'form-control placeholder-style',
        ],
        'clientOptions' => [
            'greedy' => false,
        ]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        1 => 'Активен',
        0 => 'Заблокирован'
    ]) ?>

    <div class="status input-group"></div>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? 'Зарегистрировать' : 'Обновить',
            [
                'id'    => 'addUserButton',
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
