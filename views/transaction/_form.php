<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

$script = <<< JS
$('#balance_by').on('change', function(){
  if ($(this).val() === 'user_id') {
    $('#div_user_id').attr("hidden", false);
    $('#div_user_phone').attr("hidden", true);
  } else {
    $('#div_user_id').attr("hidden", true);
    $('#div_user_phone').attr("hidden", false);
  }
});
JS;

$this->registerJs($script);
?>

<div class="transaction-form">
    <?php $form = ActiveForm::begin(['id' => 'topUpForm']); ?>

    <div id="div_balance_by" class="form-group field-balance_by">
        <label class="control-label" for="balance_by"></label>
        <select id="balance_by" class="form-control">
            <option value="user_phone">По телефону пользователя</option>
            <option value="user_id">По ID пользователя</option>
        </select>

        <div class="help-block"></div>
    </div>
    <div id="div_user_phone" class="form-group field-user_phone">
        <label class="control-label" for="user_phone">Телефон пользователя</label>
        <input type="text" id="user_phone" class="form-control" name="user_phone" maxlength="255"
               aria-required="true">

        <div class="help-block"></div>
    </div>

    <div id="div_user_id" class="form-group field-user_id" hidden="hidden">
        <label class="control-label" for="user_id">ID пользователя</label>
        <input type="text" id="user_id" class="form-control" name="user_id" maxlength="255" aria-required="true">

        <div class="help-block"></div>
    </div>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([
        1 => 'Исполнено',
        0 => 'Отменено'
    ]) ?>

    <div class="status input-group"></div>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? 'Пополнить' : 'Обновить',
            [
                'id'    => 'topUpButton',
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
            ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
