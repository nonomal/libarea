<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\SpaceModel;
use App\Models\CommentModel;
use App\Models\VotesCommentModel;
use App\Models\VotesPostModel;
use Base;
use Parsedown;

class PostController extends \MainController
{
   
    // Главная страница
    public function index() {

        if (Request::get('page')) {
            $page = \Request::get('page');
        } else {
            $page = 1;
        }

        if (!$page) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
 
        if($account = Request::getSession('account')){
            // Получаем все пространства отписанные участником
            $space_user  = SpaceModel::getSpaceUser($account['user_id']);
            $user_id     = $account['user_id'];
            $trust_level = $account['trust_level'];
        } else {
            $space_user  = [];
            $user_id     = 0;
            $trust_level = 0;
        }
            
        $pagesCount = PostModel::getPostHomeCount($space_user); 
        $posts      = PostModel::getPostHome($page, $space_user, $trust_level, $user_id);

        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            
            if(Base::getStrlen($row['post_url']) > 6) {
                $parse = parse_url($row['post_url']);
                $row['post_url'] = $parse['host'];  
            } 
            
            $row['post_url']            = $row['post_url'];
            $row['avatar']              = $row['avatar'];
            $row['num_comments']        = Base::ru_num('comm', $row['post_comments']);
            $row['post_date']           = Base::ru_date($row['post_date']);
            $result[$ind]               = $row;
         
        }  
 
        // Последние комментарии и отписанные пространства
        $latest_comments = CommentModel::latestComments();
        $space_hide      = SpaceModel::getSpaceUser($user_id);
        
        $result_comm = Array();
        foreach($latest_comments as $ind => $row){
            
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
   
            $row['comment_avatar']     = $row['avatar'];
            $row['comment_content']    = htmlspecialchars(mb_substr($row['comment_content'],0,81, 'utf-8'));  
            $row['comment_date']       = Base::ru_date($row['comment_date']);
            $result_comm[$ind]         = $row;
         
        }

        $uid  = Base::getUid();
        $data = [
            'title'            => 'Посты сообщества | ' . $GLOBALS['conf']['sitename'], 
            'description'      => 'Главная страница сообщества, форум, посты. ' . $GLOBALS['conf']['sitename'],
            'space_hide'       => $space_hide,
            'latest_comments'  => $result_comm,
            'pagesCount'       => $pagesCount,
            'pNum'             => $page,
        ];

        return view("home", ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }

    // Посты с начальными не нулевыми голосами, с голосованием, например, от 5
    public function topPost() { 
    
        $account   = \Request::getSession('account');
        $user_id = (!$account) ? 0 : $account['user_id'];
    
        // Пока Top - по количеству комментариев  
        $posts = PostModel::getPostTop($user_id);
 
        $result = Array();
        foreach($posts as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
 
            $row['avatar']        = $row['avatar'];
            $row['num_comments']  = Base::ru_num('comm', $row['post_comments']);
            $row['post_date']     = Base::ru_date($row['post_date']);
            $result[$ind]         = $row;
         
        }  
        
        // Последние комментарии
        $latest_comments = CommentModel::latestComments();
        
        $result_comm = Array();
        foreach($latest_comments as $ind => $row){
            
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
   
            $row['comment_avatar']     = $row['avatar'];
            $row['comment_content']    = htmlspecialchars(mb_substr($row['comment_content'],0,81, 'utf-8'));  
            $row['comment_date']       = Base::ru_date($row['comment_date']);
            $result_comm[$ind]         = $row;
         
        }
        
        $uid  = Base::getUid();
        $data = [
            'title'            => 'Популярные посты | ' . $GLOBALS['conf']['sitename'], 
            'description'      => 'Самые популярные посты сообществе. ' . $GLOBALS['conf']['sitename'],
            'latest_comments'  => 0,
            'space_hide'       => 0,
            'latest_comments'  => $result_comm,
            'pagesCount'       => 0,
        ];

        return view("home", ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }

    // Полный пост
    public function view()
    {
        if(!empty($_SESSION['account']['user_id'])) {
            $uid   = $_SESSION['account']['user_id'];
        } else {
            $uid   = 0;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        // Получим пост по slug
        $slug = \Request::get('slug');
        
        $post = []; 
        $post = PostModel::getPost($slug, $uid);
 
        // Если нет поста
        if (empty($post))
        {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        if(!$post['avatar']) {
            $post['avatar'] = 'noavatar.png';
        }
  
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        if($post['post_date'] != $post['edit_date']) {
            $post['edit_date'] = $post['edit_date'];
        } else {
            $post['edit_date'] = NULL;
        }
        
        if(Base::getStrlen($post['post_url']) > 6) {
            $post['post_url_full'] = $post['post_url'];
            $parse = parse_url($post['post_url']);
            $post['post_url'] = $parse['host'];  
        } else {
            $post['post_url_full'] = null;
        }
        
        // Обработает некоторые поля
        $post['post_content']   = $Parsedown->text($post['post_content']);
        $post['post_url']       = $post['post_url'];
        $post['post_url_full']  = $post['post_url_full'];
        $post['post_date']      = Base::ru_date($post['post_date']);
        $post['edit_date']      = Base::ru_date($post['edit_date']);
        $post['avatar']         = $post['avatar'];
        $post['num_comments']   = Base::ru_num('comm', $post['post_comments']); 
        $post['favorite_post']  = PostModel::getMyFavorite($post['post_id'], $uid);
        
        // Получим комментарии
        $post_comments = CommentModel::getCommentsPost($post['post_id']);
 
        $comments = Array();
        foreach($post_comments as $ind => $row){
            
            if(!$row['avatar']) {
                $row['avatar']  = 'noavatar.png';
            } 
            
            if(strtotime($row['comment_modified']) < strtotime($row['comment_date'])) {
                $row['edit'] = 1;
            }
 
            $row['comment_on']          = $row['comment_on'];  
            $row['avatar']              = $row['avatar'];
            $row['content']             = $Parsedown->text($row['comment_content']);
            $row['comment_date']        = Base::ru_date($row['comment_date']);
            $row['after']               = $row['comment_after'];
            $row['del']                 = $row['comment_del'];
            $row['comm_vote_status']    = VotesCommentModel::getVoteStatus($row['comment_id'], $uid);
            $comments[$ind]             = $row;
         
        }
       
        // Комментарии
        $comms = $this->buildTree(0, 0, $comments);
  
        $uid  = Base::getUid();
        $data = [
            'title'        => $post['post_title'] . ' | ' . $GLOBALS['conf']['sitename'], 
            'description'  => 'Тут надо сделать описание. ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view("post/view", ['data' => $data, 'post' => $post, 'comms' => $comms,  'uid' => $uid]);
    }
    
    // Для дерева комментариев
     private function buildTree($comment_on , $level, $comments, $tree=array()){
        $level++;
        foreach($comments as $comment){
            if ($comment['comment_on'] == $comment_on ){
                $comment['level'] = $level-1;
                $tree[] = $comment;
                $tree = $this->buildTree($comment['comment_id'], $level, $comments, $tree);
            }
        }
		return $tree;
    }
    
    // Посты участника
    public function userPosts()
    {
        $account    = \Request::getSession('account');
        $user_id    = (!$account) ? 0 : $account['user_id'];
        
        $login = \Request::get('login');
        $post_user  = PostModel::getUsersPosts($login, $user_id); 
        
        // Если нет такого пользователя
        if(!$post_user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $result = Array();
        foreach($post_user as $ind => $row){
             
            if(!$row['avatar']) {
                $row['avatar']  = 'noavatar.png';
            } 
 
            $row['avatar']      = $row['avatar'];
            $row['post_title']  = $row['post_title'];
            $row['post_slug']   = $row['post_slug'];
            $row['post_date']   = Base::ru_date($row['post_date']);
            $result[$ind]       = $row;
         
        }
 
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Посты ' . $login, 
            'title'         => 'Посты ' . $login . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Посты участника ' . $login . ' с сообществе ' . $GLOBALS['conf']['sitename'],
        ]; 
        
        return view("post/user", ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
    
    // Форма добавление поста
    public function addPost() 
    {
        // Будем проверять ограничение на частоту 
        // print_r(PostModel::getPostSpeed(1));
        
        $space = SpaceModel::getSpaceSelect();
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Добавить пост',
            'title'         => 'Добавить пост' . ' | ' . $GLOBALS['conf']['sitename'],
            'description'   => 'Страница добавления поста',
        ];  
       
        return view("post/add", ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Добавление поста
    public function createPost()
    {
        // Получаем title и содержание
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_content_preview   = \Request::getPost('content_preview');
        $post_content_img       = \Request::getPost('content_img');
        $post_url               = \Request::getPost('post_url');
        
        // IP адрес и ID кто добавляет
        $post_ip_int  = \Request::getRemoteAddress();
        $post_user_id = $_SESSION['account']['user_id'];
        
        // Получаем id пространства
        $space_id     = \Request::getPost('space_id');
        
        // Проверяем длину title
        if (Base::getStrlen($post_title) < 6 || Base::getStrlen($post_title) > 260)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 260 знаков', 'error');
            redirect('/post/add');
            return true;
        }
        
        // Проверяем длину тела
        if (Base::getStrlen($post_content) < 6 || Base::getStrlen($post_content) > 2500)
        {
            Base::addMsg('Длина поста должна быть от 6 до 2500 знаков', 'error');
            redirect('/post/add');
            return true;
        }
        
        // Проверяем выбор пространства
        if ($space_id == '')
        {
            Base::addMsg('Выберите пространство', 'error');
            redirect('/post/add');
            return true;
        }
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url             = (empty($post_url)) ? '' : $post_url;
        $post_content_preview = (empty($post_content_preview)) ? '' : $post_content_preview;
        $post_content_img     = (empty($post_content_img)) ? '' : $post_content_img;

        // Ограничим частоту добавления
        // PostModel::getPostSpeed($post_user_id);
        
        // Получаем SEO поста
        $post_slug = Base::seo($post_title); 
        
        $data = [
            'post_title'            => $post_title,
            'post_content'          => $post_content,
            'post_content_preview'  => $post_content_preview,
            'post_content_img'      => $post_content_img,
            'post_slug'             => $post_slug,
            'post_ip_int'           => $post_ip_int,
            'post_user_id'          => $post_user_id,
            'post_space_id'         => $space_id,
            'post_url'              => $post_url,
        ];
        
        // Записываем пост
        PostModel::AddPost($data);
        
        redirect('/');   
    }
    
    // Парсим title
    public function grabTitle() 
    {
        $url   = \Request::getPost('uri');
        preg_match("/<title>(.+)<\/title>/siU", file_get_contents($url), $matches);
        $title = $matches[1];

        return $title;
    }
    
    // Показ формы поста для редактирование
    public function editPost() 
    {
        $post_id = \Request::get('id');
        $account = \Request::getSession('account');
        
        // Получим пост
        $post   = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }

        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $account['user_id'] && $account['trust_level'] != 5) {
            redirect('/');
        }
        
        $space = SpaceModel::getSpaceSelect();
        
        $uid  = Base::getUid();
        $data = [
            'h1'            => 'Изменить пост',
            'title'         => 'Измененение поста ' . ' | ' . $GLOBALS['conf']['sitename'], 
            'description'   => 'Изменение поста на ' . $GLOBALS['conf']['sitename'],
        ];
        
        return view("post/edit", ['data' => $data, 'uid' => $uid, 'post' => $post, 'space' => $space]);
    }
    
    // Изменяем пост
    public function editPostRecording() 
    {
        $post_id                = \Request::getPostInt('post_id');
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_content_preview   = \Request::getPost('content_preview');
        $post_content_img       = \Request::getPost('content_img');
        $post_closed            = \Request::getPost('closed');
        $post_top               = \Request::getPost('top');
        $post_space_id          = \Request::getPostInt('space_id');
        $post_url               = \Request::getPost('post_url');
        
        $account = \Request::getSession('account');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
        
        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $account['user_id'] && $account['trust_level'] != 5) {
            redirect('/');
        }
        
        // Проверяем длину title
        if (Base::getStrlen($post_title) < 6 || Base::getStrlen($post_title) > 320)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 320 знаков', 'error');
            redirect('/post/edit' .$post_id);
            return true;
        }
        
        // Проверяем длину тела
        if (Base::getStrlen($post_content) < 6 || Base::getStrlen($post_content) > 2500)
        {
            Base::addMsg('Длина заголовка должна быть от 6 до 2520 знаков', 'error');
            redirect('/post/edit' .$post_id);
            return true;
        }
        
        if ($post_closed != 1) { 
            $post_closed = 0;
        }
        
        if ($post_top != 1) { 
            $post_top = 0;
        }

        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных
        // При изменение url считаем частоту смену url после добавления у конкретного пользователя
        // Если больше N оповещение персонала, если изменен на запрещенный, скрытие поста,
        // или более расширенное поведение, а пока просто проверим
        $post_url             = (empty($post_url)) ? '' : $post_url;
        $post_content_preview = (empty($post_content_preview)) ? '' : $post_content_preview;
        $post_content_img     = (empty($post_content_img)) ? '' : $post_content_img;
        
        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title, 
            'post_content'          => $post_content,
            'post_content_preview'  => $post_content_preview,
            'post_content_img'      => $post_content_img,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
            'post_space_id'         => $post_space_id,
            'post_url'              => $post_url,
        ];
        
        // Перезапишем пост
        PostModel::editPost($data);
        
        redirect('/posts/' . $post['post_slug']);
    }
    
    // Размещение своего поста у себя в профиле
    public function addPostProf()
    {
        $post_id = \Request::getPostInt('post_id');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
        
        // Это делать может только может только автор
        if ($post['post_user_id'] != $_SESSION['account']['user_id']) {
            return true;
        }
        
        PostModel::addPostProfile($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Помещаем пост в закладки
    public function addPostFavorite()
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            redirect('/');
        }
        
        PostModel::setPostFavorite($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Удаляем пост / + восстанавливаем пост
    public function deletePost()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $post_id = \Request::getPostInt('post_id');
        
        PostModel::PostDelete($post_id);
       
        return true;
    }
  
    // Просмотр поста с титульной страницы
    public function shownPost() 
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            return false;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        $post = $Parsedown->text($post['post_content']);

        return view("post/postcode", ['post_content' => $post]);
    }
  
}