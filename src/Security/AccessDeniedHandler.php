<?php

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {

	public function handle(Request $request, AccessDeniedException $accessDeniedException){
		dd('access denied');
	}
}

 