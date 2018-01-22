<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\Users;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function index($request, $response)
    {
        $users  = Users::all();
        return $this->view->render($response, 'auth/home.twig', ['users' => $users]);
    }

    public function getSignOut($request, $response)
    {
        // Sign Out
        $this->auth->logout();
        // Redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }
    
    public function postSignIn($request, $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if(!$auth){
            $this->flash->addMessage('error', 'pastikan anda memasukkan kredensial dengan benar.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('auth.home')); 
    }

    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }
    
    public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = Users::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        $this->flash->addMessage('info', 'You have been signed up!');

        $this->auth->attempt($user->email, $request->getParam('password'));

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function userVerification($request, $response)
    {
        $user = Users::where('password', $request->getParam('link'))->first();

        if ($request->getParam('link') == $user->password) {
            
            $user->status = 1;
            $user->save();

            return $this->view->render($response, 'auth/signin.twig');

        }else{
            return "Code not verified!";
        }
    }
}
