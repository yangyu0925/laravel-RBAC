<?php

namespace App\Models;

use App\Traits\Admin\ActionButtonTrait;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use ActionButtonTrait;

    protected $fillable = [
        'name', 'icon', 'slug', 'parent_id', 'url', 'heightlight_url', 'sort'
    ];

    public function getMenuComposerData()
    {
        return $this->sortTreeList($this->sortList($this->getAll(['id','name','url','slug','parent_id'])));
    }

    public function getAll($columns = ['*'])
    {
        $res = $this->all($columns)->toArray();

        $list = $this->sortList($res);

//        foreach ($list as $key => $value) {
//            $list[$key]['button'] = $this->getActionButtons('menus',$value['id']);
//        }

        return $list;
    }

    /**
     * 排序
     * @param array     $data   需要循环的数组
     * @param int       $id     获取id为$id下的子分类，0为所有分类
     * @param array     $arr    将获取到的数据暂时存储的数组中，方便数据返回
     * @return array            二维数组
     */
    protected function sortList(array $data, $id = 0, &$arr = [])
    {
        foreach ($data as $v) {
            if ($id == $v['parent_id']) {
                $arr[] = $v;
                $this->sortList($data, $v['id'], $arr);
            }
        }

        return $arr;
    }

    /**
     * 树形排序
     * @param array $data   需要排序的分类数据
     * @return array        多维数组
     */
    public function sortTreeList($data = [])
    {
        $tree = array();
        $tmpMap = array();
        foreach ($data as $k => $v) {
            $tmpMap[$v['id']] = $v;
        }

        foreach ($data as $value) {
            if (isset($tmpMap[$value['parent_id']])) {
                $tmpMap[$value['parent_id']]['child'][] = &$tmpMap[$value['id']];
            } else {
                $tree[] = &$tmpMap[$value['id']];
            }
        }

        unset($tmpMap);
        return $tree;
    }
}
