<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserDaoMysql.php';


$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activePage = 'search';
$users = [];

$userList = new UserDaoMysql($pdo);

$searchTerm = filter_input(INPUT_GET, 's');

if (empty($searchTerm)) {
    header('Location: ./'); 
    exit;
}

$users = $userList->findByName($searchTerm);

require 'partials/header.php';
require 'partials/menu.php';
?>
        <section class="feed mt-10">
            
            <div class="row">
                <div class="column pr-5">
                    <h3>Termo pesquisado: <?=$searchTerm;?></h3>
                    <div class="full-friend-list">
                        <?php foreach ($users as $friend):?>
                            <div class="friend-icon">
                                            <a href="<?=$base;?>/profile.php?id=<?=$friend->id;?>">
                                                <div class="friend-icon-avatar">
                                                    <img src="<?=$base?>/media/avatars/<?=$friend->avatar;?>" />
                                                </div>
                                                <div class="friend-icon-name">
                                                    <?=$friend->name;?>
                                                </div>
                                            </a>
                                        </div>
                        <?php endforeach; ?>
                    </div>
                  </div>

                <div class="column side pl-5">
                    <div class="box banners">
                        <div class="box-header">
                            <div class="box-header-text">Patrocinios</div>
                            <div class="box-header-buttons">
                                
                            </div>
                        </div>
                        <div class="box-body">
                            <a href=""><img src="https://alunos.b7web.com.br/media/courses/php-nivel-1.jpg" /></a>
                            <a href=""><img src="https://alunos.b7web.com.br/media/courses/laravel-nivel-1.jpg" /></a>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-body m-10">
                            Criado por 😎 David Cavalcanti
                        </div>
                    </div>
                </div>
            </div>

        </section>

<?php
require 'partials/footer.php';
?>