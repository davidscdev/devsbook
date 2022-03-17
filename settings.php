<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';


$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activePage = 'settings';

$User = new UserDaoMysql($pdo);

require 'partials/header.php';
require 'partials/menu.php';
?>
    <section class="feed mt-10">
        <h1>Configurações do Usuário</h1>
        
            <form action="settings-action.php" method="post" class="config-form" enctype="multipart/form-data">
                <?php if (!empty($_SESSION['msg'])):?>

                    <span class="msg_login"><?=$_SESSION['msg'];?></span>

                <?php $_SESSION['msg'] = '';?>
                <?php endif;?>
                <label>
                    Foto do Perfil:
                    <input type="file" name="avatar" id="">
                    </br>
                    <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar?>" class="mini" />
                </label>
                <label>
                    Capa:
                    <input type="file" name="cover" id="">
                    </br>
                    <img src="<?=$base;?>/media/covers/<?=$userInfo->cover?>" class="cover-mini" />
                </label>
                
                <hr/>

                <label>
                    Nome:
                    </br>
                    <input type="text" name="name" id="" value="<?=$userInfo->name;?>">
                </label>
                <label>
                    E-mail:
                    </br>
                    <input type="email" name="email" id="" value="<?=$userInfo->email;?>">
                </label>
                <label>
                    Data de Nascimento:
                    </br>
                    <input type="text" name="birthdate" id="birthdate" value="<?=date('d/m/Y', strtotime($userInfo->birthdate));?>">
                </label>
                <label>
                    Cidade:
                    </br>
                    <input type="text" name="city" id="" value="<?=$userInfo->city;?>">
                </label>
                <label>
                    Trabalho:
                    </br>
                    <input type="text" name="work" id="" value="<?=$userInfo->work;?>">
                </label>

                <hr/>

                <label>
                    Nova Senha:
                    </br>
                    <input type="password" name="password" id="">
                </label>
                <label>
                    Confirmar Senha:
                    </br>
                    <input type="password" name="password_confirm" id="">
                </label>
                <button class="button">Salvar</button>
            </form>
            <!-- <pre>
                <?php print_r($userInfo);?>
            </pre> -->
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
<?php
require 'partials/footer.php';
?>