<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class GetAllUserController extends AbstractController
{
    /**
     * @return User[]
     */
    public function __invoke(UserRepository $userRepository)
    {
        return $userRepository->findBy(["company" => $this->getUser()]);
    }
}