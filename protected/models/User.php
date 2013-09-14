<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $profile
 */
class User extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('profile_id, is_admin', 'numerical', 'integerOnly' => true),
            array('username', 'unique'),
            array('username', 'unique', 'className' => 'Profile', 'attributeName' => 'employee_code'),
            array('username', 'length', 'max' => 20),
            array('password, created_at, updated_at', 'safe'),
            array('id, username, password, profile_id, is_admin, created_at, updated_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'profle' => array(self::BELONGS_TO, 'Profile', 'profile_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'profile_id' => 'Profile',
            'is_admin' => 'Is Admin',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('profile_id', $this->profile_id);
        $criteria->compare('is_admin', $this->is_admin);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function generatePasswordHash($raw_password)
    {
        return crypt($raw_password, Randomness::blowfinishSalt());
    }

    public function isValidPassword($raw_password)
    {
        return crypt($raw_password, $this->password) === $this->password;
    }

}
