<?php

namespace App\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\SearchModel;
use Lori\{Content, Config, Base, Validation};

class SearchController extends MainController
{
    public function index()
    {
        if (Request::getPost()) {

            $qa =  Request::getPost('q');

            $query = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '', $qa);

            if (!empty($query)) {

                Validation::Limits($query, lang('Too short'), '3', '128', '/search');

                // Успех и определим, что будем использовать
                // Далее индивидуально расширим (+ лайки, просмотры и т.д.)
                if (Config::get(Config::PARAM_SEARCH) == 0) {
                    $qa =  SearchModel::getSearch($query);
                    $result = array();
                    foreach ($qa as $ind => $row) {
                        $row['post_content']  = Content::text(Base::cutWords($row['post_content'], 32, '...'), 'text');
                        $result[$ind]         = $row;
                    }
                } else {
                    $qa =  SearchModel::getSearchServer($query);
                    $result = array();
                    foreach ($qa as $ind => $row) {
                        $result[$ind]         = $row;
                    }
                }
            } else {
                Base::addMsg(lang('Empty request'), 'error');
                redirect('/search');
            }
        } else {
            $query  = '';
            $result = '';
        }

        $uid  = Base::getUid();
        $data = [
            'h1'            => lang('Search'),
            'sheet'         => 'search',
            'meta_title'    => lang('Search'),
        ];

        return view(PR_VIEW_DIR . '/search/index', ['data' => $data, 'uid' => $uid, 'result' => $result, 'query' => $query]);
    }
}
