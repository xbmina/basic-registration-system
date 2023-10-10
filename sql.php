<?php
//host, user, password, database
$con = new mysqli("localhost","root","","test");


if ($con->connect_errno) {
    die("データベースの接続に失敗しました。");
}

