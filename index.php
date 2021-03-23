<?php
require 'vendor/autoload.php';
session_start();
// to enable the use of the .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oauth2 Discord</title>
</head>
<body>
    <header>
        <h1>Oauth Discord</h1>
    </header>
    <main>
        <?php if(!isset($_SESSION['username'])) : ?>
            <p>Let's try to set up an Oauth2.0 authentication via Discord</p>
            <a href="https://discord.com/api/oauth2/authorize?client_id=<?= $_ENV['CLIENT_ID'] ?>&redirect_uri=http%3A%2F%2Flocalhost%2FOauth2-Discord-Curl%2Fconnect.php&response_type=code&scope=identify%20email">Connect via Discord</a>
        <?php elseif(isset($_SESSION['username'])) : ?>
            <p>You made it <?= $_SESSION['username'] ?>!</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>L-T-XLS</p>
    </footer>
</body>
</html>