<?php
namespace app\api\controller\v1;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use think\Controller;

class News extends Common{
    public function index(){
        $data = input('get.');
        $where['status'] = config('code.normal_status');
        if (!empty($data['cateid'])) {
            $where['cate_id'] = $data['cateid'];
        }
        if (!empty($data['keyword'])) {
            $where['title'] = ['like','%'.$data['keyword'].'%'];
        }
        $this->getPageAndSize($data);

        $total = model('article')->getNewsByCountCondition($where);
        $news = model('article')->getNewsByCondition($where,$this->from,$this->size);
        //$this->size为每页有多少条数据  $this->page为显示第page页的数据
        $result = [
            'total' => $total,
            'page_num' => ceil($total / $this->size),
            'list' => $this->getCateName($news),
        ];

        return show(1, 'OK', $result, 200);
    }

    /**
     * 获取详情接口
     */
    public function read()
    {
        //详情页面 APP -》1.x.com/3.html 2.接口

        $id = input('param.id', 0, 'intval');
        if (empty($id)) {
            new ApiException('输入的新闻id不合法！', 404);
        }

        //通过id 去获取数据表里的数据
        try {
            $news = model('article')->get($id);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), 400);
        }
        if (empty($news) || $news->status != config('code.normal_status')) {
            throw new ApiException('不存在该新闻', 404);
        }
        $cats = config('catetype.list');
        $news->catname = $cats[$news->cate_id];

        return show(config('code.success'), 'OK', $news, 200);

    }

}