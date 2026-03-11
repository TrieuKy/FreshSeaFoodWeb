<?php
class ProductController {
    public function index() {
        require_once 'app/models/ProductModel.php';
        $keyword  = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';

        if ($keyword || $category || $minPrice || $maxPrice) {
            $products = ProductModel::search($keyword, $category, $minPrice, $maxPrice);
        } else {
            $products = ProductModel::getAll();
        }

        include 'app/views/product/list.php';
    }

    public function detail() {
        require_once 'app/models/ProductModel.php';
        $args = func_get_args();
        $id = intval($args[0] ?? 0);

        if (!$id) {
            header('Location: /banhaisan');
            return;
        }

        $product  = ProductModel::getById($id);
        $related  = ProductModel::getAll();

        if (!$product) {
            header('Location: /banhaisan');
            return;
        }

        include 'app/views/product/detail.php';
    }
}
?>