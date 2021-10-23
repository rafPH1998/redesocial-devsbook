<?=$render('header', ['loggedUser' => $loggedUser]);?>
    <section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'photos']);?>
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
                        <div class="profile-info-location"><?=$user->work;?></div>
                    </div>
                    <div class="profile-info-data row">

                        <?php if($loggedUser->id != $user->id):?>
                            <div class="profile-info-item m-width-20">
                                <a class="button" href="<?=$base;?>/perfil/<?=$user->id;?>/follow"><?=(!$isFollowing) ? 'Seguir' : 'Deixar de Seguir';?></a>
                            </div>
                        <?php endif;?>

                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->followers);?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->following);?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->photo);?></div>
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

                        <?php if(count($user->photo) === 0):?>
                            Este usuário não possui fotos.
                        <?php endif;?>

                        <?php foreach($user->photo as $photos):?>
                            <div class="user-photo-item">
                                <a href="#modal-<?=$photos->id;?>" rel="modal:open">
                                    <img src="<?=$base;?>/media/uploads/<?=$photos->body;?>" />
                                </a>
                                <div id="modal-<?=$photos->id;?>" style="display:none">
                                    <img src="<?=$base;?>/media/uploads/<?=$photos->body;?>" />
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?=$render('footer');

     

