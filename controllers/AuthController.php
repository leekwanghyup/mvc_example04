<?php 
namespace app\controllers;

use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('auth');
    }

    public function login(Request $request)
    {   
        
        if($request->isPost()){
            return "Data Handle";
        }
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel(); 
        if($request->isPost()){
            $registerModel->dataload($request->getBody());
            

            if($registerModel->validate() && $registerModel->register()){
                return "Success";
            }
            return $this->render('register',['model'=>$registerModel]);
        }
        return $this->render('register',['model'=>$registerModel]);
    }

}