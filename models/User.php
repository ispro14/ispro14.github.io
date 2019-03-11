<?php

class User
{
        
    public static function checkName($name) {
        if (strlen($name)>=2){
            return true;
        }
        return false;
    }
    
    public static function checkPassword($password) {
        if (strlen($password)>=6){
            return TRUE;
        }
        return false;
    }
    
    public static function checkPhone($userPhone) {
        if (strlen($userPhone)>=10){
            return TRUE;
        }
        return false;
    }
    
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    
    public static function checkEmailExists($email) {
        
        require ROOT. '/config/connect.php';
        
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';
        
        $result = $pdo->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();
        
        if ($result->fetchColumn()){
            return true;
        }
        return false;
    }
    
     public static function register($name, $email, $password){
        require ROOT. '/config/connect.php';
        
        $sql = 'INSERT INTO user (name, email, password)'
                . 'VALUES (:name, :email, :password)';
        
        $result = $pdo->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        
        return $result->execute();
     }
    
     public static function checkUserData($email, $password){
        require ROOT. '/config/connect.php';
        
        $sql = 'SELECT * FROM user WHERE email = :email and password = :password';
        
        $result = $pdo->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        
        $user = $result->fetch();
        if($user){
            return $user['id'];
        }
        
        return false;
     }
     
     public static function auth($userId){
         $_SESSION['user'] = $userId;
     }
     
     public static function checkLogged(){
         
         if (isset($_SESSION['user'])){
             return $_SESSION['user'];
         }
         
         header("Location: /user/login");
         
     }
     
     
     public static function isGuest(){
         
         if (isset($_SESSION['user'])){
             return false;
         } else {
             return true;             
         }
     }
     
     public static function getUserById($userId){
        $userId = intval($userId);
        
        if ($userId) {
            require ROOT. '/config/connect.php';
        
            $sql = 'SELECT * FROM user WHERE id = :id';
            $result = $pdo->prepare($sql);
            $result->bindParam(':id', $userId, PDO::PARAM_INT);
            
            
            $result->setFetchMode (PDO::FETCH_ASSOC);
            $result->execute();
            
            return $result->fetch();
        }
    }
    
    public static function edit($userId, $name, $password)
    {
        require ROOT. '/config/connect.php';
        
        $sql = 'UPDATE user'
                . 'SET name = :name, password = :password'
                . 'WHERE id = :id';
        
        $result = $pdo->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':id', $userId, PDO::PARAM_STR);
        
        
        return $result->execute();
    }
}