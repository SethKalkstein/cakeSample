<?php 
//src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponent("Paginator");
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }
    // Add to existing src/Controller/ArticlesController.php file
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }
}