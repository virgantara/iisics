<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paper_revision".
 *
 * @property string $id
 * @property int|null $paper_id
 * @property string|null $paper_file
 * @property string|null $author_comment
 * @property string|null $reviewer_comment
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Papers $paper
 */
class PaperRevision extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paper_revision';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['paper_id'], 'integer'],
            [['author_comment', 'reviewer_comment'], 'string'],
            
            [['updated_at', 'created_at'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['paper_id'], 'exist', 'skipOnError' => true, 'targetClass' => Papers::className(), 'targetAttribute' => ['paper_id' => 'paper_id']],
            [['paper_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf'], 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',

            'paper_id' => 'Paper ID',
            'paper_file' => 'Paper File',
            'author_comment' => 'Author Comment',
            'reviewer_comment' => 'Reviewer Comment',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
}
