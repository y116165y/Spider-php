<?php
namespace app\common\model;
use think\Model;

class Article extends Base{
    public function getArticle($data=[]){
        $data['status'] = ['neq',config('code.delete_status')];
        return $this->where($data)->order('id desc')->select();
    }
    /**
     * 获取列表页接口数据
     * @param int $condition总数据
     */
    public function getNewsByCondition($condition = [], $from, $size = 5)
    {
        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $order = ['id' => 'desc'];
        $result = $this->where($condition)
            ->field($this->getListField())
            ->limit($from, $size)
            ->order($order)
            ->select();
        return $result;
    }

    /**
     * 获取列表页接口数据总数量
     * @param int $condition总数据
     */
    public function getNewsByCountCondition($condition = [])
    {
        if (!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')];
        }
        return $this->where($condition)->count();
    }

    // +----------------------------------------------------------------------
    // | 上部分：列表页     下部分：首页
    // +----------------------------------------------------------------------

    /**
     * 获取首页头部接口数据，顶部第一行新闻
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getIndexHadNormalNews($num = 4)
    {
        $data = [
            'status' => 1,
            'is_head_figure' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field($this->getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 获取首页推荐的接口数据
     */
    public function getPositionNormalNews($num = 20)
    {
        $data = [
            'status' => 1,
            'is_position' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field($this->getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 通用化过滤所需参数的数据字段
     */
    private function getListField()
    {
        return ['id',
            'title',
            'cate_id',
            'image',
            'des',
            'author',
            'datafrom'
                    ];
    }

}
