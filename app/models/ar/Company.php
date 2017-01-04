<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $user_id
 * @property string $description
 * @property integer $photo_id
 * @property double $rating
 * @property string $photoUrl
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property CompanyContact[] $contacts
 */
class Company extends \yii\db\ActiveRecord
{

    use \mdm\behaviors\ar\RelationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['address', 'description'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['!user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['contacts'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'user_id' => 'User ID',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(CompanyContact::className(), ['company_id' => 'id']);
    }

    public function setContacts($values)
    {
        $this->loadRelated('contacts', $values);
    }

    public function getPhotoUrl()
    {
        if ($this->photo_id) {
            return \yii\helpers\Url::to(['/image', 'id' => $this->photo_id], true);
        }
    }

    public function synchronRating()
    {
        $this->rating = Comment::find()
            ->select(['r' => 'AVG([[rating_item]]+[[rating_service]])'])
            ->where(['type' => Comment::TYPE_REVIEW, 'object_type' => Comment::OBJECT_TYPE_PACKAGE,
                'object_id' => TravelPackage::find()->select(['id'])->where(['company_id' => $this->id])])
            ->scalar();
        $this->save(false);
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
