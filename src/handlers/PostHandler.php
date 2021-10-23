<?php
namespace src\handlers;

use \src\models\User;
use \src\models\Post;
use \src\models\PostComment;
use \src\models\PostLike;
use \src\models\UserRelation;

class PostHandler {
    
    public static function addPost($idUser, $type, $body) {
        $body = trim($body);

        if(!empty($idUser) && !empty($body)) {

            Post::insert([
                'id_user' => $idUser,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'body' => $body


            ])->execute();
        }
    } 

    //this function _postListToObject basically getter the posts and information the user where is it being used in getUserFeed and getHomeFeed
    public static function _postListToObject($postList, $loggedUserId) {
        $posts = [];

        foreach($postList as $postItem) {
            $newUser = new Post();
            $newUser->id = $postItem['id'];
            $newUser->type = $postItem['type'];
            $newUser->body = $postItem['body'];
            $newUser->created_at = $postItem['created_at'];
            $newUser->mine = false;

            if($postItem['id_user'] === $loggedUserId) {
                $newUser->mine = true;
            }
    
            $newPost = User::select()->where('id', $postItem['id_user'])->one();
            $newUser->user = new User();
            $newUser->user->id = $newPost['id'];
            $newUser->user->name = $newPost['name'];
            $newUser->user->email = $newPost['email'];
            $newUser->user->avatar = $newPost['avatar'];
            
            //like informations
            $likes = PostLike::select()->where('id_post', $postItem['id'])->get();

            $newUser->likeCount = count($likes);
            $newUser->liked = self::isLiked($postItem['id'], $loggedUserId);

            //getting the comments
            $newUser->comments = PostComment::select()->where('id_post', $postItem['id'])->get();
            foreach($newUser->comments as $key => $comment) {
                $newUser->comments[$key]['user'] = User::select()->where('id', $comment['id_user'])->one();
            }

            $posts[] = $newUser;
        }

        return $posts;
        
    }

    public static function isLiked($id, $loggedUserId) {
        $myLike = PostLike::select()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($myLike) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteLike($id, $loggedUserId) {
        PostLike::delete()
            ->where('id_post', $id)
            ->where('id_user', $loggedUserId)
        ->execute();
    }

    public static function addLike($id, $loggedUserId) {
        PostLike::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s')
        ])->execute();
    }

    public static function getUserFeed($idUser, $page, $loggedUserId) {
        $perPage = 2;

        //sorting these posts in order
        $postList = Post::select()
            ->where('id_user', $idUser)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
        ->where('id_user', $idUser)
        ->count();
        $pageCount = ceil($total / $perPage);;

        $posts = self::_postListToObject($postList, $loggedUserId);

        //returning the data(results)
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];
    }

    public static function getHomeFeed($idUser, $page) {
        $perPage = 2;
        
        // getting the list of users I follow.
        $userInfo = UserRelation::select()->where('user_from', $idUser)->get();
        $users = [];
        foreach($userInfo as $userItem) {
            $users[] = $userItem['user_to'];
        }
        $users[] = $idUser;
        
        //sorting these posts in order
        $postList = Post::select()
            ->where('id_user', 'in', $users)
            ->orderBy('created_at', 'desc')
            ->page($page, $perPage)
        ->get();

        $total = Post::select()
            ->where('id_user', 'in', $users)
        ->count();
        $pageCount = ceil($total / $perPage);

        $posts = self::_postListToObject($postList, $idUser);

        //returning the data(results)
        return [
            'posts' => $posts,
            'pageCount' => $pageCount,
            'currentPage' => $page
        ];

    }

    public static function getPhotoUser($idUser) {
        $photoList = Post::select()
            ->where('id_user', $idUser)
            ->where('type', 'photo')
        ->get();

        $photos = [];
        foreach($photoList as $photo) {
            $newPhoto = new Post();
            $newPhoto->id = $photo['id'];
            $newPhoto->type = $photo['type'];
            $newPhoto->created_at = $photo['created_at'];
            $newPhoto->body = $photo['body'];
            
            $photos[] = $newPhoto;
        }

        return $photos;
    }

    public static function isFollowing($from, $to) {
        $data = UserRelation::select()
            ->where('user_from', $from)
            ->where('user_to', $to)
        ->one();

        if($data) {
            return true;
        } else {
            return false;
        }
    }

    public static function follow($from, $to) {
        UserRelation::insert([
            'user_from' => $from,
            'user_to' => $to
        ])->execute();
    }

    public static function unFollow($from, $to) {
        UserRelation::delete([
            'user_from' => $from,
            'user_to' => $to
        ])->execute();
    }

    public static function addComment($id, $txt, $loggedUserId) {
        PostComment::insert([
            'id_post' => $id,
            'id_user' => $loggedUserId,
            'created_at' => date('Y-m-d H:i:s'),
            'body' => $txt
        ])->execute();
    }

    public static function delete($id, $loggedUserId) {
        //1. check if the post exists and if it's yours
        $post = Post::select()
            ->where('id', $id)
            ->where('id_user', $loggedUserId)
        ->get();

        if(count($post) > 0) {
            $post = $post[0];

            //2. delete likes and comments
            PostLike::delete()->where('id_post', $id)->execute();
            PostComment::delete()->where('id_post', $id)->execute();

            // 3. if the photo is type == photo, delete the file
            if($post['type'] === 'photo') {
                $img = __DIR__.'/../../public/media/uploads/'.$post['body'];
                if(file_exists($img)) {
                    unlink($img);
                }
            }
            // 4. delete the post
            Post::delete()->where('id', $id)->execute();
        }
    }

}