<?php

namespace Zelenin\yii\modules\I18n\models\query;

use Yii;
use yii\db\ActiveQuery;
use Zelenin\yii\modules\I18n\models\Message;

class SourceMessageQuery extends ActiveQuery
{
    public function notTranslated()
    {

        $langs = Yii::$app->components['i18n']['languages'];
        $langlist = implode("','", $langs);
        $langLen = sizeof($langs);

        $messageTableName = Message::tableName();
        $query = Message::findBySql("
          SELECT yii2_source_message.*
          FROM yii2_source_message, yii2_message
          WHERE yii2_message.language IN ('$langlist') AND (TRIM(yii2_message.translation)='' OR yii2_message.translation IS NULL) AND yii2_source_message.id=yii2_message.id")->all();
        $ids = [];
        foreach ($query as $row) {
            $ids [] = $row->id;
        }

        $this->andWhere("id IN ('". implode("','", $ids)."')");
        return $this;
    }

    public function translated()
    {
        $langs = Yii::$app->components['i18n']['languages'];
        $langlist = implode("','", $langs);
        $langLen = sizeof($langs);

        $messageTableName = Message::tableName();
        $query = Message::findBySql("
          SELECT yii2_source_message.*
          FROM yii2_source_message, yii2_message
          WHERE yii2_message.language IN ('$langlist') AND (TRIM(yii2_message.translation)='' OR yii2_message.translation IS NULL) AND yii2_source_message.id=yii2_message.id")->all();
        $ids = [];
        foreach ($query as $row) {
            $ids [] = $row->id;
        }

        $this->andWhere("id NOT IN ('". implode("','", $ids)."')");
        return $this;
    }
}
