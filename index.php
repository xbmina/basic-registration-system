<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>ユーザー登録</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
<section class="h-100">
    <div class="container h-100">
        <div class="row justify-content-sm-center h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                <div class="text-center my-5">
                    <img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="logo" width="100">
                </div>
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h1 class="fs-4 card-title fw-bold mb-4">ログイン</h1>
                        <form action="index.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
                            <div class="mb-3">
                                <label class="mb-2 text-muted" for="username">ユーザー名</label>
                                <input id="username" type="text" class="form-control" name="username" value="" required autofocus>
                                <div class="invalid-feedback">
                                    ユーザー名は必須項目です。
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-2 w-100">
                                    <label class="text-muted" for="password">パスワード</label>
                                    <a href="forgot.php" class="float-end">
                                        パスワードを忘れた場合はこちら
                                    </a>
                                </div>
                                <input id="password" type="password" class="form-control" name="password" required>
                                <div class="invalid-feedback">
                                    パスワードは必須項目です。
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                    <label for="remember" class="form-check-label">ログイン維持</label>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary ms-auto">
                                    ログイン
                                </button>

                            </div>
                        </form>
                    </div>
                    <div class="card-footer py-3 border-0">
                        <div class="text-center">
                            アカウントをお持ちでない場合は <a href="register.php" class="text-dark">こちら</a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5 text-muted">
                    Copyright &copy; HahaHa
                </div>
            </div>
        </div>
    </div>
</section>


</body>
</html>

<?php
include('sql.php');

function isAlphabet($str): bool
{
    if (isset($str))
        return ctype_alnum($str);
    return false;
}

//ログイン情報保存用にセッション作成
session_start();
if (isset($_SESSION["username"])) {
    header("location: home.php");
}
else if (($_SERVER['REQUEST_METHOD']) === 'POST' ) { //アクセス方法がPOSTになっているかチェック
    if (isset($_POST["submit"])) { //ボタンが押されているかチェック


        //セッション固定攻撃対策のID再生成
        session_regenerate_id();

        //index.phpで入力された値を変数で代入　（SQL Injectionという脆弱性回避、攻撃手法を回避するためにrealescapestringを使用している
        //https://www.elp.co.jp/staffblog/?p=8896
        //たぶんこれで十分？ https://stackoverflow.com/questions/4171115/is-mysql-real-escape-string-enough-to-anti-sql-injection
        $username = $con->real_escape_string($_POST["username"]);
        $password = $con->real_escape_string($_POST["password"]);

        if (!isAlphabet($username))
            die("ユーザー名はアルファベットで入力してください。");


        $userQuery = $con->query("SELECT * FROM users WHERE username = '$username' ");
        //ユーザーが見つかった
        if ($userQuery->num_rows == 1) {
            //見つかったユーザーをfetch
            $userRow = $userQuery->fetch_assoc( );

            //dbに入っているハッシュされているパスワードを取得、変数へ代入
            $db_hashed_password = $userRow["password"];

            //入力されたパスワードとdbに入っているハッシュされたパスワードを認証関数にて確認。
            $result = password_verify($password, $db_hashed_password);
            if (!$result) //間違っているのはパスワードだが、ユーザー特定のスパムに利用されないために失敗理由は返さない。
                die("ユーザー名またはパスワードが間違っています。");

            echo "ログインできたよ | ユーザータイプ: " . $userRow["usertype"]; //ユーザータイプ確認
 	    $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
	    header("location: /home.php");

        } else {
            //
            die("ユーザー名またはパスワードが間違っています。");
        }
    }
}
