<?PHP
if(isset($_POST["AdminSignUp"])){$mode = "Admin";}


?>

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
<h1>サインアップ画面</h1>
</header>
    <div>
    <form action="LoginProcess.php" method="post">
    <div class="form-group">
            <label for="username">ユーザーネーム：</label>
            <input type="text" id="username" name="username">
    </div>
    <div class="form-group">
            <label for="email">メールアドレス：</label>
            <input type="email" id="email" name="email">
    </div>
    <div class="form-group">
            <label for="PW">パスワード：</label>
            <input type="password" id="PW" name="PW">
    </div>
    <div class="form-group">
        <label for="PWConfirm">パスワード（確認用）：</label>
        <input type="password" id="PWConfirm" name="PWConfirm">
    </div>
        <input type="submit" name="regist" value="登録" />
    </form>
    </div>
    <script>
<button onclick="location.href='Login.php'">ログイン画面に戻る</button>

</script>
</body>
</html>