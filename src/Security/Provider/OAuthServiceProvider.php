<?php

namespace App\Security\Provider;

use App\Entity\User;
use App\Security\GoogleAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class OAuthServiceProvider implements OAuthAwareUserProviderInterface
{
    public $entityManager;
    public $userAuthenticator;
    public $authenticator;
    public $request;

    public function __construct(EntityManagerInterface $entityManagerInterface, UserAuthenticatorInterface $userAuthenticatorInterface, GoogleAuthenticator $formLoginAuthenticator, RequestStack $request)
    {
        $this->entityManager = $entityManagerInterface; 
        $this->userAuthenticator = $userAuthenticatorInterface;
        $this->authenticator = $formLoginAuthenticator;
        $this->request = $request;
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
        
        return $this->userAuthenticator->authenticateUser($user, $this->authenticator, $this->request->getCurrentRequest());
    }
}