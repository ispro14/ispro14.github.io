<?php
include ROOT.'/models/Order.php';
include ROOT.'/models/User.php';
include ROOT.'/models/Cart.php';
include ROOT.'/models/Category.php';
include ROOT.'/models/Product.php';

class CabinetController
{
    public function actionIndex() {
        
        $userId = User::checkLogged();
        
        $user = User::getUserById($userId);
        
        require_once (ROOT. '/views/cabinet/index.php');
        
        return true;
    }
    
    public function actionEdit() {
        
        $userId = User::checkLogged();
        
        $user = User::getUserById($userId);
        
        $name = $user['name'];
        $password = $user['password'];
        
        if (isset($_POST['submit'])){
            $name = $_POST['name'];
            $password = $_POST['password'];


            $errors[] = false;
            
            $result = '';

            if (!User::checkName($name)){
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }

            if (!User::checkPassword($password)){
                $errors[] = 'Пароль должен быть не короче 6 символов';
            }
            
            if (User::checkName($name) == true && User::checkPassword($password)==true){
                $result = User::edit($userId, $name, $password);
            }
        }
         
        
        require_once (ROOT. '/views/cabinet/edit.php');
        
        return true;
        
    }
    
}
