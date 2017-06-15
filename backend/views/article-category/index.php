<?php
/* @var $this yii\web\View */
?>

<table class="table">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=\yii\bootstrap\Html::a('编辑',['article-category/edit','id'=>$model->id],['class'=>'btn btn-success'])?></td>
        </tr>
    <?php endforeach;?>
</table>
