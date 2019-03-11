<?php

class Product
{
    const SHOW_BY_DEFAULT = 9;

    public static function getLatestProducts($count=self::SHOW_BY_DEFAULT, $page=1){
        require ROOT. '/config/connect.php';
        
        $count = intval($count);
        
        
        $result = $pdo->query('SELECT id, name, price, image, is_new '
                . 'FROM product '
                . 'WHERE status= "1" '
                . 'ORDER BY id DESC '
                . 'LIMIT '. $count);

        $productsList = array();
        $i = 0;
        
        while ($row = $result->fetch()) {
            $productsList [$i]['id'] = $row['id'];
            $productsList [$i]['name'] = $row['name'];
            $productsList [$i]['image'] = $row['image'];
            $productsList [$i]['price'] = $row['price'];
            $productsList [$i]['is_new'] = $row['is_new'];            
            $i++;            
        }
        
        return $productsList;
    }
    public static function getProductsListByCategory($categoryId = false, $page=1) {
        
        if ($categoryId){
            $page = intval($page);
            $offset = ($page - 1)*self::SHOW_BY_DEFAULT;
            
            require ROOT. '/config/connect.php';
        
            $result = $pdo->query('SELECT id, name, price, image, is_new '
                    . 'FROM product '
                    . 'WHERE status= "1" AND category_id = '.$categoryId.' '
                    . 'ORDER BY id DESC '
                    . 'LIMIT '. self::SHOW_BY_DEFAULT
                    . ' OFFSET '.$offset);

            $products = array();
            $i = 0;

            while ($row = $result->fetch()) {
                $products [$i]['id'] = $row['id'];
                $products [$i]['name'] = $row['name'];
                $products [$i]['image'] = $row['image'];
                $products [$i]['price'] = $row['price'];
                $products [$i]['is_new'] = $row['is_new'];            
                $i++;            
            }

            return $products;
        }
    }
    public static function getProdustsByIds($idsArray)
    {        
        $products = array();
        
        require ROOT. '/config/connect.php';
        
        $idsString = implode(',', $idsArray);

        $sql = "SELECT * FROM product WHERE status='1' AND id IN ($idsString)";

        $result = $pdo->query($sql);        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }

        return $products;
    }
    public static function getTotalProductsInCategory($categoryId){
        
        require ROOT. '/config/connect.php';

        $result = $pdo->query('SELECT count(id) AS count FROM product '
                . 'WHERE status ="1" AND category_id ='.$categoryId);
        $result->setFetchMode (PDO::FETCH_ASSOC);
        $row = $result->fetch();
        return $row;
        
    }
}
