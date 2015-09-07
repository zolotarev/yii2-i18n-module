<?php

namespace Zelenin\yii\modules\I18n\models\search;

use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\ArrayHelper;
use Zelenin\yii\modules\I18n\models\SourceMessage;
use Zelenin\yii\modules\I18n\models\Message;
use Zelenin\yii\modules\I18n\Module;
//use Zelenin\yii\SemanticUI\collections\Message;


class SourceMessageSearch extends SourceMessage
{
    const STATUS_TRANSLATED = 1;
    const STATUS_NOT_TRANSLATED = 2;

    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['category', 'safe'],
            ['message', 'safe'],
            ['status', 'safe']
        ];
    }

    /**
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->status == static::STATUS_TRANSLATED) {
            $query->translated();
        }
        if ($this->status == static::STATUS_NOT_TRANSLATED) {
            $query->notTranslated();
        }

        $query
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);
        return $dataProvider;
    }

    public static function getStatus($id = null)
    {
        $statuses = [
            self::STATUS_TRANSLATED => Module::t('Translated'),
            self::STATUS_NOT_TRANSLATED => Module::t('Not translated'),
        ];
        if ($id !== null) {
            return ArrayHelper::getValue($statuses, $id, null);
        }
        return $statuses;
    }

    public static function getStatus2($id)
    {
        $langs = Yii::$app->components['i18n']['languages'];
        $langlist = implode("','", $langs);
        $langLen= sizeof($langs);

        $messageTableName = Message::tableName();
        $query = Message::findBySql("SELECT * FROM yii2_message WHERE language IN ('$langlist') AND id=$id AND (TRIM(translation)<>'' AND translation IS NOT NULL)")->count('*');

        return $query."/".$langLen;
    }
}
