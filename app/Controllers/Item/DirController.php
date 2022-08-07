<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\PostModel;
use App\Models\Item\{WebModel, UserAreaModel, FacetModel};
use UserData, Meta;

class DirController extends Controller
{
    protected $limit = 25;

    // List of sites by topic (sites by "category")
    // Лист сайтов по темам (сайты по "категориям")
    public function index($sheet)
    {
        $os = ['all', 'github', 'wap'];
        if (!in_array($screening = Request::get('grouping'), $os)) {
            self::error404();
        }

        $category  = FacetModel::get(Request::get('slug'), 'slug', $this->user['trust_level']);
        self::error404($category);

        // We will get children
        $childrens =  FacetModel::getChildrens($category['facet_id'], $screening);

        if ($category['facet_post_related']) {
            $related_posts = PostModel::postRelated($category['facet_post_related']);
        }

        $items      = WebModel::feedItem($this->pageNumber, $this->limit, $childrens, $this->user, $category['facet_id'], $sheet, $screening);
        $pagesCount = WebModel::feedItemCount($childrens,  $category['facet_id'],  $sheet, $screening);

        $m = [
            'og'    => false,
            'url'   => url('web.dir', ['grouping' => 'all', 'slug' => $category['facet_slug']]),
        ];

        $title = __('web.' . $sheet . '_title', ['name' => $category['facet_title']]);
        $description_info = $category['facet_title'] . '. ' . $category['facet_description'];
        $description  = __('web.' . $sheet . '_desc', ['description_info' => $description_info]);

        $count_site = UserData::checkAdmin() ? 0 : UserAreaModel::getUserSitesCount($this->user['id']);

        $tree = FacetModel::breadcrumb($category['facet_id']);

        return $this->render(
            '/item/sites',
            'item',
            [
                'meta'  => Meta::get($title, $description, $m),
                'data'  => [
                    'screening'         => $screening,
                    'sheet'             => $sheet,
                    'count'             => $pagesCount,
                    'pagesCount'        => ceil($pagesCount / $this->limit),
                    'pNum'              => $this->pageNumber,
                    'related_posts'     => $related_posts ?? false,
                    'items'             => $items,
                    'category'          => $category,
                    'childrens'         => $childrens,
                    'user_count_site'   => $count_site,
                    'breadcrumb'        => self::breadcrumb($tree, $screening),
                    'low_matching'      => FacetModel::getLowMatching($category['facet_id']),
                ]
            ]
        );
    }

    // Bread crumbs
    public static function breadcrumb($tree, $screening)
    {
        $arr = [
            ['name' => __('web.catalog'), 'link' => url('web')]
        ];

        $result = [];
        foreach ($tree as $row) {
            $result[] = ["name" => $row['name'], "link" => url('web.dir', ['grouping' => $screening, 'slug' => $row['link']])];
        }

        return array_merge($arr, $result);
    }

    // Click-throughs
    // Переходы
    public function cleek()
    {
        $id     = Request::getPostInt('id');
        $item   = WebModel::getItemId($id);
        self::error404($item);

        WebModel::setCleek($item['item_id']);

        return true;
    }
}
