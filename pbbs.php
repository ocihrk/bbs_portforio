<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>OCHIAI掲示板</title>
	<!-- bootstrap CDN -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<style type=text/css>
		div#main {
			padding: 50px;
			background-color: #FFCC66;
		}
	</style>
</head>

<body>
	<div class="container">
		<div id="main">
			<?php
			// データベースに接続する
			$pdo = new PDO("mysql:host=127.0.0.1;dbname=lesson;charset=utf8", "root", "");
			// print_r($_POST);

			// 受け取ったidのレコードの削除
			if (isset($_POST["delete_id"])) {
				$delete_id = $_POST["delete_id"];
				$sql  = "DELETE FROM pbbs WHERE id = :delete_id;";
				$stmt = $pdo->prepare($sql);
				$stmt -> bindValue(":delete_id", $delete_id, PDO::PARAM_INT);
				$stmt -> execute();
			}

			// 受け取ったデータを書き込む
			if (isset($_POST["content"]) && isset($_POST["user_name"])) {
				$content   = $_POST["content"];
				$user_name = $_POST["user_name"];
				$sql  = "INSERT INTO pbbs (content, user_name, updated_at) VALUES (:content, :user_name, NOW());";
				$stmt = $pdo->prepare($sql);
				$stmt -> bindValue(":content", $content, PDO::PARAM_STR);
				$stmt -> bindValue(":user_name", $user_name, PDO::PARAM_STR);
				$stmt -> execute();
			} ?>

			<h1>OCHIAI 掲示板</h1>

			<h2>投稿フォーム</h2>
			<form class="form" action="pbbs.php" method="post">
				<div class="form-group">
					<label class="control-label">投稿内容</label>
					<input -sm class="form-control" type="text" name="content">
				</div>
				<div class="form-group">
					<label class="control-label">投稿者</label>
					<input -sm class="form-control" type="text" name="user_name">
				</div>
				<button class="btn btn-success" type="submit">送信</button>
			</form>

			<h2>発言リスト</h2>
			<?php
			// データベースからデータを取得する
			$sql = "SELECT * FROM pbbs ORDER BY updated_at;";
			$stmt = $pdo->prepare($sql);
			$stmt -> execute();
			?>
			<table class="table table-striped">
				<tr>
					<th>id</th>
					<th>日時</th>
					<th>投稿内容</th>
					<th>投稿者</th>
					<th></th>
				</tr>
				<?php
				// 取得したデータを表示する
				while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) { ?>
					<tr>
						<td><?= $row["id"] ?></td>
						<td><?= $row["updated_at"] ?></td>
						<td><?= $row["content"] ?></td>
						<td><?= $row["user_name"] ?></td>
						<td>
							<form action="pbbs.php" method="post">
								<input type="hidden" name="delete_id" value=<?= $row["id"] ?>>
								<button class="btn btn-danger" type="submit">削除</button>
							</form>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</body>

</html>