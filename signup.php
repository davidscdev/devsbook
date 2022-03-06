<?php
require 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Cadastre-se</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
</head>
<body>
    <header>
        <div class="container">
            <a href="<?=$base;?>"><img src="<?=$base;?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">
        <form method="POST" action="<?=$base;?>/signup_action.php">

        <?php if (!empty($_SESSION['msg'])):?>

            <span class="msg_login"><?=$_SESSION['msg'];?></span>
            
            <?php $_SESSION['msg'] = '';?>
        <?php endif;?>
            <input placeholder="Digite seu nome completo" class="input" type="text" name="name" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input placeholder="Digite sua data de nascimento" class="input" type="text" name="birthdate" id="birthdate" />

            <input class="button" type="submit" value="Cadastrar" />

            <a href="<?=$base;?>/login.php">Já tem conta? Entre agora</a>
        </form>
    </section>

    <script src="https://unpkg.com/imask"></script>

    <script>
        IMask(
            document.getElementById('birthdate'),
            {
                mask: '00/00/0000',
                min: new Date(1900, 0, 1),
                max: new Date(2020, 0, 1),
                lazy: true
            });
    </script>
</body>
</html>