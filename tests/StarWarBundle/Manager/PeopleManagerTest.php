<?php

namespace StarWarBundle\Tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PeopleManagerTest extends KernelTestCase {

    private $manager;

    public function setup()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->manager = $kernel->getContainer()->get('star_war_service.people_manager');
    }

    public function testGetAllPeopleTotalCount()
    {
        $params = array('page' => 1);
        $people = $this->manager->getAllPeople($params);
        $this->assertEquals(87, $people['count']);
    }

    public function testGetAllPeopleResults()
    {
        $params = array('page' => 1);
        $people = $this->manager->getAllPeople($params);
        $this->assertEquals(true, array_key_exists('results', $people));
    }

    public function testGetPeopleResultsArrayAttributes()
    {
        $params = array('page' => 1);
        $people  = $this->manager->getAllPeople($params);
        $results = $people['results'];
        $this->assertEquals(true, array_key_exists('name', $results[0]));
        $this->assertEquals(true, array_key_exists('height', $results[0]));
        $this->assertEquals(true, array_key_exists('homeworld', $results[0]));
        $this->assertEquals(true, array_key_exists('films', $results[0]));
        $this->assertEquals(true, array_key_exists('gender', $results[0]));
    }

}
