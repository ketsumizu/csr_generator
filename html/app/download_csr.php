<?php
if(isset($_POST["csr_output"])) {
    $csr_output = $_POST["csr_output"];

    // ダウンロード用のヘッダーを設定
    header('Content-Disposition: attachment; filename="csr.csr"');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strlen($csr_output));

    // ファイルを出力
    echo $csr_output;
}
?>
