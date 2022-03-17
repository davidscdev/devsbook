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
                <label>
                    Foto do Perfil:
                    <input type="file" name="avatar" id="">
                </label>
                <label>
                    Capa:
                    <input type="file" name="cover" id="">
                </label>
                
                <hr/>

                <label>
                    Nome:
                    </br>
                    <input type="text" name="name" id="">
                </label>
                <label>
                    E-mail:
                    </br>
                    <input type="email" name="email" id="">
                </label>
                <label>
                    Data de Nascimento:
                    </br>
                    <input type="date" name="birthdate" id="">
                </label>
                <label>
                    Cidade:
                    </br>
                    <input type="text" name="city" id="">
                </label>
                <label>
                    Trabalho:
                    </br>
                    <input type="text" name="work" id="">
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

<?php
require 'partials/footer.php';
?>