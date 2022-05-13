<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\LogoutEvent;

class LogoutListener
{
    public function onKernelLogout(LogoutEvent $event)
    {
        dd('inservice !', $request);

        $user = $event->getRequest()->getSession()->getUser();

        if($user->getIsVerified() == false){
            return;
        }
    }
}
 
 