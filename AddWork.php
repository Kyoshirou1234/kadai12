<?php
include("function/DBfuncs.php");
$pdo = ReadDB();

// Handle form submission
if (isset($_POST['send'])) {
    $workname = htmlspecialchars($_POST["workname"], ENT_QUOTES, 'UTF-8');
    $overview = htmlspecialchars($_POST["overview"], ENT_QUOTES, 'UTF-8');
    $phase = htmlspecialchars($_POST["phase"], ENT_QUOTES, 'UTF-8');
    $assigned_users = isset($_POST['assigned_users']) ? $_POST['assigned_users'] : []; // Retrieve assigned users as an array
    foreach ($assigned_users as $user):
        $username .= $user . ",";
    endforeach;

    // Check for duplicate workname
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM work WHERE workname = :workname");
    $check_stmt->bindValue(':workname', $workname, PDO::PARAM_STR);
    $check_stmt->execute();
    $workname_exists = $check_stmt->fetchColumn();

    if ($workname_exists > 0) {
        $error_message = "エラー: 同じ業務名がすでに存在します。";
    } else {
        $sql_INSERT = "INSERT INTO work (id, workname, overview, phase, name) VALUES (NULL, :workname, :overview, :phase, :name)";
        $stmt_name = $pdo->prepare($sql_INSERT);
        $stmt_name->bindValue(':workname', $workname, PDO::PARAM_STR);
        $stmt_name->bindValue(':overview', $overview, PDO::PARAM_STR);
        $stmt_name->bindValue(':phase', $phase, PDO::PARAM_STR);
        $stmt_name->bindValue(':name', $username, PDO::PARAM_STR);
        $Enterstatus = $stmt_name->execute();

        if ($Enterstatus) {
            echo "<div>登録しました</div>";
            $lastId = $pdo->lastInsertId(); // Get the last inserted ID
            try {
                $tableName = '24_' . preg_replace('/[^a-zA-Z0-9_]/', '', $lastId);
                $sql_CREATE = "CREATE TABLE IF NOT EXISTS $tableName (
                    id INT(16) AUTO_INCREMENT PRIMARY KEY,
                    start DATE,
                    finish DATE,
                    task VARCHAR(255),
                    importance VARCHAR(4),
                    done VARCHAR(4)
                )";
                $pdo->exec($sql_CREATE);
                echo "Table $tableName created successfully";
            } catch (PDOException $e) {
                echo $sql_CREATE . "<br>" . $e->getMessage();
            }
        } else {
            echo "<div>登録に失敗しました</div>";
            exit(); // Exit on error
        }
    }
}

// ユーザー情報を取得
$stmt_users = $pdo->prepare("SELECT * FROM name");
$status_users = $stmt_users->execute();
if ($status_users == false) {
    $error = $stmt_users->errorInfo();
    exit("SQLError:" . $error[2]);
}
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body id="main">
<header>
<?PHP include("function/header.php"); ?>
</header>
<h1>業務を追加</h1>
    <div>
        <!-- Display error message if any -->
        <?php if (!empty($error_message)): ?>
        <div style="color:red;"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Form for entering data -->
        <form action="AddWork.php" method="post">
            <div class="form-group">
                <label for="workname">業務名：</label>
                <input type="text" id="workname" name="workname" value="" required />
            </div>

            <div class="form-group">
                <label for="phase">フェーズ：</label>
                <select id="phase" name="phase">
                </select>
            </div>

            <div class="form-group">
                <label for="overview">概要：</label>
                <textarea name="overview" id="overview" cols="50" rows="5"></textarea>
            </div>

            <div class="form-group">
    <label for="users">担当者：</label>
    <div id="users">
        <?php foreach ($users as $user): ?>
            <label>
                <input type="checkbox" name="assigned_users[]" value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
</div>

            <input type="submit" name="send" value="送信" />
        </form>

        <script>
        function CreateSelect(arr, name) {
            let sl = document.getElementById(name);
            sl.innerHTML = ''; // Remove existing options
            for (let item of arr) {
                let op = document.createElement('option');
                op.text = item.name || item; // Use item.name if it's an object
                op.value = item.name || item;
                sl.appendChild(op);
            }
        }
        let phase = ["提案中", "構築中", "運用中"];

        CreateSelect(phase, "phase");
        </script>
    </div>
</body>
</html>

