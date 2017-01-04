<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "company_contact".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $type
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Company $company
 * @property string $nValue
 */
class CompanyContact extends \yii\db\ActiveRecord
{
    public static $types = [
        'Phone', 'Email', 'Website',
        'WhatsApp', 'Facebook', 'Twitter', 'Instagram', 'Google+'
    ];
    protected $formatMap = [
        'email' => 'email',
        'website' => ['url', ['target' => '_blank']],
        'facebook' => ['url', ['target' => '_blank']],
        'twitter' => ['url', ['target' => '_blank']],
        'instagram' => ['url', ['target' => '_blank']],
        'google+' => ['url', ['target' => '_blank']],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['company_id'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 100],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'type' => 'Type',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getNValue()
    {
        $type = strtolower($this->type);
        $format = isset($this->formatMap[$type]) ? $this->formatMap[$type] : 'text';
        return Yii::$app->formatter->format($this->value, $format);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return[
            \yii\behaviors\TimestampBehavior::class,
        ];
    }
}
