<?php
include ROOT.'/models/Category.php';
include ROOT.'/models/Cart.php';
include ROOT.'/models/Product.php';
include ROOT.'/models/User.php';
class ProductController
{
    
    public function actionView($productId)
    {
        $categories = array();
        $categories = Category::getCategoryList();
        
        $product = Product::getProductsById($productId);
        require_once (ROOT. '/views/product/view.php');
        return true;
    }
}

