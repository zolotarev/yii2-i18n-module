<?php
/**
 * @var View $this
 * @var SourceMessage $model
 */

use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use Zelenin\yii\modules\I18n\models\SourceMessage;
use Zelenin\yii\modules\I18n\Module;
use yii\widgets\Pjax;

$this->title = Module::t('Update') . ': ' . $model->message;

$this->params['breadcrumbs'][] = ['label' => Module::t('Translations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title]
?>

<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'message')
    ->textarea(['maxlength' => true, 'readonly' => 'readonly', 'rows' => 4])
    ->label(Module::t('Source message')) ?>

<?php foreach ($model->messages as $language => $message) : ?>
    <?= $form->field($model->messages[$language], '[' . $language . ']translation')
        ->textarea(['maxlength' => true, 'rows' => 4])
        ->label($language) ?>
<?php endforeach; ?>

<?= Html::submitButton(Module::t('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<?php $form::end(); ?>

<?php Pjax::end(); ?>