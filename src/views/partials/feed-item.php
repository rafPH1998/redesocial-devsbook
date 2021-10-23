<div class="box feed-item" data-id="<?=$feeds->id;?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?=$base;?>/perfil/<?=$feeds->user->id;?>"><img src="<?=$base;?>/media/avatars/<?=$feeds->user->avatar;?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?=$base;?>/perfil/<?=$feeds->user->id;?>"><span class="fidi-name"><?=$feeds->user->name;?></span></a>
                <span class="fidi-action"><?php
                    switch($feeds->type) {
                        case 'text':
                            echo 'fez um post';
                        break;
                        case 'photo':
                            echo 'postou uma foto';
                        break;
                    }              
                    ?></span><br/>
                    <span class="fidi-date"><?=date('d/m/Y', strtotime($feeds->created_at))?></span>
            </div>
            <?php if($feeds->mine):?>
                <div class="feed-item-head-btn">
                    <img src="<?=$base;?>/assets/images/more.png" />
                    <div class="feed-item-more-window">
                        <a href="<?=$base;?>/post/<?=$feeds->id;?>/delete" onclick="return confirm('Deseja mesmo excluir esse post?')">Excluir Post</a>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
            <?php
            switch($feeds->type) {
                case 'text':
                    echo nl2br($feeds->body);
                break;
                case 'photo':
                    echo '<img src="'.$base.'/media/uploads/'.$feeds->body.'" >'; 
                break;  
            }
            ?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?=($feeds->liked ?'on':'');?>"><?=$feeds->likeCount;?></div>
            <div class="msg-btn"><?=count($feeds->comments);?></div>
        </div>
        <div class="feed-item-comments">
            
            <div class="feed-item-comments-area">
                <?php foreach($feeds->comments as $comment):?>
                    <div class="fic-item row m-height-10 m-width-20">
                        <div class="fic-item-photo">
                            <a href="<?=$base;?>/perfil/<?=$comment['user']['id'];?>"><img src="<?=$base;?>/media/avatars/<?=$comment['user']['avatar'];?>" /></a>
                        </div>
                        <div class="fic-item-info">
                            <a href="<?=$base;?>/perfil/<?=$comment['user']['id'];?>"><?=$comment['user']['name'];?></a>
                            <?=$comment['body'];?>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>

            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?=$base;?>/<?=$loggedUser->id;?>"><img src="<?=$base;?>/media/avatars/<?=$loggedUser->avatar;?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
            </div>

        </div>
    </div>
</div>