<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Account;

	class OnlyAdminChecker implements UserCheckerInterface{


	    public function checkPreAuth(UserInterface $user){
	    	$this->checkAdmin($user);
	    }

	    public function checkPostAuth(UserInterface $user){
	    	$this->checkAdmin($user);
	    }

	    private function checkAdmin(UserInterface $user){

	    	if(!$user instanceof Account){

	    	}
	    	if($user->getRoles()){
	    		
	    	}
	    }

	}