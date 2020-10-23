<?php


namespace App\Controller;


use App\Entity\Organisation;
use App\Entity\User;
use App\Services\UserService;
use App\Services\Validation\ValidationName;
use App\Services\Validation\ValidationOrganisation;
use App\Services\Validation\ValidationPassword;
use App\Services\Validation\ValidationRepeatedPassword;
use App\Services\Validation\ValidationPhone;
use App\Services\Validation\ValidationSurname;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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

            $result = $this->validate($params);
            $errorMsg = $result['result'];


            if ($result['status']) {

                /** @var User $invited_by */
                $invited_by = $this->em->getRepository('App:User')->findOneBy(['id' => $params['invited']]);

                $organisationRep = $this->em->getRepository('App:Organisation');
                $currentOrg = $organisationRep->findOneByOrganisationName($params['organisation']);

                if ($currentOrg) {
                    $organisation = $currentOrg;
                } else {
                    $organisation = new Organisation();

                    $organisation
                        ->setName($params['organisation']);

                    $this->em->persist($organisation);
                    $this->em->flush();
                }
                $user = new User();

                $user
                    ->setPhone($params['phone'])
                    ->setRoles(["ROLE_USER"])
                    ->setPassword($this->passwordEncoder->encodePassword($user, $params['password']))
                    ->setName($params['name'])
                    ->setSurname($params['surname'])
                    ->setInvited($invited_by)
                    ->setOrganisation($organisation);

                    $this->em->persist($user);
                    $this->em->flush();

                $success = true;
            }

            return new JsonResponse([
                'errorMsg' => $errorMsg,
                'success' => $success
            ]);

        }
        return [];
    }

    private function validate($params)
    {
        $validationName = new ValidationName($params['name']);
        if ($validationName->isValid() === false) {
            $result['name'] = $validationName->getMessage();
            $status = false;
        }

        $validationSurname = new ValidationSurname($params['surname']);
        if ($validationSurname->isValid() === false) {
            $result['surname'] = $validationSurname->getMessage();
            $status = false;
        }

        $validationPhone = new ValidationPhone($params['phone']);
        if ($validationPhone->isValid() === false) {
            $result['phone'] = $validationPhone->getMessage();
            $status = false;
        }

        if ($this->uniqueValidatePhone($params['phone'])) {
            $result['phone'] = 'Пользователь с таким телефоном уже существует';
            $status = false;
        }

        $validationPassword = new ValidationPassword($params['password']);
        if ($validationPassword->isValid() === false) {
            $result['password'] = $validationPassword->getMessage();
            $status = false;
        }

        $passwords = [
            'password' => $params['password'],
            'repeatedPassword' => $params['repeatedPassword']
        ];

        $validationRepeatedPassword = new ValidationRepeatedPassword($passwords);
        if ($validationRepeatedPassword->isValid() === false) {
            $result['repeatedPassword'] = $validationRepeatedPassword->getMessage();
            $status = false;
        }

        $validationOrganisation = new ValidationOrganisation($params['organisation']);
        if ($validationOrganisation->isValid() === false) {
            $result['organisation'] = $validationOrganisation->getMessage();
            $status = false;
        }


        return [
            'result' => $result ?? true,
            'status' => $status ?? true
        ];

    }

    protected function uniqueValidatePhone($phone)
    {
        $userRep = $this->em->getRepository('App:User');
        $currentUser = $userRep->findOneByPhone($phone);
        if ($currentUser) {
            return true;
        }
        return false;
    }

}