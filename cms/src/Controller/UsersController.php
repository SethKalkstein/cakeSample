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
        //set the authenticated as a user object or false if noone is logged in
        $loggedIn = $this->Auth->user() ? $this->getLoggedIn() : false;

        $this->Auth->allow(["logout", "delete",
            (!$loggedIn || $loggedIn->role_id == 1 ? "add" : "")]);

        if(!$loggedIn) $this->Auth->deny('edit', 'index', 'view', 'delete');
    
        $this->set('loggedIn', $loggedIn);
    }

     private function getLoggedIn(){

        $loggedInUser = $this->Auth->user() ? $this->Users->get($this->Auth->user('id')) : false;

        return $loggedInUser;
     }

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
 
        $user = $this->Users->get($id, [
            'contain' => ['Articles', 'Roles']
        ]);
        // echo $user->role->name; //that works!!!!!!!
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
                if($this->Auth->user()){
                    return $this->redirect(['action' => 'index']);
                } else {
                    return $this->redirect('/');
                }
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
        $loggedIn = $this->getLoggedIn();

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        
        if ($loggedIn && ($user->id == $loggedIn->id || $loggedIn->role_id == 1)){

            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
    
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
            $this->set(compact('user'));
            $this->set('roles', $this->Users->Roles->find('list'));
        } else {
            $this->Flash->error("You are not allowed to edit user: {$user->lname}, {$user->fname}.");
            return $this->redirect(['action' => 'index']);
        }
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
        $user = $this->Users->get($id);
        $loggedIn = $this->getLoggedIn();

        if(!$loggedIn) {
            $this->Flash->error(__('You do not have access to this area.'));
            return $this->redirect('/');
        }

        if($user->id == $loggedIn->id || $loggedIn->role_id == 1){
            $this->request->allowMethod(['post', 'delete']);        
            
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            
                if ($user->id == $loggedIn->id){
                    return $this->redirect($this->Auth->logout());
                }
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('You do not have access to delete this user.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        if($this->request->is("post")) {
            $user = $this->Auth->identify();
            if($user) {
                $this->Auth->setUser($user);

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
