<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\UserModel;
use App\Models\AdminModel;
use Parsedown;
use Base;

class AdminController extends \MainController
{
	public function index()
	{
        // Если TL участника не равен 5 (персонал) - редирект
        $account = Request::getSession('account');
        if(!$isAdmin = UserModel::isAdmin($account['user_id'])) {
            redirect('/');
        }
      
        $user_all = AdminModel::UsersAll();
        
        $result = Array();
        foreach($user_all as $ind => $row){
             
            if(!$row['avatar'] ) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']        = $row['avatar'];  
            $row['replayIp']      = AdminModel::replayIp($row['reg_ip']);
            $row['isBan']         = AdminModel::isBan($row['id']);
            $row['created_at']    = Base::ru_date($row['created_at']); 
            $row['logs_date']     = Base::ru_date(empty($row['logs_date']));
            $row['updated_at']    = Base::ru_date($row['updated_at']);
            $result[$ind]         = $row;
         
        } 
        
        $uid  = Base::getUid();
        $data = [
            'title'        => 'Последние сессии | Админка',
            'description'  => 'Админка на AreaDev',
            'users'        => $result,
        ];

         return view("admin/index", ['data' => $data, 'uid' => $uid]);
	}
    
    public function banUser() 
    {
        // Если TL участника не равен 5 (персонал) - редирект
        $account = Request::getSession('account');
        if(!$isAdmin = UserModel::isAdmin($account['user_id'])) {
            redirect('/');
        }
        
        $user_id = Request::get('id');
        AdminModel::setBanUser($user_id);
        
        return true;
    }
    
    // Удаленые комментарии
    public function Comments ()
    {
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
         
        $comm = AdminModel::getCommentsDell();
 
        $account    = \Request::getSession('account');
        $user_id    = $account ? $account['user_id'] : 0;
 
        $result = Array();
        foreach($comm  as $ind => $row){
            if(!$row['avatar']) {
                $row['avatar'] = 'noavatar.png';
            } 
            $row['avatar']  = $row['avatar'];
            $row['content'] = $Parsedown->text($row['comment_content']);
            $row['date']    = Base::ru_date($row['comment_date']);
            $result[$ind]   = $row;
        }
        
        $uid  = Base::getUid();
        $data = [
            'h1'          => 'Удаленные комментарии',
            'title'       => 'Удаленные комментарии' . ' | ' . $GLOBALS['conf']['sitename'],
            'description' => 'Все удаленные комментарии на сайте в порядке очередности. ' . $GLOBALS['conf']['sitename'],
        ]; 
 
        return view("admin/comm_del", ['data' => $data, 'uid' => $uid, 'comments' => $result]);
    }
     
    // Удаление комментария
    public function recoverComment()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $comm_id = \Request::getPostInt('comm_id');
        
        AdminModel::CommentsRecover($comm_id);
        
        return true;
    }
}