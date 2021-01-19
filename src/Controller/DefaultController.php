<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Controller;

use Jmleroux\CovidAttestation\Attestation\AttestationCommand;
use Jmleroux\CovidAttestation\Attestation\AttestationForm;
use Jmleroux\CovidAttestation\Attestation\AttestationHandler;
use Jmleroux\CovidAttestation\Attestation\UserData;
use Jmleroux\CovidAttestation\Attestation\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function userData(Request $request): Response
    {
        $userData = new UserData();
        $userForm = $this->createForm(UserForm::class, $userData);

        $url = '';
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $url = $this->get('router')->generate(
                'attestation',
                [
                    'user_form' => $userData->normalize(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        return $this->render('form.html.twig', [
            'user_form' => $userForm->createView(),
            'url' => $url,
        ]);
    }

    /**
     * @Route("/attestation", name="attestation", methods={"GET"})
     */
    public function attestationChoice(Request $request ,
        AttestationHandler $attestationHandler ): Response
    {
        $userData = new UserData();
        $userForm = $this->createForm(UserForm::class, $userData);

        $userForm->handleRequest($request);
        if ($userForm->isEmpty() || !$userForm->isSubmitted() || !$userForm->isValid()) {
            return $this->redirectToRoute('index');
        }
      else{
        //generate here directly
//          return new Response('Your attestation has been generated.');        
            $attestationHandler->generate($userForm->getData());
            return new Response('Your attestation has been generated.');        
      }      
    }

}
