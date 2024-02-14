<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class GetAllUserController extends AbstractController
{
    /**
     * @return User[]
     */
    public function __invoke(Request $request,UserRepository $userRepository)
    {   
        $page = (int) $request->query->get('page', 1);

        /** @var Company $company */
        $company = $this->getUser();
        
        return $userRepository->getUserByCompany($company->getId(),$page);
    }
}
