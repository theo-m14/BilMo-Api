<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CreateUserController extends AbstractController
{
    public function __invoke(User $user): User
    {
        $user->setCompany($this->getUser());
        return $user;
        
    }
}