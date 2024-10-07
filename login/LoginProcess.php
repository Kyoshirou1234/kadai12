<?php

include("../function/Loginfuncs.php");
include("../function/funcs.php");
include("../function/DBfuncs.php");
$pdo = ReadDB();

$login = "fail";
if (isset($_POST['login'])) {
    $mode = "login";
    $email = $_POST["email"];
    $PW = $_POST["PW"];
    $stmt_user = $pdo->prepare("SELECT * FROM name WHERE email = :email");
    $stmt_user->bindValue(':email', $email, PDO::PARAM_STR);
    $status_user = $stmt_user->execute();
    if ($status_user == false) {
        $error = $stmt_user->errorInfo();
        exit("SQLError:" . $error[2]);
    }
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if(empty($user)){
        $scr = h('正しいメールアドレスとパスワードを入力してください');
        Alert($scr);
    }
    else{
    if($user["username"] == "" || $user["password"] == ""){
        $scr = h('サインアップをしてください');
        Alert($scr);
    }
    }
    $PW_match = password_verify($PW, $user["password"]); //   //パスワードハッシュ化

    session_start();
    if($PW_match){  //true
        //Login成功時

        $_SESSION["chk_ssid"]  = session_id(); //SESSION_ID取得して代入
        $_SESSION["email"] = $user["email"];
        $_SESSION["user"] = $user["username"];
        $_SESSION["authority"] = $user["authority"];
        $alert = "<script type='text/javascript'>alert('ログインしました');</script>";
        echo $alert;
        echo '<script>location.href = "../ConfirmWork.php" ;</script>';
    }
    else{
        $scr = h('正しいメールアドレスとパスワードを入力してください');
        Alert($scr);
    }
    
}

if (isset($_POST["regist"])) {
    if ($_POST["username"] == "" || $_POST["email"] == "" || $_POST["PW"] == "" || $_POST["PWConfirm"] == "") {
        $scr = h('すべて入力してください');
        Alert($scr);
    } else {
        if ($_POST["PW"] != $_POST["PWConfirm"]) {
            $scr = h('パスワードが一致しません');
            Alert($scr);
        } else {
            $mode = "regist";
            $username = h($_POST["username"]);
            $email = h($_POST["email"]);
            $PW = password_hash($_POST["PW"], PASSWORD_DEFAULT);

            // Check for existing email
            $check_stmt = $pdo->prepare("SELECT * FROM name WHERE email = :email");
            $check_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_status = $check_stmt->execute();
            $user = $check_stmt->fetch(PDO::FETCH_ASSOC); // fetch single record

            if (!$user) {
                $scr = h('$email該当するメールアドレスがありません。管理者に登録依頼をしてください');
                Alert($scr);
            } else {
                if (!empty($user["password"])) {
                    $scr = $email."は登録済みです";
                    Alert($scr);
                } else {
                    $sql = "UPDATE name SET username=:username, password=:password WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $user['id'], PDO::PARAM_INT); // Correctly bind id
                    $stmt->bindValue(':password', $PW, PDO::PARAM_STR);
                    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
                    $Enterstatus = $stmt->execute();

                    if ($Enterstatus) {
                        $alert = "<script type='text/javascript'>alert('登録しました');</script>";
                        echo $alert;
                        echo '<script>location.href = "Login.php" ;</script>';
                    } else {
                        $error = $stmt->errorInfo();
                        exit("SQL Error: " . $error[2]);
                    }
                }
            }
        }
    }
}


if(isset($_POST["AdminRegist"])){
    if($_POST["email"] == ""){
        $scr = h('すべて入力してください');
        Alert($scr);
        exit();
    }
    else{
        $email = htmlspecialchars($_POST["email"]);
        $authority = isset($_POST['authority']) ? '管理者' : '閲覧者';

        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM name WHERE email = :email");
        $check_stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $check_status = $check_stmt->execute();
        $exists = $check_stmt->fetchColumn();

    if ($exists > 0) {
        $scr = h('入力したメールアドレスは既に登録されています');
        Alert($scr);
    }

    else {
        $sql_INSERT = "INSERT INTO name (id, username, email, password, authority) VALUES (NULL, :username ,:email, :password, :authority)";
        $stmt = $pdo->prepare($sql_INSERT);
        $stmt->bindValue(':username', "", PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', "", PDO::PARAM_STR);
        $stmt->bindValue(':authority', $authority, PDO::PARAM_STR);
        $Enterstatus = $stmt->execute();

    if ($Enterstatus) {
        $alert = "<script type='text/javascript'>alert('登録しました');</script>";
        echo $alert;
        echo '<script>location.href = "AdminSignUp.php" ;</script>';
    }
}
}
}
?>