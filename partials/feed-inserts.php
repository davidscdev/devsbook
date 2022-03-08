<?php
    $firtsName = current(explode(' ', $userInfo->name));
?>

<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$firtsName;?>?</div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="feed-new-send">
                <img src="<?=$base;?>/assets/images/send.png" />
            </div>
            <form class="feed-new-form" action="<?=$base;?>/feed-inserts-action.php" method="post">
                <input type="hidden" name="body">
            </form>
        </div>
    </div>
</div>

<!--JS pra pegar o conteúdo digitado na div de body, colocar num campo e enviar o formulário pra tratamento dos dados-->
<script>
    let feedInput = document.querySelector('.feed-new-input');
    let feedSubmit = document.querySelector('.feed-new-send');
    let feedForm = document.querySelector('.feed-new-form');

    /*No click da img enviar, pega o conteúdo da div feedinput coloca no form e envia o formulário. */
    feedSubmit.addEventListener('click', function(){
        let value = feedInput.innerText.trim();

        feedForm.querySelector('input[name=body]').value = value;
        feedForm.submit();
    });
</script>