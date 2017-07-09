<?

function _hc($val)
{
    return htmlspecialchars($val, ENT_QUOTES);
}

$form = [];

if (isset($_POST['url'])) {
    $fName = "/etc/apache2/sites-available/$_POST[url].conf";
    $fContent = <<<CONF
<VirtualHost *:80>
        ServerAdmin TwilightTower@mail.ru
        DocumentRoot /mnt/hgfs/$_POST[path]

        ServerName $_POST[url]
        ServerAlias www.$_POST[url]

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
CONF;
    $result = file_put_contents($fName, $fContent);

    $pipe = popen("sudo a2ensite $_POST[url]", 'r');
    fwrite($pipe, "temp123\r\n");
    pclose($pipe);

    $pipe = popen("sudo apache2ctl restart", 'r');
    fwrite($pipe, "temp123\r\n");
    pclose($pipe);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Генератор web фолдера</title>
</head>
<body>

<form method="post">
    <label for="url">Local URL (no www.):</label>
    <input type="text" id="url" name="url" value="<?= _hc($form['url']) ?>">
    <br>
    <label for="path">Path to web folder:</label>
    <input type="text" id="path" name="path" value="<?= _hc($form['path']) ?>">
    <br>
    <button type="submit">SUBMIT</button>
</form>

</body>
</html>