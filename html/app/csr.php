<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dn = array(
        "countryName" => $_POST["countryName"],
        "stateOrProvinceName" => $_POST["stateOrProvinceName"],
        "localityName" => $_POST["localityName"],
        "organizationName" => $_POST["organizationName"],
        "organizationalUnitName" => $_POST["organizationalUnitName"],
        "commonName" => $_POST["commonName"],
        "emailAddress" => $_POST["emailAddress"]
    );

		// 空のエントリを削除
    $dn = array_filter($dn, function($value) {
        return !empty($value);
    });

    // メールアドレスが設定されている場合のみバリデーションチェック
    $errors = array();
    if (isset($dn['emailAddress']) && !filter_var($dn['emailAddress'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "メールアドレスが無効です。";
    }

    // 国名のバリデーションチェック
    if (isset($dn['countryName']) && !preg_match('/^[A-Z]{2}$/', $dn['countryName'])) {
        $errors[] = "国名は2文字のISOコードである必要があります。";
    }

    // エラーがある場合は、エラーメッセージを出力してスクリプトを終了する
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div style="color:red;">' . $error . '</div>';
        }
        exit;
    }

    // 鍵作成
    $privkey = openssl_pkey_new(array(
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ));

    // CSR を生成します
    $csr = openssl_csr_new($dn, $privkey);

    // 鍵を PEM 形式で出力
    openssl_pkey_export($privkey, $private_key);

    // CSRから"-----BEGIN CERTIFICATE REQUEST-----"から"-----END CERTIFICATE REQUEST-----"までの情報だけを取得する
    $csr_output = "";
    openssl_csr_export($csr, $csr_output, true);
    $csr_output = strstr($csr_output, "-----BEGIN CERTIFICATE REQUEST-----");
    $csr_output = strstr($csr_output, "-----END CERTIFICATE REQUEST-----", true) . "-----END CERTIFICATE REQUEST-----\n";

    // 秘密鍵から"-----BEGIN PRIVATE KEY-----"から"-----END PRIVATE KEY-----"までの情報だけを取得する
    $private_key_output = strstr($private_key, "-----BEGIN PRIVATE KEY-----");
    $private_key_output = strstr($private_key_output, "-----END PRIVATE KEY-----", true) . "-----END PRIVATE KEY-----\n";

    // 画面に出力する
    print_r(nl2br($csr_output));
    echo "<br/>";
    print_r(nl2br($private_key_output));
?>

<br>
<form method="post" action="download_csr.php">
  <input type="hidden" name="csr_output" value="<?php echo htmlspecialchars($csr_output); ?>">
  <button type="submit" name="download_csr">CSRをダウンロードする</button>
</form>
<br>
<form method="post" action="download_key.php">
  <input type="hidden" name="private_key_output" value="<?php echo htmlspecialchars($private_key_output); ?>">
  <button type="submit" name="download_key">秘密鍵をダウンロードする</button>
</form>

<?php
}
?>
