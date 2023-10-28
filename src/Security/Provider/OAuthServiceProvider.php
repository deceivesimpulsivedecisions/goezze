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
        dd($response->getData(), $user);
    }
}