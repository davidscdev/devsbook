<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';



$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

// echo '<pre>';
// var_dump($userInfo);
// echo '</pre>';
// exit;

$activePage = 'photos';
$user = [];
$feed = [];

/* $postList = new PostDaoMysql($pdo);
$feed = $postList->getFeedHome($userInfo->id);
 */

 //Pega o id do usuário do perfil
$id = filter_input(INPUT_GET, 'id');

$itsMe = true;

if (!$id) {
    $id = $userInfo->id;
}

if ($userInfo->id != $id) {
    $itsMe = false;
    if($activePage == 'profile'){
        $activePage = '';
    }
}

$postData = new PostDaoMysql($pdo);
$userData = new UserDaoMysql($pdo);

// 1 - Pegar as infos do usuário 
$user = $userData->findById($id, true);

// echo '<pre>';
// var_dump($user);
// echo '</pre>';
// exit;

if(!$user){
    header('Location: '. $base);
}



//Pega a idade do usuário
$dateFrom = new Datetime($user->birthdate);
$dateTo = new Datetime('today');

$user->ages = $dateTo->diff($dateFrom)->y;

// 2 - Pegar o feed desse usuário 
$feed = $postData->getUserFeed($id);


// 3 - Verificar se o usuário logado segue esse perfil



require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed">

    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover" style="background-image: url('<?=$base;?>/media/covers/<?=$user->cover;?>');"></div>
                <div class="profile-info m-20 row">
                    <div class="profile-info-avatar">
                        <img src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" />
                    </div>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?=$user->name;?></div>
                        <?php if (!empty($user->city)): ?>
                            <div class="profile-info-location"><?=$user->city;?></div>
                        <?php endif;?>
                    </div>
                    <div class="profile-info-data row">
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->follower);?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->following);?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->photos);?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column">
                    
            <div class="box">
                <div class="box-body">

                    <div class="full-user-photos">
                        <?php foreach($user->photos as $key => $photo):?>
                            <div class="user-photo-item">
                                <a href="#modal-<?=$key;?>" rel="modal:open">
                                    <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                </a>
                                <div id="modal-<?=$key;?>" style="display:none">
                                    <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                </div>
                            </div>
                        <?php endforeach;?>
                        
                        <?php if(count($user->photos) === 0):?>
                            Esse usuário não tem fotos postadas!
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
   </div>

    </section>
<?php
require 'partials/footer.php';
?>