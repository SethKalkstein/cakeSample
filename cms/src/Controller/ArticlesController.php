<?php 
//src/Controller/ArticlesController.php

namespace App\Controller;

use App\Model\Entity\Article;

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
    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if($this->request->is(["post", "put"])){
            $this->Articles->patchEntity($article, $this->request->getData());
            if($this->Articles->save($article)){
                $this->Flash->success(__("Your Article has been updated."));
                return $this->redirect(["action"=> "index"]);
            }
            $this->Flash->error(__("Unable to update your article."));
        }
        $this->set("article", $article);
    }
    public function delete($slug)
    {
        $this->request->allowMethod(["post", "delete"]);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if($this->Articles->delete($article)) {
            $this->Flash->success(__("The {0} article has been deleted.", $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }
}