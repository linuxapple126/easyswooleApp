<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/8/26
 * Time: 10:26
 */

namespace App\HttpController\Admin;


use App\HttpController\Base;

class Index extends Base
{
    public function index()
    {
        return $this->view('admin.index');
    }
}