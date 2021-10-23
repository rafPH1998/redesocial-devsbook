<?=$render('header', ['loggedUser' => $loggedUser]);?>
    <section class="container main">
    <?=$render('sidebar', ['activeMenu' => 'friends']);?>
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

                            <div class="tabs">
                                <div class="tab-item" data-for="followers">
                                    Seguidores
                                </div>
                                <div class="tab-item active" data-for="following">
                                    Seguindo
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-body" data-item="followers">
                                    
                                    <div class="full-friend-list">
                                        <?php foreach($user->followers as $follower):?> 
                                            <div class="friend-icon">
                                                <a href="<?=$base;?>/perfil/<?=$follower->id;?>">
                                                    <div class="friend-icon-avatar">
                                                        <img src="<?=$base;?>/media/avatars/<?=$follower->avatar;?>" />
                                                    </div>
                                                    <div class="friend-icon-name">
                                                        <?=$follower->name;?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach;?>
                                    </div>

                                </div>
                                <div class="tab-body" data-item="following">
                                    
                                    <div class="full-friend-list">
                                        <?php foreach($user->following as $followings):?> 
                                            <div class="friend-icon">
                                                <a href="<?=$base;?>/perfil/<?=$followings->id;?>">
                                                    <div class="friend-icon-avatar">
                                                        <img src="<?=$base;?>/media/avatars/<?=$followings->avatar;?>" />
                                                    </div>
                                                    <div class="friend-icon-name">
                                                        <?=$followings->name;?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach;?>
                                        
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
        </div>

</section>
<?=$render('footer');

     

