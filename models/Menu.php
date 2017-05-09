<?php

namespace yii2x\ui\menu\models;

use Yii;
use yii2x\common\behaviors\JsonFieldBehavior;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $alias
 * @property string $config
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['alias'], 'string', 'max' =>45],
            [['alias'], 'unique'],
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
            'config' => 'Config',
            'alias' => 'Alias',
        ];
    }

    public function behaviors()
    {
        return [                              
            [
                'class' => JsonFieldBehavior::className(),
                'attributes' => ['config']
            ]
        ];
    }    
    
    /**
     * @inheritdoc
     * @return MenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
}
