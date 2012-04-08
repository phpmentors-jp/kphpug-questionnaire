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

namespace KPHPUG\QuestionnaireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem;
use KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItemFactory;
use KPHPUG\QuestionnaireBundle\Event\BundleEvent;

/**
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class AnswerControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @param array $postBootListeners
     */
    protected function addPostBootListeners(array $postBootListeners)
    {
        foreach ($postBootListeners as $postBootListener) {
            BundleEvent::addPostBootListener($postBootListener);
        }
    }

    /**
     * @param array $postBootListeners
     */
    protected function removePostBootListeners(array $postBootListeners)
    {
        foreach ($postBootListeners as $postBootListener) {
            BundleEvent::removePostBootListener($postBootListener);
        }
    }

    protected function setUp()
    {
        $this->entityManager = \Phake::mock('Doctrine\ORM\EntityManager');

        $databasePlatform = \Phake::mock('Doctrine\DBAL\Platforms\AbstractPlatform');
        \Phake::when($databasePlatform)
            ->registerDoctrineTypeMapping($this->anything(), $this->anything())
            ->thenReturn(null);
        $connection = \Phake::mock('Doctrine\DBAL\Driver\Connection');
        \Phake::when($connection)->getDatabasePlatform()->thenReturn($databasePlatform);
        \Phake::when($this->entityManager)->getConnection()->thenReturn($connection);
    }

    public function configureEntityManager(BundleEvent $event)
    {
        $event->getContainer()->set('doctrine.orm.default_entity_manager', $this->entityManager);
    }

    public function configurePreparationContext(BundleEvent $event)
    {
        $questionnaireItemFactory = new QuestionnaireItemFactory();
        $questionnaireItems = array();
        $questionnaireItems[] = $questionnaireItemFactory->create('PHPとのつきあいかた', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 1);
        $questionnaireItems[] = $questionnaireItemFactory->create('ソーシャルゲームとクラウドとPHPについて', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 2);

        $questionnaireItemRepository = \Phake::mock('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItemRepository');
        \Phake::when($questionnaireItemRepository)->findBy($this->anything(), $this->anything())
            ->thenReturn($questionnaireItems);

        \Phake::when($this->entityManager)->getRepository('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem')
            ->thenReturn($questionnaireItemRepository);
    }

    public function configureAnsweringContext(BundleEvent $event)
    {
        $questionnaireItems = array();
        foreach (array(1, 2) as $questionnaireItemId) {
            $questionnaireItem = \Phake::mock('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem');
            \Phake::when($questionnaireItem)->getId()->thenReturn($questionnaireItemId);
            $questionnaireItems[] = $questionnaireItem;
        }

        $answerRepository = \Phake::mock('KPHPUG\QuestionnaireBundle\Domain\Entity\AnswerRepository');
        $questionnaireItemRepository = \Phake::mock('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItemRepository');
        \Phake::when($questionnaireItemRepository)->find($this->anything())
            ->thenReturn($questionnaireItems[0])
            ->thenReturn($questionnaireItems[1]);

        \Phake::when($this->entityManager)->getRepository('KPHPUG\QuestionnaireBundle\Domain\Entity\Answer')
            ->thenReturn($answerRepository);
        \Phake::when($this->entityManager)->getRepository('KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem')
            ->thenReturn($questionnaireItemRepository);
    }

    /**
     * @test
     */
    public function アンケートに回答する()
    {
        $postBootListeners = array(
            array($this, 'configurePreparationContext'),
            array($this, 'configureEntityManager'),
        );
        $this->addPostBootListeners($postBootListeners);
        $client = static::createClient(); /* @var $client \Symfony\Component\BrowserKit\Client */
        $this->removePostBootListeners($postBootListeners);

        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertThat(
            $client->getCrawler()->filter('title')->text(),
            $this->stringContains('回答のご入力')
        );

        $form = $client->getCrawler()->selectButton('next')->form();
        $form['answer[answerDetails][answerDetail0]'] = QuestionnaireItem::$CHOICES_REVIEW['満足'];
        $form['answer[answerDetails][answerDetail1]'] = QuestionnaireItem::$CHOICES_REVIEW['不満'];

        $postBootListeners = array(
            array($this, 'configureEntityManager'),
        );
        $this->addPostBootListeners($postBootListeners);
        $client->submit($form);
        $this->removePostBootListeners($postBootListeners);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->request('GET', $client->getResponse()->headers->get('Location'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertThat(
            $client->getCrawler()->filter('title')->text(),
            $this->stringContains('回答のご確認')
        );

        $postBootListeners = array(
            array($this, 'configureAnsweringContext'),
            array($this, 'configureEntityManager'),
        );
        $this->addPostBootListeners($postBootListeners);
        $client->submit($client->getCrawler()->selectButton('next')->form());
        $this->removePostBootListeners($postBootListeners);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->request('GET', $client->getResponse()->headers->get('Location'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertThat(
            $client->getCrawler()->filter('title')->text(),
            $this->stringContains('回答完了')
        );
    }
}

/*
 * Local Variables:
 * mode: php
 * coding: utf-8
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
