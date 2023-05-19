<?php
if(isset($_POST["private_key_output"])) {
    $private_key_output = $_POST["private_key_output"];

    // ダウンロード用のヘッダーを設定
    header('Content-Disposition: attachment; filename="private.key"');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strlen($private_key_output));

    // ファイルを出力
    echo $private_key_output;
}
?>
