<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php Pjax::begin() ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'pager'        => [
        'linkContainerOptions'          => ['class' => 'page-item'],
        'linkOptions'                   => ['class' => 'page-link'],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link']
    ],
    //'filterModel' => $searchModel,
    'options' => [
        'class' => 'table-responsive table-responsive-sm',
    ],
    'rowOptions' => function ($model, $key, $index, $grid){
        $class = $index %2 ? 'odd' : 'even';
        return [
            'key' => $key,
            'index' => $index,
            'class' => $class,
        ];
    },
    'tableOptions' => [
        'align' => 'center',
        'class' => 'table table-striped table-bordered table-hover text-center'
    ],
    'columns' => [
        [
            'attribute' => 'id',
            'label' => 'ID',
        ],
        'fullName',
        'phone',
        'balance',
        [
            'attribute' => 'status',
            'label' => 'Статус',
            'format' => 'raw',
            'value' => function($data) {
                $checked = ($data->status) ? 'checked' : '';
                return "<label class='switch'><input class='switch-user-status' type='checkbox' data-user_id='{$data->id}' {$checked}><span class='slider round'></span></label>";
            },
            'filter' => [
                1 => 'Активен',
                0 => 'Заблокирован',
            ],
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Создан',
            'format' => ['datetime', 'php:d.m.Y H:i'],
        ],
    ],
]); ?>

<?php Pjax::end() ?>
