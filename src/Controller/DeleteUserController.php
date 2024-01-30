<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class DeleteUserController extends AbstractController
{
    public function __invoke(User $user) : User
    {
        if($this->getUser() != $user->getCompany()){
            throw new NotFoundHttpException('User Not Found');
        }
        return $user;
    }
}