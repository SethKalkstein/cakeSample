<?php 
//src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent("Paginator");
        $this->loadComponent("Flash");
        
    }
    public function index()
    {
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }
    // Add to existing src/Controller/ArticlesController.php file
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }
    public function add(){
        $article = $this->Articles->newEntity();
        if($this->request->is('post')){
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            //this is hardcoded now but will be fixed later when authentication is built out
            if ($this->Articles->save($article)){
                $this->Flash->success(__("Your article has been saved."));
                return $this->redirect((['action'=>'index']));
            }
        }
        $this->set('article', $article);
    }
}