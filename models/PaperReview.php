<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paper_review".
 *
 * @property int $id
 * @property int $rev_id
 * @property int $paper_id
 * @property int $abs_id
 * @property string|null $comment_from_reviewer
 * @property string|null $response_from_author
 * @property string|null $acceptance_status
 * @property string|null $file_path
 *
 * @property Papers $paper
 * @property Abstracts $abs
 * @property Reviewer $rev
 */
class PaperReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paper_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rev_id', 'paper_id', 'abs_id'], 'required'],
            [['rev_id', 'paper_id', 'abs_id'], 'integer'],
            [['comment_from_reviewer', 'response_from_author'], 'string'],
            [['acceptance_status'], 'string', 'max' => 100],
            [['file_path'], 'string', 'max' => 500],
            [['paper_id'], 'exist', 'skipOnError' => true, 'targetClass' => Papers::className(), 'targetAttribute' => ['paper_id' => 'paper_id']],
            [['abs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abstracts::className(), 'targetAttribute' => ['abs_id' => 'abs_id']],
            [['rev_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reviewer::className(), 'targetAttribute' => ['rev_id' => 'rev_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rev_id' => 'Reviewer ID',
            'paper_id' => 'Paper ID',
            'abs_id' => 'Abs ID',
            'comment_from_reviewer' => 'Comment From Reviewer',
            'response_from_author' => 'Response From Author',
            'acceptance_status' => 'Acceptance Status',
            'file_path' => 'File Path',
        ];
    }

    /**
     * Gets query for [[Paper]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaper()
    {
        return $this->hasOne(Papers::className(), ['paper_id' => 'paper_id']);
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
     * Gets query for [[Rev]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRev()
    {
        return $this->hasOne(Reviewer::className(), ['rev_id' => 'rev_id']);
    }
}
