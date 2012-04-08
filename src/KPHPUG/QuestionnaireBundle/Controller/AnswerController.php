<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5.3
 *
 * Copyright (c) 2012 GOTO Hidenori <hidenorigoto@gmail.com>,
 *               2012 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      File available since Release 0.1.0
 */

namespace KPHPUG\QuestionnaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use KPHPUG\QuestionnaireBundle\Domain\Entity\AnswerFactory;
use KPHPUG\QuestionnaireBundle\Domain\Service\Answering;
use KPHPUG\QuestionnaireBundle\Form\Type\AnswerType;

/**
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class AnswerController extends Controller
{
    const STATE_INPUT = 'input';
    const STATE_CONFIRMATION = 'confirmation';
    const STATE_SUCCESS = 'success';

    /**
     * @Route("/")
     * @Method("GET")
     */
    public function inputAction()
    {
        if (!$this->get('session')->has('state')) {
            $answerFactory = new AnswerFactory();
            $answer = $answerFactory->create(
                $this->get('doctrine')
                    ->getEntityManager()
                    ->getRepository('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem')
                    ->findBy(array(), array('itemNumber' => 'ASC'))
            );

            $this->get('session')->set('answer', $answer);
        }

        $this->get('session')->set('state', self::STATE_INPUT);
        return $this->render('KPHPUGQuestionnaireBundle:Answer:input.html.twig', array(
            'form' => $this->createForm(new AnswerType(), $this->get('session')->get('answer'))->createView(),
            'formErrors' => false,
        ));
    }

    /**
     * @Route("/")
     * @Method("POST")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function inputPostAction()
    {
        if (!($this->get('session')->has('state')
              && $this->get('session')->get('state') == self::STATE_INPUT)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new AnswerType(), $this->get('session')->get('answer'));
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $this->get('session')->set('state', self::STATE_CONFIRMATION);
            return $this->redirect($this->generateUrl('kphpug_questionnaire_answer_confirmation', array(), true));
        } else {
            return $this->render('KPHPUGQuestionnaireBundle:Answer:input.html.twig', array(
                'form' => $form->createView(),
                'formErrors' => true,
            ));
        }
    }

    /**
     * @Route("/confirmation")
     * @Method("GET")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function confirmationAction()
    {
        if (!($this->get('session')->has('state')
              && $this->get('session')->get('state') == self::STATE_CONFIRMATION)) {
            throw $this->createNotFoundException();
        }

        return $this->render('KPHPUGQuestionnaireBundle:Answer:confirmation.html.twig', array(
            'form' => $this->createFormBuilder()->getForm()->createView(),
            'answer' => $this->get('session')->get('answer'),
        ));
    }

    /**
     * @Route("/confirmation")
     * @Method("POST")
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function confirmationPostAction()
    {
        if (!($this->get('session')->has('state')
              && $this->get('session')->get('state') == self::STATE_CONFIRMATION)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()->getForm();
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            if ($this->getRequest()->request->has('prev')) {
                return $this->redirect($this->generateUrl('kphpug_questionnaire_answer_input', array(), true));
            }

            $answer = $this->get('session')->get('answer');
            $answering = new Answering($this->get('doctrine')->getEntityManager());
            $answering->answer($answer);

            $this->get('session')->remove('state');
            $this->get('session')->remove('answer');
            $this->get('session')->setFlash('answer', $answer);
            $this->get('session')->setFlash('state', self::STATE_SUCCESS);
            return $this->redirect($this->generateUrl('kphpug_questionnaire_answer_success', array(), true));
        } else {
            return $this->render('KPHPUGQuestionnaireBundle:Answer:confirmation.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/success")
     * @Method("GET")
     */
    public function successAction()
    {
        if (!($this->get('session')->hasFlash('state')
              && $this->get('session')->getFlash('state') == self::STATE_SUCCESS)) {
            return $this->redirect('http://conference.kphpug.jp/2012');
        }

        return $this->render('KPHPUGQuestionnaireBundle:Answer:success.html.twig');
    }
}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
