<?php
/**
 * User class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;
 
/**
 * Application user model.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 * 
 * @property integer $id
 * @property string $username
 * @property string $company_name
 * @property string $auth_key
 * @property string $api_key
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 10;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys($this->statusList()), 'on' => 'root'],
            ['username', 'filter', 'filter' => 'trim'],
            [['email', 'username', 'password'], 'required'],
            [['email', 'username', 'api_key'], 'unique'],
            [['username', 'company_name', 'password'], 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'        => Yii::t('modules/user', 'Email'),
            'username'     => Yii::t('modules/user', 'Login'),
            'company_name' => Yii::t('modules/user', 'Company name'),
            'auth_key'     => Yii::t('modules/user', 'Auth key'),
            'api_key'      => Yii::t('modules/user', 'Api key'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_user}}';
    }

    /**
     * gets all available user status list
     * @return array statuses
     */
    public static function statusList()
    {
        return [
            self::STATUS_INACTIVE => Yii::t('modules/user', 'Deactivated'),
            self::STATUS_ACTIVE   => Yii::t('modules/user', 'Active')
        ];
    }

    /**
     * Gets model readabe status name.
     * @return string
     */
    public function getStatusName()
    {
        return self::statusList()[$this->status];
    }

    /**
     * Activates user.
     * @return boolean
     */
    public function activate()
    {
        return $this->updateAttributes([
            'status' => self::STATUS_ACTIVE,
            'auth_key' => null
        ]);
    }
    
    /**
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($auth_key, $type = NULL)
    {
        static::findOne(compact('auth_key'));
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire    = \Yii::$app->getModule('user')->params['resetTokenExpireSeconds'];
        $parts     = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password original password.
     * @return string hashed password.
     */
    public function generatePassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        return $this->auth_key = md5(Yii::$app->security->generateRandomKey());
    }

    public function generateApiKey()
    {
        return $this->api_key = md5(Yii::$app->security->generateRandomKey());
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = md5(Yii::$app->security->generateRandomKey()) . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert && !$this->api_key) {
            $this->generateApiKey();
        }
        if ($insert && !$this->auth_key) {
            $this->generateAuthKey();
        }
        if ($insert || $this->isAttributeChanged('password')) {
            $this->password = $this->generatePassword($this['password']);
        }
        return true;
    }
}
