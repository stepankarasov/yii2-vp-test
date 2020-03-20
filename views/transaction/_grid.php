<?php

use app\models\Transaction;
use yii\grid\GridView;
use yii\helpers\Html;
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
    'showFooter'   => true,
    //'filterModel' => $searchModel,
    'options'      => [
        'class' => 'table-responsive table-responsive-sm',
    ],
    'rowOptions'   => function ($model, $key, $index, $grid)
    {
        $class = $index % 2 ? 'odd' : 'even';

        return [
            'key'   => $key,
            'index' => $index,
            'class' => $class,
        ];
    },
    'tableOptions' => [
        'align' => 'center',
        'class' => 'table table-striped table-bordered table-hover text-center'
    ],
    'columns'      => [
        [
            'attribute' => 'id',
            'label'     => 'ID',
            'footer'    => '<b>Итого:</b>'
        ],
        [
            'attribute' => 'user.id',
            'label'     => 'Пользователь',
            'format'    => 'raw',
            'value'     => function ($data)
            {
                return $data->user->fullName;
            },
        ],
        [
            'attribute' => 'amount',
            'footer'    => Transaction::getTotal($dataProvider->models, 'amount'),
        ],
        [
            'attribute' => 'status',
            'label'     => 'Статус',
            'format'    => 'raw',
            'value'     => function ($data)
            {
                if ($data->status) {
                    return '<span class="label label-success">Исполнено</span>';
                } else {
                    return '<span class="label label-default">Отменено</span>';
                }
            },
            'filter'    => [
                1 => 'Исполнено',
                0 => 'Отменено',
            ],
        ],
        [
            'attribute' => 'created_at',
            'label'     => 'Создан',
            'format'    => ['datetime', 'php:d.m.Y H:i'],
        ],

        [
            'class'    => \yii\grid\ActionColumn::class,
            'template' => '{cancel}',
            'buttons'  => [
                'cancel' => function ($url, $model, $key)
                {
                    if ($model->status) {
                        return Html::a('Отменить', "transactions/{$model->id}/cancel", [
                            'title'        => 'Отменить платеж',
                            'data-confirm' => 'Действительно отменить платеж?',
                            'data-method'  => 'post',
                            'data-pjax'    => '0',
                        ]);
                    }

                    return false;
                },
            ],
        ],
    ],
]);
?>

<?php Pjax::end() ?>
