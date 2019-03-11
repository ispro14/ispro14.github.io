<?php
include ROOT.'/models/Category.php';
include ROOT.'/models/Product.php';
include ROOT.'/models/User.php';
include ROOT.'/models/Cart.php';
class SiteController 
{
    public function actionIndex()
    {
        $categories = array();
        $categories = Category::getCategoryList();
        
        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(6);
        
        
        require_once (ROOT. '/views/site/index.php');
        return true;
    }
    public function actionContact()
    {
        $userEmail = '';
        $userText = '';
        $result = false;
        
        if (isset($_POST['submit'])) {
            
            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];
            
            $errors = false;
           
            
            if (!User::checkEmail($userEmail)){
                $errors[] = 'Вы ввели неправильный Email';
            }
            
            if ($errors == false){
                
                $adminEmail = 'bis03@mail.ru';
                $message = 'Текст сообщения: {$userText}. От {$userEmail}';
                $suggest = 'Обратная связь с сайта';
                $result = mail($suggest, $message, $adminEmail);
                $result = true;
            }
            
        }
        
        require_once (ROOT. '/views/site/contact.php');
        return true;
    }
}

