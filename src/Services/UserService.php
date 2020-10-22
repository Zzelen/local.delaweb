<?php


namespace App\Services;


use Symfony\Component\DependencyInjection\Container;

class UserService
{
    private $em;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function getUsers()
    {
        return $this->em->getRepository('App:User')->findAll();
    }

}