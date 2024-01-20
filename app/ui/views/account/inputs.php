<div class="form-group">
<label for="auth_user">ユーザー名</label>
<input type="text"
       class="form-control"
       name="user_name"
       value="<?php echo $this->escape($user_name); ?>" />
</div>

<div class="form-group">
<label for="auth_password">パスワード</label>
<input type="password"
       class="form-control"
       name="password"
       value="<?php echo $this->escape($password); ?>" />
</div>
