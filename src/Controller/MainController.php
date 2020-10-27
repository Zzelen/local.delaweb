<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('app_login');
    }

}