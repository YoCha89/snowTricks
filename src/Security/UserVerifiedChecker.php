<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Account;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

	class UserVerifiedChecker implements UserCheckerInterface{


	    public function checkPreAuth(UserInterface $user){
	    	$this->checkIsVerified($user);
	    }

	    public function checkPostAuth(UserInterface $user){
	    	return;
	    }

	    private function checkIsVerified(UserInterface $user){
	    	
			if($user->getIsVerified()){
	            return;
	        }else{
	             throw new CustomUserMessageAccountStatusException('Veuillez finaliser la création de votre compte en cliquant sur le lien envoyé par mail.');
	        }
	    }

	}

