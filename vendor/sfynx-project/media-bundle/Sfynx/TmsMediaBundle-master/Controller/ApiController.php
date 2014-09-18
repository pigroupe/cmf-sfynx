<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tms\Bundle\MediaBundle\Entity\Media;
use Tms\Bundle\MediaBundle\Exception\MediaAlreadyExistException;
use Tms\Bundle\MediaBundle\Exception\NoMatchedStorageMapperException;
use Tms\Bundle\MediaBundle\Exception\MediaNotFoundException;
use Tms\Bundle\MediaBundle\Exception\NoMatchedTransformerException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ApiController extends Controller
{
    /**
     * Post
     *
     * @param Request $request
     * @Route("/media")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {
        $response = new Response();
        try {
            $media = $this->get('tms_media.manager.media')->addMedia(
                $request->files->get('media'),
                sprintf('[%s] %s',
                    $request->getClientIp(),
                    $request->request->get('source', null)
                ),
                $request->request->get('name', null),
                $request->request->get('description', null)
            );

            $response->setStatusCode(201);
            $response->setContent(json_encode(array_merge(
                $media->toArray(),
                array('publicUri' => $this->get('tms_media.manager.media')->getMediaPublicUri($media))
            )));
        } catch (MediaAlreadyExistException $e) {
            $response->setStatusCode(400);
            $response->setContent($e->getMessage());
        } catch (NoMatchedStorageMapperException $e) {
            $response->setStatusCode(415);
            $response->setContent($e->getMessage());
        }  catch (FileException $e) {
            $response->setStatusCode(413);
            $response->setContent($e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(418);
            $response->setContent($e->getMessage());
        }

        return $response;
    }

    /**
     * Delete
     *
     * @param Request $request
     * @param string $reference
     * @Route("/media/{reference}")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $reference)
    {
        $response = new Response();
        try {
            $this->get('tms_media.manager.media')->deleteMedia($reference);
            $response->setStatusCode(204);
        } catch (\Exception $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
        }

        return $response;
    }

    /**
     * Get
     *
     * @param Request $request
     * @param string $reference
     * @Route("/media/{reference}.{_format}", defaults={"_format"=null})
     * @Route("/media/{reference}")
     * @Method({"GET"})
     */
    public function getAction(Request $request, $reference, $_format)
    {
        $response = new Response();
        try {
            $media = $this->get('tms_media.manager.media')->retrieveMedia($reference);
            $responseMedia = $this->get('tms_media.manager.media')->transform(
                $media,
                array_merge(
                    $request->query->all(),
                    array('format' => $request->getRequestFormat())
                )
            );

            $response->setPublic();
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', $responseMedia->getContentType());
            $response->headers->set('Content-Length', $responseMedia->getContentLength());
            $response->setETag($responseMedia->getETag());
            $response->setLastModified($responseMedia->getLastModifiedAt());
            $response->setContent($responseMedia->getContent());
/*
            $response->setExpires($responseMedia->getExpires());
            $response->setMaxAge($responseMedia->getMaxAge());
            $response->setSharedMaxAge($responseMedia->getSharedMaxAge());
*/
        } catch (MediaNotFoundException $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
        } catch (NoMatchedTransformerException $e) {
            $response->setStatusCode(404);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
        } catch (\Exception $e) {
            $response->setStatusCode(503);
            $response->setContent($e->getMessage());
            $response->headers->set('Content-Type', 'text/html');
        }

        return $response;
    }

    /**
     * GetBinary
     *
     * @param Request $request
     * @param string $reference
     * @Route("/media/{reference}/{_format}.bin", defaults={"_format"=null})
     * @Method({"GET"})
     */
    public function getBinaryAction(Request $request, $reference, $_format)
    {
        $response = $this->getAction($request, $reference, $_format);
        if ($response->getStatusCode() == 200) {
            $response->headers->set('Content-Type', 'application/octet-stream');
        }

        return $response;
    }

    /**
     * GetEndpoint
     *
     * @param Request $request
     * @Route("/endpoint.{_format}", defaults={"_format"="json"})
     * @Method({"GET"})
     */
    public function getEndpointAction(Request $request, $_format)
    {
        $data = array(
            'publicEndpoint' => $this->get('tms_media.manager.media')->getApiPublicEndpoint()
        );

        $response = new Response();
        $response->setPublic();
        $response->setStatusCode(200);
        // Cache for one year
        $response->setMaxAge(31536000);
        $response->setSharedMaxAge(31536000);

        if ($_format == 'json') {
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($data));
        } elseif ($_format == 'xml') {
            $xml = new \SimpleXMLElement('<root/>');
            $data = array_flip($data);
            array_walk_recursive($data, array($xml, 'addChild'));
            $response->headers->set('Content-Type', 'text/xml');
            $response->setContent($xml->asXML());
        }

        return $response;
    }
}
