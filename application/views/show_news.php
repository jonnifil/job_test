<div class="col-xs-12">
    <?php if($can_edit): ?>
        <button class="btn btn-default pull-right" onclick="edit(<?=$news['id'] ?>)" name="edit">Редактировать</button>
        <button class="btn btn-danger pull-right" onclick="do_delete(<?=$news['id'] ?>)" name="delete">Удалить</button>
    <?php endif; ?>
    <div class="clearfix"></div>
    <h3 class="text-center"><?=$news['title']; ?></h3>
    <p><?=$news['body'] ?></p>
</div>
<script type="text/javascript">
    function edit(id) {
        window.location = '/news/edit/'+id;
    }

    function do_delete(id) {
        if(confirm('Вы действительно желаете удалить новость?')){
            window.location = '/news/delete_news/'+id;
        }
    }
</script>