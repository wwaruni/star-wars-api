<?php

namespace CodePractise\Service\V1\StarWarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AbstractController extends Controller
{
    /**
     * Format Response
     * @param mixed $data
     * @param int $statusCode
     * @param string $eTag
     * @return Response
     */
    protected function formatResponse($data = null, $statusCode = null, $eTag = null)
    {
        $request           = $this->get('request_stack')->getCurrentRequest();
        $response          = new Response();
        $contentType       = 'application/json';
        $headerContentType = $request->headers->get('content_type');

        // Set status code
        if ( !empty($statusCode) ) {
            $response->setStatusCode($statusCode);
        }

        // Set header content
        if ( !empty($headerContentType) ) {
            $contentType  = $headerContentType;
        }
        // Set data
        if ( !empty($data) ) {
            $response->setContent($data);
        }

        // set last modified
        if ( !empty($eTag) ) {
            $response->setETag($eTag);
        }

        $response->headers->set('Content-Type', $contentType);

        return $response;
    }

}
