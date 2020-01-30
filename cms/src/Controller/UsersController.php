<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(["logout"]);
        //set the authenticated as a user object or false if noone is logged in
        $loggedIn = $this->Auth->user() ? $this->Users->get($this->Auth->user('id')) : false;
        $this->set('loggedIn', $loggedIn);
        // $loggedUser = $this->Auth->identify();
        // echo "Using Identify: ".var_dump($loggedUser);
        // $loggedInUser = $this->Users->get($this->Auth->user('id'));
        // echo "Using Nested Get: ";
        // echo var_dump($loggedInUser);
        // $other = $this->Users->get(3);
        // echo var_dump($other);
    }

/*     private function getLoggedIn(){

        $loggedInUser = $this->Auth->user() ? $this->Users->get($this->Auth->user('id')) : false;

        return $loggedInUser;
    } */

    public function index()
    {
        $this->paginate = [
            'contain' => ['Roles']
        ];

        $users = $this->paginate($this->Users);

        $this->set('_serialize', ['users']);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $loggedIn = $this->getLoggedIn();
        $loggedInRoleLevel = $this->getLoggedIn()->role_id;
        $user = $this->Users->get($id, [
            'contain' => ['Articles'],
        ]);
        
        // if($this->getLoggedIn()->id == $user->id){
/*         echo "From the view: <br>";
        echo var_dump($this->getLoggedIn()); */
        if(!$loggedIn){
            echo "You're not even logged in, dude!!!";
        } elseif($this->getLoggedIn()->id == $user->id){
            echo "This is the person who is logged in";
            echo "<br>";
            echo "logged in person's role is ".$loggedIn->role_id;
        } else {
            echo "This is NOT the person who is logged in ";
            echo "<br>";
            echo "NOT logged in person's role is ". $this->Users->Roles->get($loggedIn->role_id)->name;
            // echo "logged in person's role is ". $this->Users->get($loggedIn->id, ["role_id"]);
            // echo "NOT logged in person's role is ". $loggedIn->get("role_id", ["contain" => ["name"]]);
            // echo "another try: ". $this->Roles->get($loggedIn->id);
            // echo "NOT logged in person's role is ". $loggedIn->role->name; 
            //  echo "NOT logged in person's role is ". $loggedIn->role->name;
        }
        $this->set('loggedIn', $loggedIn);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            // $this->Flash->error();
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('roles', $this->Users->Roles->find('list'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function login()
    {
        if($this->request->is("post")) {
            $user = $this->Auth->identify();
            if($user) {
                $this->Auth->setUser($user);
                // return $this->setAction('view', "3");
                // return $this->redirect($this->setAction('view', "1"));
                return $this->redirect(['action' => 'view',  $this->Auth->user('id')]);
            }
            $this->Flash->error("Your username or password is incorrect.");
        }
    }

    public function logout()
    {
        $this->Flash->success("You are now logged out.");
        return $this->redirect($this->Auth->logout());
    }
}
