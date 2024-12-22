<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BannedUserListener
{
  private AuthorizationCheckerInterface $authorizationChecker;
  private RouterInterface $router;

  public function __construct(AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router)
  {
    $this->authorizationChecker = $authorizationChecker;
    $this->router = $router;
  }

  public function onKernelRequest(RequestEvent $event): void
  {

    $request = $event->getRequest();

    // Exclure la route 'app_access_denied'
    if ($request->attributes->get('_route') === 'app_access_denied') {
      return;
    }

    // dump($request->attributes);
    // die;
    // Vérifiez si l'utilisateur a le rôle ROLE_BANNED
    if (
      $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')
      && $this->authorizationChecker->isGranted('ROLE_BANNED')
    ) {
      $response = new RedirectResponse($this->router->generate('app_access_denied'));
      $event->setResponse($response);
    }
  }
}
