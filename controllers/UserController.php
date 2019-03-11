<?php

include ROOT.'/models/Cart.php';
include ROOT.'/models/User.php';

class UserController 
{
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';

        if (isset($_POST['submit'])){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];


            $errors[] = false;
            
            $result = '';

            if (!User::checkName($name)){
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }

            if (!User::checkEmail($email)){
                $errors[] = 'Вы ввели неправильный Email';
            }

            if (!User::checkPassword($password)){
                $errors[] = 'Пароль должен быть не короче 6 символов';
            }

            if (User::checkEmailExists($email)){
                $errors[] = 'Такой Email уже используется';
            }

            if (User::checkName($name) == true && User::checkEmail($email)==true 
                    && User::checkPassword($password)==true && User::checkEmailExists($email)==false){
                $result = User::register($name, $email, $password);
            }
        }
        
        require_once (ROOT.'/views/user/register.php');
        
        return true;
    }
    
    public function actionLogin()
    {
         $email = '';
        $password = '';

        if (isset($_POST['submit'])){
            $email = $_POST['email'];
            $password = $_POST['password'];


            $errors[] = false;
            
            $result = '';
            
            if (!User::checkEmail($email)){
                $errors[] = 'Вы ввели неправильный Email';
            }

            if (!User::checkPassword($password)){
                $errors[] = 'Пароль должен быть не короче 6 символов';
            }
            
            $userId = User::checkUserData($email,$password);

            if ($userId==false){
                $errors[] = 'Неверные данные для входа на сайт';
                
            } else {
                User::auth($userId);
                
                header("Location: /cabinet/");
            }

           
        }
        
        require_once (ROOT.'/views/user/login.php');
        
        return true;
    }
    
    public function actionLogout() {
        session_start();
        unset($_SESSION["user"]);
        header("Location: /");
        
    }
}