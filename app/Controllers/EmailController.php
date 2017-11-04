<?php

namespace App\Controllers;

use Respect\Validation\Validator as v;
use Slim\Views\Twig as View;
use App\Models\Users;
use Carbon\Carbon;
use Nette\Mail\Message;

class EmailController extends Controller
{
    /**
     * Method INDEX
     *
     * Ini adalah default dari EmailController
     * @param [object] $request
     * @param [object] $response
     * @return object
     * 
     * Rendering view sesuai routes request
     * routes/
     * |__ web.php
     * 
     * $app->get('/controller/method/params', 'EmailController:index')
     */

     # protected $property = value|blank;

    public function index($request, $response)
    {
        return $this->view->render($response, 'email.twig');
    }

    public function sendEmail($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email(),
            'subject' => v::notEmpty()->alpha(),
            'body' => v::notEmpty(),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('email'));
        }

        // $user = Users::where('email', $request->getParam('email'))->first();

        $mail = new Message;
        $mail->setFrom('harmony@framework.com')
            ->addTo($request->getParam('email'))
            ->setSubject($request->getParam('subject'))
            ->setHTMLBody($this->view->fetch('email/email-template.twig', ["user" => 'Dimas', "email" => $request->getParam('body')]));

        $this->mailer->send($mail);        
            
        return $response->withRedirect($this->router->pathFor('admin.home'));
    }
}