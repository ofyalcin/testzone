<?php
header("Content-type:text/html; charset=utf-8");
require 'functions.php';
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
                        <input type="text" name="link" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ürün sayfasında yer alan ürün listesine ait ID girin</label>
                        <input type="text" name="list_id" class="form-control" required value="showLabelPageItems">
                    </div>
                    <div class="form-group">
                        <label>Sayfada yer alan ürün linki sınıfını girin (class)</label>
                        <input type="text" name="anchor_class" class="form-control" required value="showcaseTitle">
                    </div>
                    <div class="form-group">
                        <label>Ürün sayfasında yer alan ürün tablosuna ait ID girin</label>
                        <input type="text" name="product_table_id" class="form-control" required value="_productDetailFeatures">
                    </div>
                    <button type="submit" class="btn btn-primary">Excel Oluştur</button>
                </form>
                <?php
                if( isset($_POST['link']) && !empty($_POST['link']) && isset($_POST['list_id']) && !empty($_POST['list_id']) ){

                    $link = $_POST['link'];
                    echo get_product_info_table( $link );

                }else{
                    echo '<p class="alert alert-info">Ürün listesini taramak ve Excel dosyaları oluşturmak için zorunlu bilgileri doldurun.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
