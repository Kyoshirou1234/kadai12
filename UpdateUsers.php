<?php
include("function/DBfuncs.php");
include("function/funcs.php");
$pdo = ReadDB();
session_start();

// POSTデータを受け取ってテーブルを更新
if (isset($_POST['UpdatedUsers'])) {
    foreach ($_POST['users'] as $user_data) {
        $authority = isset($user_data['authority']) && $user_data['authority'] === '管理者' ? '管理者' : '閲覧者';
        $id = $user_data['id'];

        // データベースの業務内容を更新する
        $sql = "UPDATE name SET authority=:authority WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':authority', $authority, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // 実行して更新が成功したかチェック
        if (!$stmt->execute()) {
            $error = $stmt->errorInfo();
            exit("SQLError:" . $error[2]);
        }
    }

    // 削除機能
    if (isset($_POST['delete_users'])) {
        foreach ($_POST['delete_users'] as $delete_id) {
            $sql_delete = "DELETE FROM name WHERE id = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->bindValue(':id', $delete_id, PDO::PARAM_INT);
            if (!$stmt_delete->execute()) {
                $error = $stmt_delete->errorInfo();
                exit("SQLError: " . $error[2]);
            }
        }
    }

    $alert = "<script type='text/javascript'>alert('更新されました');</script>";
    echo $alert;
}

// ユーザー情報を取得
$stmt_users = $pdo->prepare("SELECT * FROM name");
$status_users = $stmt_users->execute();
if ($status_users == false) {
    $error = $stmt_users->errorInfo();
    exit("SQLError:" . $error[2]);
}
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

$_SESSION
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ユーザー情報変更</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
<header>
<?php include("function/header.php"); ?>
</header>

<h1>管理者のみ：ユーザー情報変更</h1>

<!-- 更新用フォーム -->
<form method="post" action="">
    <table>
        <thead>
        <tr>
            <th>ユーザーネーム</th>
            <th>メールアドレス</th>
            <th>管理者権限</th>
            <th>削除</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user["username"]); ?></td>
                <td><?= h($user["email"]); ?></td>
                <td>
                    <input type="hidden" name="users[<?= h($user['id']); ?>][authority]" value="閲覧者">
                    <label>
                        <input type="checkbox" name="users[<?= h($user['id']); ?>][authority]" value="管理者" <?= $user["authority"] === "管理者" ? 'checked' : ''; ?>>
                    </label>
                </td>
                <td>
                    <input type="checkbox" name="delete_users[]" value="<?= h($user['id']); ?>">
                </td>
                <input type="hidden" name="users[<?= h($user['id']); ?>][id]" value="<?= h($user['id']); ?>">
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input type="submit" name="UpdatedUsers" value="更新">
</form>
<form action="login/AdminSignUp.php" method="post">
    <input type="submit" name="AdminSignUp" value="ユーザーを追加" />
    </form>
</body>
</html>
