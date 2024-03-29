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
            <div class="feed-new-photo">
                <img src="<?=$base;?>/assets/images/photo.png" />
                <input type="file" name="photo" class="feed-new-file" accept="image/png, image/jpeg">
            </div>            
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
    let feedPhoto = document.querySelector('.feed-new-photo');
    let feedFile = document.querySelector('.feed-new-file'); //Seleciona o input de arquivo que está escondido e o atrela ao click da img photo (selecionado acima).

    /* Passa o evento de click da img photo para o input "excondido" em tela. */
    feedPhoto.addEventListener('click', function(){
        feedFile.click();
    });

    /*Cria o evento para envio da imagem no evento change do input file */
    feedFile.addEventListener('change', async function(){
        let photo = feedFile.files[0];
        let formData = new FormData();

        formData.append('photo', photo);

        //Faz um envio do arquivo atráves de AJAX.
        let req = await fetch('ajax_upload.php', {
            method: 'POST',
            body: formData
        });
        let json = await req.json();

        if(json.error != '') {
            alert(json.error);
        }

        window.location.href = window.location.href;
    });

    /*No click da img enviar, pega o conteúdo da div feedinput coloca no form e envia o formulário. */
    feedSubmit.addEventListener('click', function(){
        let value = feedInput.innerText.trim();

        feedForm.querySelector('input[name=body]').value = value;
        feedForm.submit();
    });
</script>