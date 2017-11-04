<?php

namespace App\Controllers\AuthAdmin;

use App\Models\Admins;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthAdminController extends Controller
{
    public function getSignOut($request, $response)
    {
        // Sign Out
        $this->admin->logoutAdmin();
        // Redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'admin/signin.twig');
    }
    
    public function postSignIn($request, $response)
    {
        $auth = $this->admin->attemptAdmin(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if(!$auth){
            $this->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('admin.signin'));
        }

        return $response->withRedirect($this->router->pathFor('admin.home')); 
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'admin/signup.twig');
    }
    
    public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.admin.signup'));
        }

        $admin = Admins::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        $this->flash->addMessage('info', 'You have been signed up!');

        $this->admin->attemptAdmin($admin->email, $request->getParam('password'));

        return $response->withRedirect($this->router->pathFor('admin.home'));
    }
}
