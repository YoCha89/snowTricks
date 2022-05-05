<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\AccountRepository;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'login';


    private UrlGeneratorInterface $urlGenerator;
    private AccountRepository $accountRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator, AccountRepository $accountRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->accountRepository = $accountRepository;
    }

    public function authenticate(Request $request): Passport {
    
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        if($email != null){
            $user = $this->accountRepository->findOneBy(array('email'=>$email));
            if($user != null){
                $check = $this->checkIsVerified($request, $user);
                if($check==true){        
                    return new Passport(
                        new UserBadge($email),
                        new PasswordCredentials($request->request->get('password', '')),
                        [
                            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                        ]
                    );
                }
                //only authorizes a passport as a return 
                /*else{

                    return new RedirectResponse('/');
                }  */         
            }
        }
        
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('index'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    //checks if a user has verified his email when registering before connecting him
    public function checkIsVerified($request, $user) {

        if($user->getIsVerified() != false){
            return true;
        }else{
            $request->getSession()->invalidate();
            $request->getSession()->set('error', 'Veuillez confirmer la création de votre compte en cliquant sur le lien qui vous a été envoyé par email.');
            return new RedirectResponse('/');
        }
    }
}
