<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function strip_single($tag, $string)
{
    $string = preg_replace('/<' . $tag . '[^>]*>/i', '', $string);
    $string = preg_replace('/<\/' . $tag . '>/i', '', $string);
    return $string;
}
function yaziyi_ayikla($tag, $metin=null)
{

    $string = $tag->C14N();
    $string = strip_tags($string);
    $string = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $string);
    $string = preg_replace('/&#xD;/', '', $string);
    $string = trim(preg_replace('/\s+/', ' ', $string));
    if(!empty($metin)){
        $string = str_replace($metin, '', $string);
    }
    $string = str_replace(':', '', $string);

    return $string;
}
function get_dom(){

}
function get_product_info_table( $url )
{
    libxml_use_internal_errors(true );

    if( isset($_POST['list_id']) && !empty($_POST['list_id']) ) {

        $content = file_get_contents( $url );
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->strictErrorChecking = false;
        $dom->loadHTML($content);

        // Ürün listesinde yer alan ürün linklerini çekmek için
        $xPath = new DOMXPath( $dom );
        $anchorClass    = $_POST['anchor_class'];
        $anchorTags     = $xPath->evaluate("//div[@class=\"$anchorClass\"]//a/@href");
        $kat_name       = $dom->getElementsByTagName('h1')->item(0)->nodeValue;

        if( !file_exists( 'products/'.$kat_name ) ){
            mkdir( 'products/'.$kat_name );
        }

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getActiveSheet()->setCellValue('A1','ÜRÜN ADI');
        $spreadsheet->getActiveSheet()->setCellValue('B1','ÜRÜN FİYATI');
        $spreadsheet->getActiveSheet()->setCellValue('C1','ÜRÜN KATEGORİSİ');
        $spreadsheet->getActiveSheet()->setCellValue('D1','ÜRÜN MARKASI');
        $spreadsheet->getActiveSheet()->setCellValue('E1','STOK KODU');
        $spreadsheet->getActiveSheet()->setCellValue('F1','GARANTİ SÜRESİ');
        $spreadsheet->getActiveSheet()->setCellValue('G1','ÜRÜN SEO BAŞLIK');
        $spreadsheet->getActiveSheet()->setCellValue('H1','ÜRÜN TABLO KODU');

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);


        $i=1;
        foreach ($anchorTags  as $anchorTag) {
            $i++;
            $parse_url = parse_url( $url ) ;
            $product_link = $parse_url['scheme']."://".$parse_url['host'].$anchorTag->nodeValue;;

            if ( isset($_POST['product_table_id']) && !empty($_POST['product_table_id']) ) {

                $content2 = file_get_contents($product_link);
                $dom2 = new DOMDocument();
                $dom2->preserveWhiteSpace = false;
                $dom2->strictErrorChecking = false;
                $dom2->loadHTML($content2);

                $product_urun_adi               = $dom2->getElementById( 'urun_adi' );
                $product_urun_indirimli_fiyati  = $dom2->getElementById( 'indirimli_cevrilmis_fiyat' );
                $product_urun_kategorisi        = $dom2->getElementById( 'etiketler_tip_2' );
                $product_urun_marka             = $dom2->getElementById( 'etiketler_tip_3' );
                $product_urun_stok              = $dom2->getElementById( 'stok_kodu' );
                $product_urun_garanti           = $dom2->getElementById( 'garanti' );
                $product_table                  = $dom2->getElementById( $_POST['product_table_id'] );
                $images                         = $dom2->getElementById( 'pImageLink' );

                $urun_tablosu           = NULL;
                $urun_adi               = NULL;
                $urun_indirimli         = NULL;
                $urun_kat               = NULL;
                $urun_marka             = NULL;
                $urun_stok              = NULL;
                $urun_garanti           = NULL;

                if ($product_table) {
                    $urun_tablosu = $product_table->C14N();
                    $urun_tablosu = strip_single('tr', $urun_tablosu);
                    $urun_tablosu = strip_single('span', $urun_tablosu);
                    $urun_tablosu = strip_single('a', $urun_tablosu);
                    $urun_tablosu = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i", '<$1$2>', $urun_tablosu);
                    $urun_tablosu = preg_replace('/&#xD;/', '', $urun_tablosu);
                    $urun_tablosu = str_replace(':', '', $urun_tablosu);
                    $urun_tablosu = str_replace('<div>', '<div class="content-scraper">', $urun_tablosu);
                }

                if ($product_urun_adi) {
                    $urun_adi = yaziyi_ayikla($product_urun_adi,'Arçelik ');
                }

                if ($product_urun_indirimli_fiyati) {
                    $urun_indirimli = yaziyi_ayikla($product_urun_indirimli_fiyati,'İndirimli Fiyat ');
                    $urun_indirimli = str_replace('(KDV DAHİL)', '', $urun_indirimli);
                }

                if ($product_urun_kategorisi) {
                    $urun_kat = yaziyi_ayikla($product_urun_kategorisi,'Kategori ');
                }

                if ($product_urun_marka) {
                    $urun_marka = yaziyi_ayikla($product_urun_marka,'Marka ');
                }

                if ($product_urun_stok) {
                    $urun_stok = yaziyi_ayikla($product_urun_stok,'Stok Kodu ');
                }

                if ($product_urun_garanti) {
                    $urun_garanti = yaziyi_ayikla($product_urun_garanti,'Garanti Süresi ');
                }


                // Ürün adını
                $spreadsheet->getActiveSheet()->setCellValue('A'.$i,$urun_adi);
                // Ürün fiyatı
                $spreadsheet->getActiveSheet()->setCellValue('B'.$i,$urun_indirimli);
                // Ürün kategorisi
                $spreadsheet->getActiveSheet()->setCellValue('C'.$i,$urun_kat);
                // Ürün markası
                $spreadsheet->getActiveSheet()->setCellValue('D'.$i,$urun_marka);
                // Ürün stok kodu
                $spreadsheet->getActiveSheet()->setCellValue('E'.$i,$urun_stok);
                // Ürün garanti süresi
                $spreadsheet->getActiveSheet()->setCellValue('F'.$i,$urun_garanti);
                // Ürün SEO açıklamasını yaz
                $spreadsheet->getActiveSheet()->setCellValue('G'.$i,$urun_adi.', '.$urun_marka.', '.$urun_kat);
                // Ürün tablosu HTML kodunu yaz
                $spreadsheet->getActiveSheet()->setCellValue('H'.$i,$urun_tablosu);

                $xPathx = new DOMXPath( $dom2 );
                $anchorClassx = 'productImages';
                $anchorTagsx = $xPathx->evaluate("//div[@class=\"$anchorClassx\"]//a/@lightbox-link");

                $im = 0;
                foreach ( $anchorTagsx  as $anchorTagx ) {
                    $im++;
                    $imagelink = 'http://'.parse_url( $anchorTagx->nodeValue, PHP_URL_HOST ) . parse_url( $anchorTagx->nodeValue, PHP_URL_PATH );
                    $imagename = pathinfo($imagelink)['basename'];

                    $urunklasoru = 'products/' .$kat_name. '/images/'.$urun_adi;

                    if( !file_exists( $urunklasoru ) ){
                        mkdir( $urunklasoru );
                    }

                    $img = $urunklasoru . '/' . $imagename;

                    $fp = fopen( $img, "w" );
                    fwrite( $fp, file_get_contents( $imagelink ) );
                    fclose( $fp );
                }

            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('products/'.$kat_name."/Ürün Listesi.xlsx");

        //return $result;
        return '<p class="alert alert-success">Ürünler için Excel dosyaları oluşturuldu.</p>';
    }
}
