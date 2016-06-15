<?php
/**
 * Company class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\models;

use yii\base\Event;
use yii\db\ActiveRecord;
use Yii;
 
/**
 * Application company model.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 * 
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $owner_id
 */
class Company extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'min' => 2, 'max' => 255],
            ['description', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title'        => Yii::t('modules/user', 'Title'),
            'description'     => Yii::t('modules/user', 'Description'),
            'owner_id'     => Yii::t('modules/user', 'Owner ID'),
        ];
    }

    public static function childInit(Event $event)
    {
        /** @var ActiveRecord $model */
        $model = $event->sender;
        if (!$model->hasAttribute('owner_id') || $model->getAttribute('owner_id')
            || !isset(Yii::$app->user)
        ) {
            return;
        }
        $model->setAttribute('owner_id', @Yii::$app->user->getIdentity()->owner_id);
    }
}
