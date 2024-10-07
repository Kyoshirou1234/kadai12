<?PHP
include("Loginfuncs.php");
session_start();
sschk();



echo 
'<h3>'.$_SESSION["user"].'さん';

echo '
<div class="tabs">
    <div class="tab">
        <form action="ConfirmWork.php" method="post">
        <input type="submit" name="ini" value="業務を確認" />
        </form>
    </div>
    <div class="tab">
        <form action="ConfirmWork.php" method="post">
        <input type="submit" name="all" value="すべてのタスクを表示" />
        </form>
    </div>';

    if ($_SESSION["authority"] == "管理者") {
        echo '
        <div class="tab">
        <form action="AddWork.php" method="post">
        <input type="submit" value="業務を追加" />
        </form>
    </div>';
            }
    
    echo 
    '<div class="tab">
        <form action="AddTask.php" method="post">
        <input type="submit" value="タスクを追加" />
        </form>
    </div>
    <div class="tab">
        <form action="UpdateWork.php" method="post">
        <input type="submit" name="UpdateWorks" value="業務内容の変更" />
        </form>
    </div>
    <div class="tab">
        <form action="UpdateTasks.php" method="post">
        <input type="submit" name="UpdateTasks" value="タスクの変更" />
        </form>
    </div>
';

if ($_SESSION["authority"] == "管理者") {
echo '
    <div class="tab">
        <form action="UpdateUsers.php" method="post">
        <input type="submit" name="UpdateUser" value="ユーザー管理" />
    </form>
    </div>';
    }

echo '
    <div class="tab">
        <form action="login/Logout.php" method="post">
        <input type="submit" name="Logout" value="ログアウト" />
        </form>
    </div>
</div>
';

?>