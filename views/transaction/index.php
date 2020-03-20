<?php

/** @var $searchModel app\models\TransactionSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $model app\models\Transaction */
/** @var $this yii\web\View */

$this->title = 'Отчет';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <h3 class="page-title"><?= $this->title ?></h3>
    </div>
</div>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">

            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header border-bottom">
                        <h6 class="m-0">Поиск</h6>
                    </div>
                    <div class="card-body">
                        <?= $this->render('_search', [
                            'model' => $searchModel
                        ]) ?>
                    </div>
                </div>
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
