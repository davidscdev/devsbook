<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoMysql.php';



$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activePage = 'profile';


/* $postList = new PostDaoMysql($pdo);
$feed = $postList->getFeedHome($userInfo->id);
 */

 //Pega o id do usuário do perfil
$id = filter_input(INPUT_GET, 'id');

if (!$id) {
    $id = $userInfo->id;
}


$itsMe = true;

if ($userInfo->id != $id) {
    $itsMe = false;
    $activePage = '';
}

$postData = new PostDaoMysql($pdo);
$userData = new UserDaoMysql($pdo);

// 1 - Pegar as infos do usuário 
$user = $userData->findById($id, true);
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

        <div class="column side pr-5">
            
            <div class="box">
                <div class="box-body">
                    
                    <div class="user-info-mini">
                        <img src="<?=$base;?>/assets/images/calendar.png" />
                        <?=date('d/m/Y', strtotime($user->birthdate));?> (<?=$user->ages;?> anos)
                    </div>

                    <div class="user-info-mini">
                        <img src="<?=$base?>/assets/images/pin.png" />
                        <?php if (!empty($user->city)): ?>
                            <?=$user->city;?>
                        <?php endif;?>
                        
                    </div>

                    <div class="user-info-mini">
                        <img src="<?=$base?>/assets/images/work.png" />
                        <?php if (!empty($user->work)): ?>
                            <?=$user->work;?>
                        <?php endif;?>
                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Seguindo
                        <span>(<?=count($user->following);?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?=$base;?>/friends.php?id=<?=$user->id;?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body friend-list">
               
                    <?php if (count($user->following)>0):?>
                        <?php foreach ($user->following as $friend):?>
                            <div class="friend-icon">
                            <a href="<?=$base;?>/profile.php?id=<?=$friend->id;?>">
                                <div class="friend-icon-avatar">
                                    <img src="<?=$base;?>/media/avatars/<?=$friend->avatar;?>" />
                                </div>
                                <div class="friend-icon-name">
                                    <?=$friend->name;?>
                                </div>
                            </a>
                        </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>

        </div>
        <div class="column pl-5">

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Fotos
                        <span>(<?=count($user->photos);?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?=$base;?>/photos.php?id=<?=$friend->id;?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body row m-20">

                    <?php if (count($user->photos)>0):?>
                            <?php foreach ($user->photos as $photo):?>
                                <div class="user-photo-item">
                                    <a href="#modal-2" rel="modal:open">
                                        <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                    </a>
                                    <div id="modal-2" style="display:none">
                                        <img src="<?=$base;?>/media/uploads/<?=$photo->body;?>" />
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                </div>
            </div>


            <?php if($itsMe):?>
                <?php require 'partials/feed-inserts.php';?>
            <?php endif;?>
            

            <?php if(count($feed)>0):?>
                <?php foreach($feed as $item):?>
                    <?php require 'partials/feed-item.php';?>
                <?php endforeach;?>
                    
            <?php else:?>
                Esse usuário não possui posts a serem exibidos.
            <?php endif;?>
        </div>
   </div>

    </section>
<?php
require 'partials/footer.php';
?>