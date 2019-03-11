<?php
include ROOT.'/models/Order.php';
include ROOT.'/models/User.php';
include ROOT.'/models/Cart.php';
include ROOT.'/models/Category.php';
include ROOT.'/models/Product.php';
class CartController
{
    public function actionAdd($id)
    {
        Cart::addProduct($id);
        
        $referer = $_SERVER['HTTP_REFERER'];
        
        header('Location: '. $referer);
    }
    
    public function actionAddAjax($id) {
        
        echo Cart::addProduct($id);
        return true;
    }
    
    public function actionIndex() {
        
        $categories = array();
        $categories = Category::getCategoryList();
        
        $productsInCart = false;
        
        $productsInCart = Cart::getProducts();
        
        if ($productsInCart){
            $productsIds = array_keys($productsInCart);
            $products = Product::getProdustsByIds($productsIds);
            
            $totalPrice = Cart::getTotalPrice($products);
        }
        
        require_once (ROOT. '/views/cart/index.php');
        
        return true;
        
    }
    
    public function actionCheckout()
    {
        $categories = array();
        $categories = Category::getCategoryList();


        // статус успешного оформления заказа
        $result = false;


        // Форма отправлена?
        if (isset($_POST['submit'])) {
            // Да
            // Считываем данные формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            // Валидация полей
            $errors = false;
            if (!User::checkName($userName))
                $errors[] = 'Неправильное имя';
            if (!User::checkPhone($userPhone))
                $errors[] = 'Некорректный телефон';

            // Форма заполненна корректно?
            if ($errors == false) {
                // Да
                // Сохраняем заказ в базе
                // Собираем инфу о заказе
                $productsInCart = Cart::getProducts();
                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }

                // сохраняем заказ в бд
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // отправка сообщения о заказе админу            
                    $adminEmail = 'bis03@mail.ru';
                    $message = '...';
                    $subject = 'Новый заказ';
                    mail($adminEmail, $subject, $message);  

                    // Очищаем корзину
                    Cart::clear();
                }
            } else {
                // Форма заполнена некорректно
                $productsInCart = Cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = Product::getProdustsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }
        } else {
            // Форма не отправлена
            // данные из корзины    
            $productsInCart = Cart::getProducts();

            if ($productsInCart == false) {
                header("Location: /");
            } else {
                $productsIds = array_keys($productsInCart);
                $products = Product::getProdustsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();


                $userName = false;
                $userPhone = false;
                $userComment = false;

                if (User::isGuest()) {
                } else {
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    $userName = $user['name'];
                }
            }
        }

        require_once(ROOT . '/views/cart/checkout.php');

        return true;
    }  
}