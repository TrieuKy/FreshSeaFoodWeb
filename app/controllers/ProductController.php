<?php
class ProductController {
    public function index() {
        require_once 'app/models/ProductModel.php';
        $keyword  = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';
        $sort     = $_GET['sort'] ?? '';

        if ($keyword || $category || $minPrice || $maxPrice || $sort) {
            $products = ProductModel::search($keyword, $category, $minPrice, $maxPrice, $sort);
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

        $reviews = ProductModel::getReviews($id);
        
        $canReview = false;
        $orderIdToReview = null;
        if (isset($_SESSION['user_id'])) {
            $orderIdToReview = ProductModel::canUserReview($_SESSION['user_id'], $id);
            if ($orderIdToReview) {
                $canReview = true;
            }
        }

        include 'app/views/product/detail.php';
    }
}
?>