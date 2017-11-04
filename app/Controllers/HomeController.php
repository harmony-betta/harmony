<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use App\Models\Admins;
use App\Support\Filesystem\Storage;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        return $this->view->render($response, 'home.twig');
    }

    public function upload($request, $response)
    {
        $directory = dirname(dirname(__DIR__)).'/storage/public';
        $count = 1;
        
        $uploadedFiles = $request->getUploadedFiles();
    
        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['example1'];
        if ($uploadedFile->getError() === 0) {
            $filename = Storage::storeAs($directory, 'image', $uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }
        
        // handle multiple inputs with the same key
        foreach ($uploadedFiles['example2'] as $uploadedFile) {
            if ($uploadedFile->getError() === 0) {
                $filename = Storage::storeAs($directory, 'image_' .$count++, $uploadedFile);
                $response->write('uploaded ' . $filename . '<br/>');
            }
        }

        // handle single input with multiple file uploads
        foreach ($uploadedFiles['example3'] as $uploadedFile) {
            if ($uploadedFile->getError() === 0) {
                $filename = Storage::store($directory, $uploadedFile);
                $response->write('uploaded ' . $filename . '<br/>');
            }
        }
    }
    
}
