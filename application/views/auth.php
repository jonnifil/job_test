
<div class="col-xs-12" id="auth_block">
    <div class="col-xs-6 form-horizontal">
        <div class="form-group">
            <label class="col-xs-2 control-label">
                Логин
            </label>
            <div class="col-xs-9">
                <input class="form-control" type="text" name="login" placeholder="Логин" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 control-label">
                Пароль
            </label>
            <div class="col-xs-9">
                <input class="form-control" type="text" name="password" placeholder="Пароль" required>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default pull-left" name="come_in">Войти</button>
            <button class="btn btn-primary pull-right" name="register">Зарегистрироваться</button>
        </div>

    </div>
    <div class="col-xs-6">

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var user_form = new UserForm();
    });
    var UserForm = function () {
        this.block = $('#auth_block');
        this.come_in.button = $('[name="come_in"]', this.block)
        this.register.button = $('[name="register"]', this.block);
        this.come_in.button.on('click', $.proxy(this.come_in, this));
        this.register.button.on('click', $.proxy(this.register, this));
    };

    UserForm.prototype = {
        constructor: UserForm,

        come_in: function () {
            var data = this.form_data();
            if(data === false)
                return;
            else{
                $.ajax({
                    type: 'post',
                    url: '/auth/come_in',
                    data: {
                        'come_in': data
                    },
                    dataType: 'json',
                    success: function (resp) {
                        if(resp.saved === true){
                            window.location = '/home/start';
                        }else{
                            alert('login failed')
                        }
                    },
                    error: function (error) {
                        alert(error.responseText);
                    }
                });
            }
        },

        register: function () {
            var data = this.form_data();
            if(data === false)
                return;
            else{
                $.ajax({
                    type: 'post',
                    url: '/auth/register',
                    data: {
                        'register': data
                    },
                    dataType: 'json',
                    success: function (resp) {
                        if(resp.saved === true){
                            window.location = '/home/start';
                        }else{
                            alert('register failed')
                        }
                    },
                    error: function (error) {
                        console.log(error.responseText);
                        alert(error.responseText);
                    }
                });
            }
        },
        
        form_data: function () {
            var data = {}, login, password;
            login = $('input[name="login"]', this.block).val();
            password = $('input[name="password"]', this.block).val();
            if(login.length == 0 || password.length == 0){
                alert('Поля логин и пароль должны быть заполнены!');
                return false;
            }else{
                data.login = login;
                data.password = password;
                return data;
            }
        }
    };
</script> 