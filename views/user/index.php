<?php

/** @var $searchModel app\models\UserSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $modelUser app\models\User */
/** @var $modelTransaction app\models\Transaction */
/** @var $this yii\web\View */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;


$script = <<< JS
$(document).ready(function () {
    $('#addUserButton').click(function() {
        $('.status').html('');

        $.ajax({
            url: 'users/create',
            type: 'POST',
            dataType: 'json',
            data: $('form#addUserForm').serialize(),

            beforeSend: function(xhr, textStatus) {
                $('form#addUserForm :input').attr('disabled','disabled');
                $('.btn-success').attr('disabled','disabled').text('Регистрация...');
            },

            success: function(response) {
                $('.btn-success').removeAttr('disabled').text('Зарегистрировать');
                if (response.code === 200) {
                    $('form#addUserForm :input').attr('disabled','disabled');
                    $('.status').html("<i class='fa fa-check'></i> "+response.message)
                        .css('color','green');

					setTimeout(function() {
                      $("#add-user").modal('hide');
                      location.reload();
                    }, 1000);
					
                    return true;
                } else {
                    $('.status').html(response.message)
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                    return false;
                }
            },

            error: function(errors) {
                $('form#addUserForm :input').removeAttr('disabled');
                $('.btn-success').removeAttr('disabled').text('Зарегистрировать');
				var response = jQuery.parseJSON(errors.responseText);

				if (response.code === 400) {
                    let errors = '';
                    $.each(response.errors, function (index, item) {
                      errors += item+'<br>';
                    });
				  
                    $('.status').html(errors)
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                    return false;
                } else {
					$('.status').html('Ошибка соединения с сервером!')
						.css('color','red')
						.animate({'paddingLeft':'10px'},400)
						.animate({'paddingLeft':'5px'},400);
					return false;
				}
            }
        });
    });
    
    $('#topUpButton').click(function() {
        $('.status').html('');

        $.ajax({
            url: 'transactions/create',
            type: 'POST',
            dataType: 'json',
            data: $('form#topUpForm').serialize(),

            beforeSend: function(xhr, textStatus) {
                $('form#topUpForm :input').attr('disabled','disabled');
                $('.btn-success').attr('disabled','disabled').text('Пополняем...');
            },

            success: function(response) {
                $('.btn-success').removeAttr('disabled').text('Пополнить');
                if (response.code === 200) {
                    $('.status').html("<i class='fa fa-check'></i> "+response.message)
                        .css('color','green');

					setTimeout(function() {
                      $("#top-up-balance").modal('hide');
                      location.reload();
                    }, 1000);
					
                    return true;
                } else {
                    $('.status').html(response.message)
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                    return false;
                }
            },

            error: function(errors) {
                $('form#topUpForm :input').removeAttr('disabled');
                $('.btn-success').removeAttr('disabled').text('Пополнить');
				var response = jQuery.parseJSON(errors.responseText);
					
				if (response.code === 400) {
                    let errors = '';
                    $.each(response.errors, function (index, item) {
                      errors += item+'<br>';
                    });
				  
                    $('.status').html(errors)
                        .css('color','red')
                        .animate({'paddingLeft':'10px'},400)
                        .animate({'paddingLeft':'5px'},400);
                    return false;
                } else {
					$('.status').html('Ошибка соединения с сервером!')
						.css('color','red')
						.animate({'paddingLeft':'10px'},400)
						.animate({'paddingLeft':'5px'},400);
					return false;
				}
            }
        });
    });
    
	$(".container").on("change", ".switch-user-status", function(){
        const user_id = $(this).data('user_id');
        let m_status;

        if ($(this).prop('checked')) {
            m_status = loadAjax(user_id, 1);
            $(this).prop('checked', m_status);
        } else {
            m_status = loadAjax(user_id, 0);
            $(this).prop('checked', m_status);
        }
    });

    function loadAjax(m_id, m_param) {
        let m_status = m_param;
        const data = {status: m_param};
        const param = $('meta[name=csrf-param]').attr("content");
        const token = $('meta[name=csrf-token]').attr("content");

        data[param] = token;

        $.ajax({
            type: "POST",
            url: "/users/"+m_id+"/update",
            data: data,
            success: function(response) {
                if (response.code !== 200)
                    m_status = !m_status;
            },
            error: function() {
                m_status = !m_status;
            }
        });

        return m_status;
    }
});
JS;

$this->registerJs($script);

use yii\helpers\Html;
use yii\helpers\Url; ?>

<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <h3 class="page-title"><?= $this->title ?></h3>
    </div>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-user">Зарегистрировать
                пользователя
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#top-up-balance">Пополнить
                баланс
            </button>
            <div class="box-tools pull-right">
                <?= Html::a('Обновить', Url::to(), ['id' => 'refreshButton', 'class' => 'btn btn-box-tool']) ?>
            </div>
        </div>
        <div class="panel-body">
            <?= $this->render('_grid', [
                'dataProvider' => $dataProvider,
                'searchModel'  => $searchModel
            ]); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Зарегистрировать пользователя</h4>
            </div>

            <div class="modal-body">
                <?= $this->render('_form', [
                    'model' => $modelUser,
                ]); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="top-up-balance" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Пополнить баланс</h4>
            </div>
            <div class="modal-body">
                <?= $this->render('../transaction/_form', [
                    'model' => $modelTransaction,
                ]); ?>
            </div>
        </div>
    </div>
</div>
</div>
