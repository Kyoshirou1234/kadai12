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
            <label for="email">メールアドレス：</label>
            <input type="email" id="email" name="email">
    </div>
    <div class="form-group">
    <label for="authority">管理者権限：</label>
    <input type="checkbox" name="authority" value="管理者">
    </div>
    <input type="submit" name="AdminRegist" value="登録" />
    </form>
    </div>
    <button onclick="location.href='../Updateusers.php'">ユーザー管理に戻る</button>
</body>
</html>