<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property int $pid
 * @property string $participant_id
 * @property string $name
 * @property string $name2
 * @property string $gender
 * @property string $type
 * @property string $institution
 * @property string $address
 * @property string $country
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $password
 * @property string $registered
 * @property string $token
 * @property string $reset_key
 * @property string $activation_code
 * @property int $active
 * @property int $paid
 * @property int $enable
 * @property string $status
 * @property string $regsuccess
 * @property int $certificate
 * @property int $as_presenter
 * @property int $block
 * @property string $no_certificate
 */
class Participants extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participants';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name2', 'gender', 'type', 'institution', 'address', 'country', 'phone', 'fax', 'email'], 'required'],
            [['registered'], 'safe'],
            [['active', 'paid', 'enable', 'certificate', 'as_presenter', 'block'], 'integer'],
            [['participant_id', 'email', 'no_certificate'], 'string', 'max' => 50],
            [['name', 'name2'], 'string', 'max' => 100],
            [['gender', 'activation_code'], 'string', 'max' => 10],
            [['type', 'phone', 'fax'], 'string', 'max' => 25],
            [['institution', 'country'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 300],
            [['password', 'reset_key'], 'string', 'max' => 32],
            [['token'], 'string', 'max' => 500],
            [['status'], 'string', 'max' => 15],
            [['regsuccess'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'Pid',
            'participant_id' => 'Participant ID',
            'name' => 'Full Name without academic title',
            'name2' => 'Full Name with academic title',
            'gender' => 'Gender',
            'type' => 'Type',
            'institution' => 'Institution',
            'address' => 'Address',
            'country' => 'Country',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'email' => 'Email',
            'password' => 'Password',
            'registered' => 'Registered',
            'token' => 'Token',
            'reset_key' => 'Reset Key',
            'activation_code' => 'Activation Code',
            'active' => 'Active',
            'paid' => 'Paid',
            'enable' => 'Enable',
            'status' => 'Status',
            'regsuccess' => 'Regsuccess',
            'certificate' => 'Certificate',
            'as_presenter' => 'As Presenter',
            'block' => 'Block',
            'no_certificate' => 'No Certificate',
        ];
    }
}
