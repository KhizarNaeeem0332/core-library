<?php

namespace Bindeveloperz\Core\Database;

use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\UrlWindow;

class Pagination
{

    private $page = "";
    private $perPage = 15;
    private $columns = ["*"];
    private $pageName = "page";
    private $result;
    private $query;

    private static $instance = null ;

    private function __construct($pageName , $page)
    {
        $this->pageName = $pageName;
        $this->page = $page;
    }

    public static function getInstance($pageName = "page" , $page = "")
    {
        if(self::$instance != null)
        {
            return self::$instance;
        }
        return new Pagination($pageName , $page);
    }

    public function setColumns($columns=['*'])
    {
        $this->columns = $columns;
    }


    public function result($query)
    {

        $this->query = $query;

        return $this;
    }

    public function paginate( $perPage=15, $columns=null)
    {
        $this->perPage = $perPage;
        $this->columns = ($columns != null) ? $columns : $this->columns;

        LengthAwarePaginator::currentPathResolver(function () {
            return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
        });

        LengthAwarePaginator::currentPageResolver(function ($pageName = 'page') {
            $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : 1;
            return $page;
        });

        $this->page = isset($_REQUEST[$this->pageName]) ? $_REQUEST[$this->pageName] : null;


        if(is_array($this->query))
        {

            $offset = ($this->page * $this->perPage) - $this->perPage;

            $record =  new LengthAwarePaginator(
                array_slice($this->query, $offset, $perPage, true), // Only grab the items we need
                count($this->query), // Total items
                $perPage, // Items per page
                $this->page, // Current page
                ['path' => Paginator::resolveCurrentPath() ] // We need this so we can keep all old query parameters from the url
            );

            $this->result = $record;
            return $this->result;
        }

        $record = $this->query->paginate($this->perPage, $this->columns, $this->pageName, $this->page);
        $this->result = $record;
        return  $this->result;
    }

    public function render($paginatePage = "full")
    {

        $page = (strtolower($paginatePage) == "full") ? "bs4-paginate.php" : "bs4-simple-paginate.php";

        $window = UrlWindow::make( $this->result);
        $elements =  array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);
        $_paginate['result'] =   $this->result;
        $_paginate['elements'] =  $elements;
        require  __DIR__ . '/views/' . $page;
    }




}