<?php
require 'config.php';
require 'models/Auth.php';
require 'dao/PostDaoMysql.php';


$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activePage = 'home';

$postList = new PostDaoMysql($pdo);

$feed = $postList->getFeedHome($userInfo->id);
echo '<pre>';
var_dump($feed);
echo '</pre>';
exit;

require 'partials/header.php';
require 'partials/menu.php';
?>
        <section class="feed mt-10">
            
            <div class="row">
                <div class="column pr-5">
                    <?php require 'partials/feed-inserts.php';?>
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
                            Criado com 😎 por David Cavalcanti
                        </div>
                    </div>
                </div>
            </div>

        </section>

<?php
require 'partials/footer.php';
?>