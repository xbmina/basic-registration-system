<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Muhamad Nauval Azhar">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
	<title>Bootstrap 5 Login Page</title>
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
							<h1 class="fs-4 card-title fw-bold mb-4">ユーザー登録</h1>
							<form action= "register.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
								<div class="mb-3">
									<label class="mb-2 text-muted" for="name">ユーザー名</label>
									<input id="name" type="text" class="form-control" name="name" value="" required autofocus>
									<div class="invalid-feedback">
										ユーザー名を入力してください。
									</div>
								</div>

								<div class="mb-3">
									<label class="mb-2 text-muted" for="email">メールアドレス</label>
									<input id="email" type="email" class="form-control" name="email" value="" required>
									<div class="invalid-feedback">
                                        メールアドレスは必須項目です。
									</div>
								</div>

								<div class="mb-3">
									<label class="mb-2 text-muted" for="password">パスワード</label>
									<input id="password" type="password" class="form-control" name="password" required>
								    <div class="invalid-feedback">
                                        パスワードは必須項目です。
							    	</div>
								</div>

								<p class="form-text text-muted mb-3">
									登録することで利用規約に同意したとみなします。.
								</p>

								<div class="align-items-center d-flex">
									<button type="submit" name="submit" class="btn btn-primary ms-auto">
										登録
									</button>
								</div>
							</form>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								すでにアカウントをお持ちの場合は <a href="index.php" class="text-dark">こちら</a>
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

	<script src="js/login.js"></script>
</body>
</html>

<?php
include('sql.php');

function isEmail($email)
{
    if (!empty($email))
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    return false;
}
function isAlphabet($str): bool
{
    if (isset($str))
        return ctype_alnum($str);
    return false;
}
function isSecure($str)
{
    $pattern = "#.*^(?=.{8,64})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#";
    if (!empty($str))
        return preg_match($pattern, $str);
    return false;
}


if (($_SERVER['REQUEST_METHOD']) === 'POST' ) { //アクセス方法がPOSTになっているかチェック
    if (isset($_POST["submit"])) { //ボタンが押されているかチェック

        //register.phpで入力された値を変数で代入
        $email    = $con->real_escape_string($_POST["email"]);
        $username = $con->real_escape_string($_POST["name"]);
        $password = $con->real_escape_string($_POST["password"]);

        if (!isEmail($email))
            die("入力されたメールアドレスがメールアドレスの形式ではありません。");
        if (!isAlphabet($username))
            die("ユーザー名はアルファベットで入力してください。");
        if (!isSecure($password))
            die("パスワードの形式が間違っています。\nパスワードは大文字、小文字、数字を含まなければなりません。");
        //ユーザー名がすでに存在しないかデータベースを確認するべき、メールアドレスが既に使われていないか確認するべき。
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        //登録
        $con->query("INSERT INTO users 
        (email, username, password,usertype) 
        VALUES ('$email', '$username','$hashed_password', '0')");

        die("登録完了しました。");

    }
}

