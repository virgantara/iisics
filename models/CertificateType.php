<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "certificate_type".
 *
 * @property int $id
 * @property string $certificate_type_name
 * @property string $certificate_prefix_number
 * @property string|null $certificate_template
 * @property string|null $certificate_font_style
 * @property int|null $certificate_font_size
 * @property int|null $certificate_text_top_position
 */
class CertificateType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'certificate_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['certificate_type_name', 'certificate_prefix_number'], 'required'],
            [['certificate_font_style'], 'required','on'=>'insert'],
            [['certificate_font_size', 'certificate_text_top_position'], 'integer'],
            [['certificate_type_name', 'certificate_prefix_number'], 'string', 'max' => 255],
            // [['certificate_template', 'certificate_font_style'], 'string', 'max' => 500],
            [['certificate_template'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','jpeg','png'], 'maxSize' => 1024 * 1024 * 2],
            [['certificate_font_style'], 'file', 'skipOnEmpty' => true, 'extensions' => ['ttf'], 'maxSize' => 1024 * 1024 * 0.5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Type',
            'certificate_type_name' => 'Certificate Type Name',
            'certificate_prefix_number' => 'Certificate Prefix Number',
            'certificate_template' => 'Certificate Template',
            'certificate_font_style' => 'Certificate Font Style',
            'certificate_font_size' => 'Certificate Font Size',
            'certificate_text_top_position' => 'Certificate Text Top Position',
        ];
    }
}
