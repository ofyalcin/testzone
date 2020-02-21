<?php
require './vendor/autoload.php';
use Goutte\Client;

function get_products( $productsArchiveUrl, $productsAnchor ){

        $client = new Client();
        $crawler = $client->request('GET', $productsArchiveUrl);

        $links = $crawler->filter( '.'.$productsAnchor. ' a' )->each(function($node) {
            $href  = $node->attr('href');
            $title = $node->attr('title');
            $text  = $node->text();

            return compact('href', 'title', 'text');
        });

        return $links;
}

function get_products_details( $links ){

        $client = new Client();


        $links = $crawler->filter( $productsAnchor. ' a' )->each(function($node) {
            $href  = $node->attr('href');
            $title = $node->attr('title');
            $text  = $node->text();

            return compact('href', 'title', 'text');
        });

        $i = 0;
        foreach ( $links as $link ) {
            $i++;
            echo $i.' - <a href="'.$link['href'].'">'.$link['text'].'</a><br>';
        }

        return $links;
}


function create_product_files( $links ){

        $client = new Client();
        $crawler = $client->request('GET', $productsArchiveUrl);

        $links = $crawler->filter( $productsAnchor. ' a' )->each(function($node) {
            $href  = $node->attr('href');
            $title = $node->attr('title');
            $text  = $node->text();

            return compact('href', 'title', 'text');
        });

        $i = 0;
        foreach ( $links as $link ) {
            $i++;
            echo $i.' - <a href="'.$link['href'].'">'.$link['text'].'</a><br>';
        }

        return $links;
}
