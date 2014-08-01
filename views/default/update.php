<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var bariew\userModule\models\User $model
 */

$this->title = 'My Profile: ';
?>
<div class="user-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
