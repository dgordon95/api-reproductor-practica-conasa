<?php
// src/Controller/SpotifyController.php
namespace App\Controller;

use App\Utils\SpotifyService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\BadResponseException;
use FOS\RestBundle\Controller\FOSRestController;

class SpotifyController extends FOSRestController
{
    
    /**
     * Recibir token
     *
     * Este método permite conseguir un token de acceso.
     *
     * @Route("/api/spotify/token", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el token necesario  para hacer peticiones a Spotify-Api",
     * )
     * @SWG\Parameter(
     *     name="artist",
     *     in="path",
     *     type="string",
     *     description="Nombre del artista"
     * )
     * @SWG\Tag(name="Spotify")
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
     * Busar artista
     *
     * Este método permite buscar artistas en Spotify.
     *
     * @Route("/api/spotify/artist/{artist}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra todos los datos de un usuario de Spotify buscado por nombre.",
     * )
     * @SWG\Parameter(
     *     name="artist",
     *     in="path",
     *     type="string",
     *     description="Nombre del artista"
     * )
     * @SWG\Tag(name="Spotify")
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
    
    /**
     * Busar ablbum
     *
     * Este método permite buscar el ultimo album de un artistas en Spotify.
     *
     * @Route("/api/spotify/artists/{id}/albums",methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra todos los datos de el ultimo album de un usuario de Spotify.",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="Id del artista"
     * )
     * @SWG\Tag(name="Spotify")
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
     * Busar ablbum
     *
     * Este método permite buscar los temas mas conocidos de un artista de Spotify.
     *
     * @Route("/api/spotify/artists/{id}/top-tracks",methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Muestra los 10 temas mas reproducidos de un usuario de Spotify.",
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="Id del artista"
     * )
     * @SWG\Tag(name="Spotify")
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