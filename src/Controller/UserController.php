<?php


namespace App\Controller;


use App\Entity\User;
use App\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $em;
    public $userService;
    private $passwordEncoder;

    public function __construct(Container $container, UserService $userService, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userService = $userService;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     * @Route ("/userCreate")
     */
    public function createAction(Request $request)
    {
        $success = false;


        if ($request->getMethod() === 'POST') {
            $params = [
                'phone' => trim($request->get('phone')) ?? '',
                'name' => trim($request->get('name')) ?? '',
                'surname' => trim($request->get('surname')) ?? '',
                'invited' => trim($request->get('invited')) ?? '',
                'organisation' => trim($request->get('organisation')) ?? '',
                'password' => trim($request->get('password')) ?? '',
                'repeatedPassword' => trim($request->get('repeatedPassword')) ?? ''
            ];

            $result = $this->userService->validate($params);
            $errorMsg = $result['result'];

            if ($result['status']) {
                $success = $this->userService->save($params, true);
            }

            return new JsonResponse([
                'errorMsg' => $errorMsg,
                'success' => $success
            ]);


        }
        return [];
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     * @Template ()
     * @Route ("/profile")
     */
    public function profileAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $users = $this->userService->getUsers();
        $success = false;

        if ($request->getMethod() === 'POST') {

            $params = [
                'phone' => trim($request->get('phone')) ?? '',
                'name' => trim($request->get('name')) ?? '',
                'surname' => trim($request->get('surname')) ?? '',
                'invited' => trim($request->get('invited')) ?? '',
                'organisation' => trim($request->get('organisation')) ?? '',
                'id' => $user->getId()
            ];


            $result = $this->userService->validate($params);
            $errorMsg = $result['result'];
            if ($result['status']) {
                $success = $this->userService->save($params, false);
            }


            return new JsonResponse([
                'errorMsg' => $errorMsg,
                'success' => $success
            ]);
        }
        return [
            'user' => $user,
            'users' => $users
        ];
    }

}