<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'auth_key')->textInput();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'day3/captcha',
    'template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-1">{image}</div></div>'
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();