<?php

namespace App\Security\Provider;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

class OAuthServiceProvider implements OAuthAwareUserProviderInterface
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface; 
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getData();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        // If the user is not available then register the user
        if(is_null($user))
        {
            $user = new User();

            $user->setEmail($data['email']);
            $user->setFirstName($data['given_name']);
            $user->setLastName($data['family_name']);
            $user->setIsVerified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        dd($response->getData(), $user);
    }
}