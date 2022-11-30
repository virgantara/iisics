<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "papers".
 *
 * @property int $paper_id
 * @property int $abs_id
 * @property int $pid
 * @property string $paper_file
 * @property string $paper_raw
 * @property string $paper_ext
 * @property string $paper_date
 * @property string $paper_info
 * @property string $paper_status None,Accepted,Rejected
 * @property string $paper_editor_comment
 * @property int $paper_final
 * @property int $paper_reviewed
 * @property string $paper_recomendation Accept, Revision Required, Reject
 * @property string $paper_review_comment
 * @property string $paper_review_date
 * @property string $paper_review_file
 * @property string $paper_review_file_raw
 * @property string $paper_review_file_ext
 * @property string $paper_revised_file
 * @property string $paper_revised_file_raw
 * @property string $paper_revised_file_ext
 * @property string $paper_final_file
 * @property string $paper_final_file_raw
 * @property string $paper_final_file_ext
 *
 * @property Certificate[] $certificates
 * @property PaperReview[] $paperReviews
 * @property Abstracts $abs
 * @property ReviewFile[] $reviewFiles
 */
class Papers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'papers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['abs_id', 'pid'], 'required'],
            [['abs_id', 'pid', 'paper_final', 'paper_reviewed'], 'integer'],
            [['paper_date', 'paper_review_date'], 'safe'],
            [['paper_info', 'paper_editor_comment', 'paper_review_comment'], 'string'],
            [['paper_raw', 'paper_review_file', 'paper_review_file_raw', 'paper_revised_file', 'paper_revised_file_raw', 'paper_final_file', 'paper_final_file_raw'], 'string', 'max' => 50],
            [['paper_ext', 'paper_review_file_ext', 'paper_revised_file_ext', 'paper_final_file_ext'], 'string', 'max' => 6],
            [['paper_status', 'paper_recomendation'], 'string', 'max' => 25],
            [['abs_id','pid'],'unique','targetAttribute'=>['abs_id','pid']],
            [['abs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abstracts::className(), 'targetAttribute' => ['abs_id' => 'abs_id']],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Participants::className(), 'targetAttribute' => ['pid' => 'pid']],
            [['paper_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf'], 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paper_id' => Yii::t('app', 'Paper ID'),
            'abs_id' => Yii::t('app', 'Abstract Title'),
            'pid' => Yii::t('app', 'Participant'),
            'paper_file' => Yii::t('app', 'Paper File'),
            'paper_raw' => Yii::t('app', 'Paper Raw'),
            'paper_ext' => Yii::t('app', 'Paper Ext'),
            'paper_date' => Yii::t('app', 'Paper Date'),
            'paper_info' => Yii::t('app', 'Paper Info'),
            'paper_status' => Yii::t('app', 'Paper Status'),
            'paper_editor_comment' => Yii::t('app', 'Paper Editor Comment'),
            'paper_final' => Yii::t('app', 'Paper Final'),
            'paper_reviewed' => Yii::t('app', 'Paper Reviewed'),
            'paper_recomendation' => Yii::t('app', 'Paper Recomendation'),
            'paper_review_comment' => Yii::t('app', 'Paper Review Comment'),
            'paper_review_date' => Yii::t('app', 'Paper Review Date'),
            'paper_review_file' => Yii::t('app', 'Paper Review File'),
            'paper_review_file_raw' => Yii::t('app', 'Paper Review File Raw'),
            'paper_review_file_ext' => Yii::t('app', 'Paper Review File Ext'),
            'paper_revised_file' => Yii::t('app', 'Paper Revised File'),
            'paper_revised_file_raw' => Yii::t('app', 'Paper Revised File Raw'),
            'paper_revised_file_ext' => Yii::t('app', 'Paper Revised File Ext'),
            'paper_final_file' => Yii::t('app', 'Paper Final File'),
            'paper_final_file_raw' => Yii::t('app', 'Paper Final File Raw'),
            'paper_final_file_ext' => Yii::t('app', 'Paper Final File Ext'),
        ];
    }

    public function getP()
    {
        return $this->hasOne(Participants::className(), ['pid' => 'pid']);
    }

    /**
     * Gets query for [[Certificates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates()
    {
        return $this->hasMany(Certificate::className(), ['pid' => 'pid']);
    }

    /**
     * Gets query for [[PaperReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaperReviews()
    {
        return $this->hasMany(PaperReview::className(), ['paper_id' => 'paper_id']);
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
     * Gets query for [[ReviewFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewFiles()
    {
        return $this->hasMany(ReviewFile::className(), ['paper_id' => 'paper_id']);
    }
}
