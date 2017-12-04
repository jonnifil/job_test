<h2 class="text-center">Выбери свою новость</h2>
<div class="col-xs-12">
    <?php if(is_array($news_list)): ?>
        <?php foreach ($news_list as $news): ?>
            <div class="col-xs-11 news_block pointer" style="padding-top: 10px; cursor: pointer" data-id="<?=$news['id'] ?>">
                <h4 class="text-center"><?=$news['title'] ?></h4>
                <p><?=$news['annotate'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h4 class="text-center">Ваша новость может быть первой!</h4>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.news_block').on('click', function () {
            show_news($(this).attr('data-id'));
        })
    });
    function show_news(id) {
        window.location = '/news/start/'+id;
    }
</script>
