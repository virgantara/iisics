<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $pay_id
 * @property int $pid
 * @property int $abs_id
 * @property string $pay_created
 * @property string $pay_date
 * @property string $pay_file
 * @property string $pay_method
 * @property string $pay_origin
 * @property string $pay_destination
 * @property string $pay_currency
 * @property int $pay_nominal
 * @property string $pay_info
 * @property string $pay_status None, Valid, Invalid
 * @property int $valid_by
 * @property string $valid_by_name
 *
 * @property Abstracts $abs
 * @property Participants $p
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'abs_id', 'pay_date', 'pay_method', 'pay_origin', 'pay_destination', 'pay_currency', 'pay_nominal'], 'required'],
            [['pid', 'abs_id', 'pay_nominal', 'valid_by'], 'integer'],
            [['pay_created', 'pay_date'], 'safe'],
            [['pay_info'], 'string'],
            [['pay_method', 'pay_origin', 'pay_destination', 'valid_by_name'], 'string', 'max' => 50],
            [['pay_currency', 'pay_status'], 'string', 'max' => 10],
            [['abs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abstracts::className(), 'targetAttribute' => ['abs_id' => 'abs_id']],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Participants::className(), 'targetAttribute' => ['pid' => 'pid']],
            [['pay_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','jpeg','png'], 'maxSize' => 1024 * 1024 * 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => 'Pay ID',
            'pid' => 'Participant',
            'abs_id' => 'Abstract Title',
            'pay_created' => 'Pay Created',
            'pay_date' => 'Payment Date',
            'pay_file' => 'Proof of Payment',
            'pay_method' => 'Method',
            'pay_origin' => 'From Bank',
            'pay_destination' => 'Conference Bank Account',
            'pay_currency' => 'Currency',
            'pay_nominal' => 'Amount',
            'pay_info' => 'Payment Info',
            'pay_status' => 'Payment Status',
            'valid_by' => 'Valid By',
            'valid_by_name' => 'Valid By Name',
        ];
    }

    /**
     * Gets query for [[Abs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbs()
    {
        return $this->hasOne(Abstracts::className(), ['abs_id' => 'abs_id']);
    }

    /**
     * Gets query for [[P]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Participants::className(), ['pid' => 'pid']);
    }
}
