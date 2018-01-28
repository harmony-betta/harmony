<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\Users;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    /**
     * @param  Request Interface
     * @param  Response Interface
     * @return Template HTML(twig) Dashboard User
     */
    public function index($request, $response)
    {
        return $this->view->render($response, 'auth/home.twig');
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     * @return boolean and redicect to Home
     */
    public function getSignOut($request, $response)
    {
        // Sign Out
        $this->auth->logout();
        // Redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     * @return Template HTML(twig) Login Page
     */
    public function getSignIn($request, $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }
    
    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Respect Validation
     * 
     * if data not validated, user will redirect back to Sing In Page
     * otherwise user will attempt to login using Email and Password Credentials
     * 
     * if attempt failed user will redirect back to Sing In Page
     * otherwise if everything is OK, user will redirect to Homepage / Dashboard
     * 
     * @return Template HTML(twig) Home Page
     */
    public function postSignIn($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email(),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

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

    /**
     * @param  Request Interface
     * @param  Response Interface
     * @return Template HTML(twig) Sign Up Page
     */
    public function getSignUp($request, $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }
    
    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Respect Validation
     * 
     * if data not validated, user will redirect back to Sing Un Page
     * otherwise user will be created using Eloquent Detail of Credential follow:
     * - email
     * - name
     * - password
     *
     * and user will be attempted to Login automaticly using email and password
     * 
     * @return Template HTML(twig) Home Page
     */
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
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            'password_reset' => ''
        ]);

        $this->flash->addMessage('info', 'You have been signed up!');

        $this->auth->attempt($user->email, $request->getParam('password'));

        return $response->withRedirect($this->router->pathFor('auth.home'));
    }
}
