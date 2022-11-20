<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "abstract_review".
 *
 * @property int $id
 * @property int $rev_id
 * @property int $abs_id
 * @property string|null $comments
 * @property string|null $response
 * @property string|null $file_path
 *
 * @property Reviewer $rev
 * @property Abstracts $abs
 */
class AbstractReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'abstract_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rev_id', 'abs_id'], 'required'],
            [['rev_id', 'abs_id'], 'integer'],
            [['comments', 'response','status'], 'string'],
            [['rev_id', 'abs_id'], 'unique', 'targetAttribute' => ['rev_id', 'abs_id'],'message' => 'The combination of Reviewer and Abstract has been used. Please select another Reviewer'],
            [['rev_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reviewer::className(), 'targetAttribute' => ['rev_id' => 'rev_id']],
            [['abs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abstracts::className(), 'targetAttribute' => ['abs_id' => 'abs_id']],
            [['file_path'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf'], 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rev_id' => 'Reviewer',
            'abs_id' => 'Abstract',
            'comments' => 'Comments from Reviewer',
            'response' => 'Response from Author',
            'file_path' => 'File from Reviewer',
            'status' => 'Recommendation'
        ];
    }

    /**
     * Gets query for [[Rev]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRev()
    {
        return $this->hasOne(Reviewer::className(), ['rev_id' => 'rev_id']);
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
}
