<body>
<form action="home.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">

<button type="submit" name="logoutBtn" class="btn btn-primary ms-auto">
    ログアウト
</button>
</form>
</body>
<?php
include('sql.php');



function isAlphabet($str): bool
{
    if (isset($str))
        return ctype_alnum($str);
    return false;
}
session_start();

if (isset($_POST["logoutBtn"])) {
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    header("location: /index.php");
} else if (isset($_SESSION['username'])) {
    //ログイン情報保存用にセッション作成

    $username = $_SESSION["username"];
    $password = $_SESSION["password"];

    $userQuery = $con->query("SELECT * FROM users WHERE username = '$username' ");
    //ユーザーが見つかった
    if ($userQuery->num_rows == 1) {
        //見つかったユーザーをfetch
        $userRow = $userQuery->fetch_assoc();

        //dbに入っているハッシュされているパスワードを取得、変数へ代入
        $db_hashed_password = $userRow["password"];

        //入力されたパスワードとdbに入っているハッシュされたパスワードを認証関数にて確認。
        $result = password_verify($password, $db_hashed_password);
        if (!$result) {
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            header("location: /index.php");
        }

        die("ログインされています、ホーム画面です");


    } else {
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        header("location: /index.php");
    }
} else {
    session_destroy();
    header("location: /index.php");
}


