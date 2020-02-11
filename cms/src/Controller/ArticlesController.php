<?php 
//src/Controller/ArticlesController.php

namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{   
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent("Paginator");
        $this->loadComponent("Flash");
        $this->loadModel('Users');
        
        //everyone can see the index!
        $this->Auth->allow(['index']);

        //set the authenticated as a user object or false if noone is logged in
        $loggedIn = $this->Auth->user() ? $this->Users->get($this->Auth->user('id')) : false;

        $this->set('loggedIn', $loggedIn);   
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam("action");
 
        $pass = $this->request->getParam("pass");
        $slug = $this->request->getParam("pass.0");
        $article  = $this->Articles->findBySlug($slug)->first();

        $loggedInUser = $this->Users->get($this->Auth->user('id'));

        // echo "The Pass: ". "<br>"; 
        // echo var_dump($pass) . "<br>";
        // echo "The Slug: " .  "<br>"; 
        // echo var_dump($slug) . "<br>";
        // echo "The Article: " .  "<br>"; 
        // echo var_dump($article)  . "<br>"; 
        // The add and tags actions are always allowed to logged in users.

        if($loggedInUser == null){
            if(in_array($action, [ 'index', 'tags'])){
                return true;
            } else {
                return false;
            }
        } else if ($loggedInUser->role_id == 1){
            //admins can create edit and delete anyone's articles, they can do everything!
            return true;
        } else if ($loggedInUser->role_id == 2 && $action == 'edit'){
            //eidtors edit articles, but can't delete them or add new ones for people
            return true;
        } else if ($article != null && $article->user_id == $loggedInUser->id){
            //users can do anything to their own articles
            return true;
        } else if (in_array($action, ['index', 'view', 'tags', 'add'])){
            //anyone logged in can view all articles, individual articles, tags, or add an article
            return true;
        } else {
            return false;
        }
        return false;

        // if(in_array($action, ["add", "tags", "view", "index", "edit", "delete"])){
        //     return true;
        // }

        // $pass = $this->request->getParam("pass");
        // echo "The Pass: ". var_dump($pass);
        // // All other actions require a slug.
        // $slug = $this->request->getParam("pass.0");
        // if(!$slug){
        //     return false;
        // }
        //     // Check that the article belongs to the current user.
        // $article  = $this->Articles->findBySlug($slug)->first();

        // // return $article->user_id === $user["id"]; 
    }
    
    public function tags()
    {
        // The 'pass' key is provided by CakePHP and contains all
        // the passed URL path segments in the request.
        $tags = $this->request->getParam("pass");
        // Use the ArticlesTable to find tagged articles.
        $articles = $this->Articles->find("tagged", [
            "tags" => $tags
        ]);
        // Pass variables into the view template context.
        $this->set([
            "articles" => $articles,
            "tags" => $tags
        ]);
    }
    //different way to do the above function passing the request parameters 
    //as parameters to the method instead of using the getParam function to retreive them
/*     public function tags(...$tags)
    {
    // Use the ArticlesTable to find tagged articles.
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ]);

    // Pass variables into the view template context.
        $this->set([
         'articles' => $articles,
         'tags' => $tags
     ]);
    } */
    public function index()
    {
        // $action = $this->request->params['action'];
        // echo "<br>From the Index <br>" . var_dump($action) . "<br>";

        // $article6 = $this->Articles->newEntity();
        $this->paginate = ['contain' => ['Users', 'Users.Roles']];

        // $articles = $this->Paginator->paginate($this->Articles->find());
        $articles = $this->paginate($this->Articles->find());
        
        $this->set(compact('articles'));
    }
    // Add to existing src/Controller/ArticlesController.php file
    public function view($slug = null)
    {

        $article = $this->Articles
        ->findBySlug($slug)
        ->contain(["Tags", "Users"])
        ->firstOrFail();
        $this->set(compact('article'));
    }

    public function add(){


        $article = $this->Articles->newEntity();

        if($this->request->is('post')){
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            
            if($this->Auth->user('id') == $article->user_id || $this->Auth->user('role_id') == 1){
            // $article->user_id = $this->Auth->user("id");
                if ($this->Articles->save($article)){
                    $this->Flash->success(__("Your article has been saved."));
                    return $this->redirect((['action'=>'index']));
                }
            }
            $this->Flash->error(__("Unable to add your Article"));
        }
        $tags = $this->Articles->Tags->find("list");
        $this->set("tags", $tags);
        $this->set('article', $article);

        // $this->set(compact('user'));
        // $this->set('roles', $this->Users->Roles->find('list'));

        $this->set('users', $this->Articles->Users->find('list', ["keyField"=>"id", "valueField"=>"full_identifier"]));
    }

    public function edit($slug)
    {
/*         $article = $this->Articles
        ->findBySlug($slug)
        ->contain('Tags')
        ->firstOrFail(); */
        $article = $this->Articles
        ->findBySlug($slug)
        ->contain(['Tags'])
        ->firstOrFail();

        if($this->request->is(["post", "put"])){
            $this->Articles->patchEntity($article, $this->request->getData(), ["accessibleFields" => ["user_id" => false]]);
            
            if($this->Articles->save($article)){
                $this->Flash->success(__("Your Article has been updated."));
                return $this->redirect(["action"=> "index"]);
            }
            $this->Flash->error(__("Unable to update your article."));
        }
        $tags = $this->Articles->Tags->find("list");

        $this->set("tags", $tags);

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