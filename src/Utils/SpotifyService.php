<?php

namespace App\Utils;

use App\Utils\SpotifyService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;


class SpotifyService
{
    public function getTokenService() 
    {
        $client_id = '3a5ac347d15940b9b92dcbc9c98e27bf'; 
        $client_secret = '0eae6c25f62e4208becf35db761d8f0e'; 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            'https://accounts.spotify.com/api/token' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     'grant_type=client_credentials' ); 
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret))); 

        $result=curl_exec($ch);
        dump($ch);
        
        $result = str_replace('\n', '', $result);
        $result = rtrim($result, ',');
        $result = "[" . trim($result) . "]";
        $token = $porciones = explode('"', $result);
        return $token[3];       
    }

    public function getArtistService($artist)
    {
        $client = new \GuzzleHttp\Client([
        // Base URI is used with relative requests
        'base_uri' => $_ENV['API_SPOTIFY_URL'].'/search',
        // You can set any number of default request options.
        'timeout'  => 2.0,
        ]);
            
        $token = $this->getTokenService();       
                
                
        $response  =  $client -> request ( 'GET' ,$_ENV['API_SPOTIFY_URL'].'/search',['query' => ['q'  =>  $artist,'type' => 'artist',
                                                                                                        'limit' => '1','access_token' => $token]
                                                                                                        ]); 
        $contents = (string) $response->getBody();
            
        $contents = str_replace('\n', '', $contents);
        $contents = rtrim($contents, ',');
        $json = json_decode($contents, true);
        return new JsonResponse($json);
        }

    public function getArtistAlbumService($id){

        $client = new \GuzzleHttp\Client([
        // Base URI is used with relative requests
        'base_uri' => $_ENV['API_SPOTIFY_URL'].'/artists/'.$id.'/albums',
        // You can set any number of default request options.
        'timeout'  => 2.0,
        ]);
            
        $token = $this->getTokenService();        
            $response  =  $client -> request('GET' ,
                        $_ENV['API_SPOTIFY_URL'].'/artists/'.$id.'/albums',
                        ['query' => ['limit' => '1','access_token' => $token]
            ]); 
        $contents = (string) $response->getBody();
            
        $contents = str_replace('\n', '', $contents);
        $contents = rtrim($contents, ',');
        $json = json_decode($contents, true);
        return new JsonResponse($json);
    }
    
    public function getAtristTopTracksService($id){
         $client = new \GuzzleHttp\Client([
        // Base URI is used with relative requests
        'base_uri' => $_ENV['API_SPOTIFY_URL'].'/artists/'.$id.'/top-tracks',
        // You can set any number of default request options.
        'timeout'  => 2.0,
        ]);
            
         $token = $this->getTokenService();       
            $response  =  $client -> request('GET' ,
                        $_ENV['API_SPOTIFY_URL'].'/artists/'.$id.'/top-tracks',
                        ['query' => ['country' => 'ES','access_token' => $token]
            ]); 
        $contents = (string) $response->getBody();
            
        $contents = str_replace('\n', '', $contents);
        $contents = rtrim($contents, ',');
        $json = json_decode($contents, true);
        return new JsonResponse($json);

    }
}