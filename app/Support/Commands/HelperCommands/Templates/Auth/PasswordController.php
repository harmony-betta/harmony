<?php

namespace App\Controllers\Auth;

use App\Models\Users;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Nette\Mail\Message;

class PasswordController extends Controller
{
    /**
     * @param  Request Interface
     * @param  Response Interface
     * @return Template HTML(twig) Change Password Page
     */
    public function getChangePassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Respect Validation
     * 
     * if data not matches, user will redirect back to Change Password Page
     * otherwise user will attempt to Home Page
     * 
     * @return Template HTML(twig) Home Page
     */
    public function postChangePassword($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'old_password' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $this->auth->user()->setPassword($request->getParam('password'));
        $this->flash->addMessage('info', 'Your Password has been updated!');
        return $response->withRedirect($this->router->pathFor('home'));
    }

     /**
     * @param  Request Interface
     * @param  Response Interface
     * @return Template HTML(twig) Forgot Password Page
     */
    public function forgotPassword($request, $response)
    {
        return $this->view->render($response, 'auth/password/forgot-password.twig');
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Existing Credentials on Server
     * 
     * if data email not matches with user credentials, user will redirect back to Forgot Password Page
     * otherwise user will recieved Request Link to Forgot Password.
     * 
     * @return Text (Notification on the top of Form)
     */
    public function postForgotPassword($request, $response)
    {
        $email = $request->getParam('email');
        $token = urlencode(password_hash($email, PASSWORD_DEFAULT));

        $emailExists = Users::where('email', $email)->first();

        if (empty($emailExists)) {
            $this->flash->addMessage('error', 'Your Email does\'t exists');
            return $response->withRedirect($this->router->pathFor('auth.forgot.password'));
        }

        $user = Users::findOrFail($emailExists->id);
        $user->password_reset = $token;
        $user->save();

        $mail = new Message;
        $mail->setFrom('no-reply@harmonyframework.com')
            ->addTo($email)
            ->setSubject('Invitation Email')
            ->setHTMLBody($this->view->fetch('email/forgot-password-email.twig', ["email" => $email, 'token' => $user->password_reset]));
        $this->mailer->send($mail);

        $this->flash->addMessage('info', 'We have been send request password, please check your email');
        return $response->withRedirect($this->router->pathFor('auth.forgot.password'));
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Existing Credentials on Server
     * 
     * if data email not matches with user credentials, user will redirect back to Reset Password Page
     * otherwise user will recieved Request Link to Reset Password via email.
     *
     * @param [text] $email sending form Forgot Password Form
     * @param [text] $token also sending form Forgot Password Form
     * 
     * @return Template HTML(twig) Reset Password Page
     */
    public function resetPassword($request, $response)
    {
        $user = Users::where('email', $request->getParam('email'))->first();
        if ($user->password_reset == urlencode($request->getParam('token'))) {
            
            return $this->view->render($response, 'auth/password/reset-password.twig', ['email' => $request->getParam('email')]);

        } else {
            $this->flash->addMessage('error', 'Sorry your token not verify!');
            return $response->withRedirect($this->router->pathFor('auth.forgot.password'));
        }
    }

    /**
     * @param  Request Interface
     * @param  Response Interface
     *
     * First Data will validate using Statement Control
     * 
     * if data email not matches with user credentials, user will redirect back to Reset Password Page
     * otherwise user password will be changed with new password
     *
     * Recived fillable form form :
     * - New Password
     * - Confirm New Password
     *
     * and compare them. if everything OK, new password will generate, if generate Successfull
     * user will redirect to Sign In Page
     * 
     * @return Template HTML(twig) Sign In Page
     */
    public function postResetPassword($request, $response)
    {
        if ($request->getParam('new_password') !== $request->getParam('cofirm_password')) {
            $this->flash->addMessage('error', 'Your not match');
            return '<script>window.history.back();</script>';
        }

        $user = Users::where('email', $request->getParam('email'))->first();
        $user->password = password_hash($request->getParam('new_password'), PASSWORD_DEFAULT);
        $user->password_reset = '';
        $user->save();

        $this->flash->addMessage('info', 'Your Password has been updated!');
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
}
