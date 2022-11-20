<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reviewer".
 *
 * @property int $rev_id
 * @property string $rev_email
 * @property string $rev_password
 * @property string $rev_type
 * @property string $rev_name
 * @property string $rev_status
 * @property string $rev_enc
 *
 * @property AbstractReview[] $abstractReviews
 * @property PaperReview[] $paperReviews
 */
class Reviewer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviewer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rev_email','rev_type', 'rev_name', 'rev_status'], 'required'],
            [['rev_email'], 'email'],
            [['rev_email', 'rev_password', 'rev_name'], 'string', 'max' => 50],
            [['rev_type', 'rev_status'], 'string', 'max' => 10],
            [['rev_enc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rev_id' => 'ID',
            'rev_email' => 'Email',
            'rev_password' => 'Password',
            'rev_type' => 'Type',
            'rev_name' => 'Name',
            'rev_status' => 'Status',
            'rev_enc' => 'Rev Enc',
        ];
    }

    /**
     * Gets query for [[AbstractReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbstractReviews()
    {
        return $this->hasMany(AbstractReview::className(), ['rev_id' => 'rev_id']);
    }

    /**
     * Gets query for [[PaperReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaperReviews()
    {
        return $this->hasMany(PaperReview::className(), ['rev_id' => 'rev_id']);
    }
}
