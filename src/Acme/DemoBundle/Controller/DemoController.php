<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DemoController extends Controller
{
    /**
     * @Route("/", name="newsletter_signup")
     * @Template()
     */
    public function newsletterSignupAction(Request $request)
    {
        $form = $this->getNewsletterSignupForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $newButton    = $form->get('new');
            $editButton   = $form->get('edit');
            $createButton = $form->get('create');

            if ($newButton->isClicked()) {
                $form = $this->getNewsletterSignupForm($form->getData(), true);
            }

            if ($editButton->isClicked()) {
                /**
                 * We don't have to do anything here because we are just
                 * returning the original form with the submitted data attached
                 */
            }

            if ($createButton->isClicked()) {
                $response = 'Thanks for signing up!';

                return new Response($response);
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Handle creating the form and setting fields / buttons and data
     */
    protected function getNewsletterSignupForm($data = null, $previewMode = false)
    {
        $actionUrl = $this->generateUrl('newsletter_signup');

        $formBuilder             = $this->createFormBuilder($data);
        $fieldAttributes         = [];
        $newButtonAttributes     = [];
        $previewButtonAttributes = [];

        if ($previewMode) {
            $fieldAttributes['read_only'] = true;
            $newButtonAttributes['attr']  = ['hidden' => true];
        } else {
            $previewButtonAttributes['attr'] = ['hidden' => true];
        }

        $formBuilder->add('name', 'text', $fieldAttributes)
                    ->add('email_address', 'email', $fieldAttributes)
                    ->add('new', 'submit', $newButtonAttributes)
                    ->add('edit', 'submit', $previewButtonAttributes)
                    ->add('create', 'submit', $previewButtonAttributes);

        $formBuilder->setAction($actionUrl);

        $form = $formBuilder->getForm();

        return $form;
    }
}
