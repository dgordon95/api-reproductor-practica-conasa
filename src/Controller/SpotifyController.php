<?php
// src/Controller/SpotifyController.php
namespace App\Controller;

use App\Utils\SpotifyService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;

class SpotifyController
{
    /**
     * @Route("/spotify/token")
     */
    public function getToken(SpotifyService $spotifyService)
    {
        try{
            $token = $spotifyService->getTokenService();
        }
        catch(\Exception $e){
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
         }
        return new JsonResponse($token);       
        
    }


    /**
     * @Route("/spotify/artist/{artist}")
     */
    public function searchArtist($artist,SpotifyService $spotifyService)
    {
        try{
            $artist = $spotifyService->getArtistService($artist);
        }
        catch(\Exception $e){
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
         }
        return $artist;
    }
/**https://api.spotify.com/v1/artists/7dGJo4pcD2V6oG8kP0tJRR/albums?market=ES&limit=1&access_token=BQAYWnHnmH9Ko33FCvJawIOKvG3gMpsSiL24U9gi-4GgimIhYP0qaZyNDYM_24hLseux83PTIXSpK4FvCK8 */

    /**
     * @Route("/spotify/artists/{id}/albums")
     */
    public function getArtistAlbum($id,SpotifyService $spotifyService)
    {

        try {
           $album = $spotifyService->getArtistAlbumService($id);
  
        } catch (\Exception $exception) {
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
        }
        return $album;      
    }

    /**
     * @Route("/spotify/artists/{id}/top-tracks")
     */
    public function getArtistTopTracks($id,SpotifyService $spotifyService){
           try {
           $tracks = $spotifyService->getAtristTopTracksService($id);
  
        } catch (\Exception $exception) {
            $logger->error($e->getMessage());
            return new JsonResponse(['error' => $translator->trans('api.user.catch_error')],400);
        }
        return $tracks; 

    }
}