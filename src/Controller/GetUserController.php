<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class GetUserController extends AbstractController
{
    public function __invoke(User $user) : User
    {
        if($this->getUser() != $user->getCompany()){
            //return 404 for security purpose
            throw new NotFoundHttpException('User Not Found');
        }
        return $user;
    }
}
