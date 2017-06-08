<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=$brand->logo?\yii\bootstrap\Html::img($brand->logo,['heigth'=>20]):''?></td>
            <td><?=$brand->status==1?'正常':'隐藏'?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id,'class'=>'btn btn-xs btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id,'class'=>'btn btn-xs btn-danger'])?>
            </td>
        </tr>

    <?php endforeach;?>
</table>