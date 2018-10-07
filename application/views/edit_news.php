<div class="col-xs-12" id="news_form">
    <div class="col-xs-11" style="padding-bottom: 10px">
        <button class="btn btn-default pull-right" name="save">Сохранить</button>
    </div>

    <div class="clearfix"></div>
    <div class="form-horizontal">
        <input type="hidden" name="id" value="<?=$news['id'] ?>">
        <input type="hidden" name="user_id" value="<?=$news['user_id'] ?>">
        <div class="form-group">
            <label class="col-xs-2 control-label">
                Заголовок
            </label>
            <div class="col-xs-9">
                <input class="form-control" type="text" name="title" value="<?=$news['title'] ?>" placeholder="Заголовок">
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 control-label">
                Краткое содержание
            </label>
            <div class="col-xs-9">
                <textarea class="form-control" name="annotate" placeholder="Краткое содержание"><?=$news['annotate'] ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 control-label">
                Текст новости
            </label>
            <div class="col-xs-9">
                <textarea class="form-control" rows="20" name="body" placeholder="Текст новости"><?=$news['body'] ?></textarea>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var dialog = new NewsDialog();
    });
    var NewsDialog = function () {
        this.block = $('#news_form');
        this.save.button = $('[name="save"]', this.block);
        this.save.button.on('click', $.proxy(this.save, this));
    };

    NewsDialog.prototype = {
        collect_data: function () {
            var data = {};
            data.id = $('input[name="id"]', this.block).val();
            data.user_id = $('input[name="user_id"]', this.block).val();
            data.title = $('input[name="title"]', this.block).val();
            data.annotate = $('textarea[name="annotate"]', this.block).val();
            data.body = $('textarea[name="body"]', this.block).val();
            if(data.title.length == 0 ||data.annotate.length == 0 ||data.body.length == 0){
                alert('Заполните все поля!');
                return false;
            }
            return data;
        },

        save: function () {
            var data = this.collect_data();
            if(data === false)
                return;
            else{
                $.ajax({
                    type: 'post',
                    url: '/news/save',
                    data: {
                        'news_data': data
                    },
                    dataType: 'json',
                    success: function (resp) {
                        window.location = '/home/start';

                    },
                    error: function (error) {
                        alert(error.responseText);
                    }
                });
            }
        }
    };

</script>