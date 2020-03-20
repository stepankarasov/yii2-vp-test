<?php

namespace app\behaviors\transaction;

use app\models\Transaction;
use app\models\User;
use ErrorException;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class UpdateBalanceBehavior
 * @package app\behaviors\transaction
 */
class UpdateBalanceBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }

    /**
     * @throws ErrorException
     */
    public function afterInsert()
    {
        /** @var Transaction $owner */
        $owner = $this->owner;

        /** @var User $user */
        $user = $owner->user;
        $user->addBalance($owner->amount);
    }

    /**
     * @param $input
     * @throws ErrorException
     */
    public function afterUpdate($input)
    {
        /** @var Transaction $owner */
        $owner = $this->owner;

        $newStatus = ArrayHelper::getValue($input, 'sender.status', 1);
        $oldStatus = ArrayHelper::getValue($input, 'changedAttributes.status');

        if ($newStatus != $oldStatus) {
            /** @var User $user */
            $user = $owner->user;
            if ($newStatus == 0) {
                $user->addBalance(-$owner->amount);
            }
            if ($newStatus == 1) {
                $user->addBalance($owner->amount);
            }
        }
    }
}