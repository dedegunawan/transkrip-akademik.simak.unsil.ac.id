<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 25/10/19
 * Time: 17.10
 */

namespace App\Exceptions;


class AppException extends \Exception
{
    protected $viewTemplate;
    protected $datas=array();
    public function render()
    {
        $view = $this->getViewTemplate() ?? 'exceptions.app';
        return response()->view($view, array_merge($this->getDatas(), ['exception' => $this]), 500);
    }

    /**
     * @return mixed
     */
    public function getViewTemplate()
    {
        return $this->viewTemplate;
    }

    /**
     * @param mixed $viewTemplate
     */
    public function setViewTemplate($viewTemplate): void
    {
        $this->viewTemplate = $viewTemplate;
    }

    /**
     * @return mixed
     */
    public function getDatas()
    {
        return $this->datas;
    }

    /**
     * @param mixed $datas
     */
    public function setDatas($datas): void
    {
        $this->datas = $datas;
    }



}
