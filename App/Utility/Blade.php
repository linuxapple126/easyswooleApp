<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/8/19
 * Time: 16:30
 */

namespace App\Utility;


use duncan3dc\Laravel\BladeInstance;
use EasySwoole\Template\RenderInterface;
use Throwable;

class Blade implements RenderInterface
{
    private $engine;

    /**
     * Blade constructor.
     * @param $viewsDir
     * @param string $cacheDir
     */
    public function __construct($viewsDir, $cacheDir = '')
    {
        if ($cacheDir == '') {
            $cacheDir = sys_get_temp_dir();
        }
        $this->engine = new BladeInstance($viewsDir, $cacheDir);
    }

    /**
     * 模板渲染
     * @param string $template
     * @param array $data
     * @param array $options
     * @return string|null
     */
    public function render(string $template, array $data = [], array $options = []): ?string
    {
        $content = $this->engine->render($template, $data);
        return $content;
    }

    /**
     * 每次渲染完成都会执行清理
     * @param string|null $result
     * @param string $template
     * @param array $data
     * @param array $options
     */
    public function afterRender(?string $result, string $template, array $data = [], array $options = [])
    {

    }

    /**
     * 异常处理
     * @param Throwable $throwable
     * @return string
     * @throws Throwable
     */
    public function onException(\Throwable $throwable): string
    {
        $msg = "{$throwable->getMessage()} at file:{$throwable->getFile()} line:{$throwable->getLine()}";
        trigger_error($msg);
        return $msg;
    }

}