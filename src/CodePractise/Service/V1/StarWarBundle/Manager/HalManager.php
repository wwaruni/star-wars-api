<?php 

namespace CodePractise\Service\V1\StarWarBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HalManager
{
    /**
     * @var Router
     */
    protected $router;
    protected $serializer;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(Router $router, RequestStack $requestStack)
    {
        $this->router       = $router;
        $this->requestStack = $requestStack;

        // Serialize object initialization
        $encoders          = array(new XmlEncoder(), new JsonEncoder());
        $normalizers       = array(new ObjectNormalizer());
        $this->serializer  = new Serializer($normalizers, $encoders);
    }

    /**
     * Generate current url
     * @return string
     */
    protected function generateCurrentUrl()
    {
        $request = $this->getRequest();
        $list    = explode('?page',  $request->getHttpHost() . $request->getRequestUri());
        $url     = $request->getHttpHost() . $request->getRequestUri();

        if (count($list) > 1) {
            list($url, $page) = explode('?page',  $request->getHttpHost() . $request->getRequestUri());
        }

        return ($request->isSecure() ? 'https://' : 'http://') . $url;
    }

    /**
     * Get router instance
     * @return Router
     */
    protected function getRouter()
    {
        return $this->router;
    }

    /**
     * Format a single record as HAL response
     * @param array $record
     * @param string $format
     * @return string
     */
    public function formatSingleResponse($record, $format = 'json')
    {

        switch ($format) {
            case 'json':
                return $this->serializer->serialize($record, $format);
                break;
            case 'xml':
                return $this->serializer->serialize($record, $format);
                break;
        }
    }

    /**
     * Format list of records as HAL response
     * @param array $records
     * @param int $page
     * @param int $size
     * @param int $total
     * @param string $format
     * @return string
     */
    public function formatListResponse($records, $offset = 1, $size = 100, $total = 0, $format = 'json')
    {
        // Prepare base node
        $return = array('data' => $records);

        // Set Pagination links
        $this->setPaginationLinks($return, $offset, $size, $total);
        // Return
        switch ($format) {
            case 'json':
                return $this->serializer->serialize($return, $format);
                break;
            case 'xml':
                return $this->serializer->serialize($return, $format);
                break;
        }
    }

    /**
     * Set Pagination links 
     */
    private function setPaginationLinks(& $records, $currentPg, $size, $total)
    {
        // Create links
        // Get current url
        $currentUrl = $this->generateCurrentUrl();
        $next       = '';
        $previous   = '';

        if (isset($records['data']['next']) && !empty($records['data']['next'])) {

            list($url, $nextpage) = explode('page', $records['data']['next']);

            $next = $currentUrl .'?page'.$nextpage;
        }

        if (isset($records['data']['previous']) && !empty($records['data']['previous'])) {

            list($url, $prevpage) = explode('page', $records['data']['previous']);

            $previous = $currentUrl .'?page'.$prevpage;
        }

        $records['pagination'] = array(
            'next'     => $next,
            'previous' => $previous,
        );

    }

    /**
     * Calculate pagination starting number
     */
    private function calculateStart(& $start, $currentPg, $last, & $max)
    {
        if ( ($currentPg - 2) < 2 ) {

            $start = 1 ;
            $max   = ($last <= $currentPg || $last < 5) ? $last : 5;

        } else if ($currentPg <= $last) {

            if ($currentPg + 2 > $last) {
                $start = ($currentPg - (5 - ($last - $currentPg))) < 1 ? 1 : ($currentPg - (5 - ($last - $currentPg)));
                $max   = $last;
            } else {
                $max   = $currentPg + 2;
                $start = ($currentPg - 2 ) < 1 ? 1  : ($currentPg - 2);
            }

        } else {
            $start = ($last - 5 ) < 1 ? 1 : $last - 5;
            $max   = $last;
        }

    }

    /**
     * Get request instance
     */
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
