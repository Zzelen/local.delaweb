<?php


namespace App\Services;


use App\Entity\Organisation;
use App\Entity\User;
use App\Services\Validation\ValidationName;
use App\Services\Validation\ValidationOrganisation;
use App\Services\Validation\ValidationPassword;
use App\Services\Validation\ValidationPhone;
use App\Services\Validation\ValidationRepeatedPassword;
use App\Services\Validation\ValidationSurname;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $em;
    private $passwordEncoder;

    public function __construct(Container $container, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getUsers()
    {
        return $this->em->getRepository('App:User')->findAll();
    }

    public function validate($params)
    {
        $user = null;
        if (isset($params['id'])) {
            $user = $this->em->getRepository('App:User')->find($params['id']);
        }

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


        $currentPhone = isset($user) ? $user->getPhone() : '';

        if ($currentPhone !== $params['phone'])
            if ($this->uniqueValidatePhone($params['phone'])) {
                $result['phone'] = 'Пользователь с таким телефоном уже существует';
                $status = false;
            }


        $validationOrganisation = new ValidationOrganisation($params['organisation']);
        if ($validationOrganisation->isValid() === false) {
            $result['organisation'] = $validationOrganisation->getMessage();
            $status = false;
        }

        if (isset($params['password']) && isset($params['repeatedPassword'])) {
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

    public function save($params, $newUser = false)
    {
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

        if ($newUser) {
            $user = new User();
        } else {
            $user = $this->em->getRepository('App:User')->find($params['id']);
        }


        $user
            ->setPhone($params['phone'])
            ->setName($params['name'])
            ->setSurname($params['surname'])
            ->setInvited($invited_by)
            ->setOrganisation($organisation);

        if (isset($params['password'])) {
            $user
                ->setRoles(["ROLE_USER"])
                ->setPassword($this->passwordEncoder->encodePassword($user, $params['password']));
            }

        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

}