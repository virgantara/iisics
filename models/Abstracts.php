<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "abstracts".
 *
 * @property int $abs_id
 * @property int|null $pid
 * @property int $topic_id
 * @property string|null $abs_date
 * @property string|null $abs_date_edit
 * @property string $abs_title
 * @property string $abs_author
 * @property string $abs_institution
 * @property string $abs_abstract
 * @property string $abs_keyword
 * @property string $abs_type
 * @property string $abs_status None, Accepted, Rejected, Revised
 * @property int|null $abs_paid
 * @property string $presenter_name
 * @property string|null $examiner_by
 * @property int|null $rev_id
 * @property string|null $rev_name
 * @property int $viewed 0=no, 1=yes, 2=revised
 *
 * @property AbstractReview[] $abstractReviews
 * @property Topics $topic
 * @property Participants $p
 * @property Certificate[] $certificates
 * @property PaperReview[] $paperReviews
 * @property Papers[] $papers
 */
class Abstracts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'abstracts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'topic_id', 'abs_paid', 'rev_id', 'viewed'], 'integer'],
            [['topic_id', 'abs_title', 'abs_author', 'abs_institution', 'abs_abstract', 'abs_keyword', 'abs_type', 'presenter_name'], 'required'],
            [['abs_date', 'abs_date_edit'], 'safe'],
            [['abs_abstract'], 'string'],
            [['abs_title', 'abs_author', 'abs_institution', 'abs_keyword'], 'string', 'max' => 1000],
            [['abs_type', 'rev_name'], 'string', 'max' => 50],
            [['abs_status'], 'string', 'max' => 25],
            [['presenter_name'], 'string', 'max' => 255],
            [['examiner_by'], 'string', 'max' => 100],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topics::className(), 'targetAttribute' => ['topic_id' => 'topic_id']],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Participants::className(), 'targetAttribute' => ['pid' => 'pid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'abs_id' => 'ID',
            'pid' => 'Pid',
            'topic_id' => 'Topic',
            'abs_date' => 'Date',
            'abs_date_edit' => 'Date Edit',
            'abs_title' => 'Title',
            'abs_author' => 'Author',
            'abs_institution' => 'Affiliation',
            'abs_abstract' => 'Abstract',
            'abs_keyword' => 'Keyword',
            'abs_type' => 'Type',
            'abs_status' => 'Acceptance Status',
            'abs_paid' => 'Payment Status',
            'presenter_name' => 'Presenter Name',
            'examiner_by' => 'Examiner By',
            'rev_id' => 'Rev ID',
            'rev_name' => 'Rev Name',
            'viewed' => 'Viewed',
        ];
    }

    /**
     * Gets query for [[AbstractReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbstractReviews()
    {
        return $this->hasMany(AbstractReview::className(), ['abs_id' => 'abs_id']);
    }

    /**
     * Gets query for [[Topic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topics::className(), ['topic_id' => 'topic_id']);
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
     * Gets query for [[Certificates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificates()
    {
        return $this->hasMany(Certificate::className(), ['abs_id' => 'abs_id']);
    }

    /**
     * Gets query for [[PaperReviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaperReviews()
    {
        return $this->hasMany(PaperReview::className(), ['abs_id' => 'abs_id']);
    }

    /**
     * Gets query for [[Papers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPapers()
    {
        return $this->hasMany(Papers::className(), ['abs_id' => 'abs_id']);
    }
}
