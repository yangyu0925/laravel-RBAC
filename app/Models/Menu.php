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
        return $this->sortTreeList($this->getAll(['id', 'name', 'url', 'slug', 'icon', 'parent_id', 'sort']));
    }

    public function getAll($columns = ['*'])
    {
        $result = $this->all($columns)->toArray();

        $sort = array_column($result, 'sort');

        array_multisort($sort, SORT_DESC, $result);

        return $result;
    }

    /**
     * 树形排序
     * @param array $data   需要排序的分类数据
     * @return array        多维数组
     */
    public function sortTreeList($data = [])
    {
        $tree = [];
        $temp = [];

        foreach ($data as $k => $v) {
            $temp[$v['id']] = $v;
        }

        foreach ($data as $value) {
            if (isset($temp[$value['parent_id']])) {
                $temp[$value['parent_id']]['child'][] = &$temp[$value['id']];
            } else {
                $tree[] = &$temp[$value['id']];
            }
        }

        unset($tmpMap);
        return $tree;
    }
}
