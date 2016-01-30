<?php

namespace bariew\userModule\models;

use Yii;
use yii\authclient\BaseOAuth;

/**
 * This is the model class for table "user_auth".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $name
 * @property string $service_id
 * @property integer $created_at
 * @property string $data
 *
 * @property User $user
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('modules/user', 'ID'),
            'user_id' => Yii::t('modules/user', 'User ID'),
            'name' => Yii::t('modules/user', 'Name'),
            'service_id' => Yii::t('modules/user', 'Service ID'),
            'created_at' => Yii::t('modules/user', 'Created At'),
            'data' => Yii::t('modules/user', 'Data'),
        ];
    }

    public static function clientUser(BaseOAuth $client)
    {
        $attributes = [
            'name' => $client->getName(),
            'service_id' => $client->id
        ];
        /**
         * @var self $model
         */
        if (!$model = self::findOne($attributes)) {
            $model = new self(array_merge($attributes, [
                'created_at' => time(),
                'data' => json_encode($client->getUserAttributes())
            ]));
            $model->save(false);
            $user = new User([
                'username' => $model->name . $model->id,
                'status' => User::STATUS_ACTIVE
            ]);
            $user->save(false);
            $model->updateAttributes(['user_id' => $user->id]);
            return $user;
        }
        return $model->user;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return self::hasOne(User::className(), ['id'=> 'user_id']);
    }
}
