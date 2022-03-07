<?php
    require 'config.php';


    /*
    *Limpa o token da sessão, quando for comparar se o token do banco é igual ao da sessão
    *será direcionado para a tela de login.
    */
    $_SESSION['token'] = '';
    header('Location: '.$base);
    exit;
?>