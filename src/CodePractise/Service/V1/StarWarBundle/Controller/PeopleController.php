<?php

namespace CodePractise\Service\V1\StarWarBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;

class PeopleController extends AbstractController
{
    /**
     * List down all the people
     *
     *  @QueryParam (name = "page", description ="Current page number", default = 1)
     *
     *
     * @Method({"GET"})
     *
     * @Route("/people",
     *     name="v1_list_people"
     *     )
     *
     * @Apidoc(
     *     description = "List down all the in star War API.",
     *      section    = "People",
     *      statusCodes = {
     *          200 = "OK",
     *          400  = "Parameter not found",
     *          404  = "Method not allowed"
     *      }
     *    )
     *
     */
    public function listAction(ParamFetcher $paramFetcher) {

        // Initialize manager class
        $pm     = $this->get('star_war_service.people_manager');
        $hm     = $this->get('star_war_service.hal_manager');

        try {

            $params = $paramFetcher->all();
            

            $people = $pm->getAllPeople($params);
            // set total number of people
            $total  = isset($people['count']) ? $people['count'] : 0;
            // Offset value
            $offset = 1;
            // Number of records
            $size   = 100;

            return $this->formatResponse($hm->formatListResponse($people,$offset,$size, $total), 200);


        } catch (\Exception $ex) {

            return $this->formatResponse($hm->formatSingleResponse(array('error' => $ex->getMessage())), $ex->getCode());
        }

    }
}
