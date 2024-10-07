<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body id="main">
<header>
    <h1>ログイン画面</h1>
</header>
<div>
    <form action="LoginProcess.php" method="post">
        <div class="form-group">
            <label for="email">メールアドレス：</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="PW">パスワード：</label>
            <input type="password" id="PW" name="PW">
        </div>
        <div class="form-group">
            <input type="submit" name="login" value="ログイン" />
        </div>
    </form>
</div>
<form action="SignUp.php" method="post">
<div class="form-group">
<input type="submit" name="signup" value="サインアップ" />
</div>
</form>
</body>
</html>
