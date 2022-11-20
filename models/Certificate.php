<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "certificate".
 *
 * @property int $cert_id
 * @property string $cert_no
 * @property int $pid
 * @property int $abs_id
 * @property string $name
 * @property int $type_id
 *
 * @property Participants $p
 * @property Abstracts $abs
 * @property CertificateType $type
 */
class Certificate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'certificate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cert_no', 'pid', 'abs_id', 'name', 'type_id'], 'required'],
            [['pid', 'abs_id', 'type_id'], 'integer'],
            [['cert_no', 'name'], 'string', 'max' => 50],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Participants::className(), 'targetAttribute' => ['pid' => 'pid']],
            [['abs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abstracts::className(), 'targetAttribute' => ['abs_id' => 'abs_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CertificateType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cert_id' => 'Cert ID',
            'cert_no' => 'Cert No',
            'pid' => 'Pid',
            'abs_id' => 'Abs ID',
            'name' => 'Name',
            'type_id' => 'Type ID',
        ];
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
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CertificateType::className(), ['id' => 'type_id']);
    }
}
