<?php

namespace App\Controllers\AuthAdmin;

use App\Models\Admins;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AdminPasswordController extends Controller
{
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'admin/password/change.twig');
    }

    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'old_password' => v::noWhitespace()->notEmpty()->matchesPassword($this->admin->admin()->password),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('admin.password.change'));
        }

        $this->admin->admin()->setPassword($request->getParam('password'));
        $this->flash->addMessage('info', 'Your Password has been updated!');
        return $response->withRedirect($this->router->pathFor('admin.home'));
    }
}
