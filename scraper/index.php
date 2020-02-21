<?php
require 'inc/functions.php'
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Oluşturucu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container vh-100">
    <div class="row h-100">
        <div class="col-12 d-flex flex-column justify-content-center">
            <form action="" method="post" class="mb-5">
                <div class="form-group">
                    <label>Ürünlerin bulunduğu sayfa linkini girin</label>
                    <input type="text" name="archive_link" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Sayfada yer alan ürün linkini bir üst sınıfını girin</label>
                    <input type="text" name="anchor_class" class="form-control" required value="showcaseTitle" placeholder="Örneğin: showcaseTitle">
                </div>
                <button type="submit" class="btn btn-primary">Excel Oluştur</button>
            </form>
            <?php
            if( isset($_POST['archive_link']) && !empty($_POST['archive_link']) && isset($_POST['anchor_class']) && !empty($_POST['anchor_class']) ){

                $products = get_products( $_POST['archive_link'], $_POST['anchor_class'] );
                $product_details = get_products_details( $products );

                $i = 0;
                foreach ( $products as $link ) {
                    $i++;
                    echo $i.' - <a href="'.$link['href'].'">'.$link['text'].'</a><br>';
                }

            }else{
                echo '<p class="alert alert-info">Ürün listesini taramak ve Excel dosyaları oluşturmak için zorunlu bilgileri doldurun.</p>';
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
