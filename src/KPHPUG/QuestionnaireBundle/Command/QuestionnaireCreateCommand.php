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

namespace KPHPUG\QuestionnaireBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem;
use KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItemFactory;
use KPHPUG\QuestionnaireBundle\Domain\Service\QuestionnaireCreation;

/**
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class QuestionnaireCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:questionnaire:create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionnaireItems = array();
        foreach ($this->questions() as $question) {
            $questionnaireItemFactory = new QuestionnaireItemFactory();
            $questionnaireItems[] = $questionnaireItemFactory->create($question[0], $question[1], $question[2], $question[3]);
        }

        $questionnaireCreation = new QuestionnaireCreation($this->getContainer()->get('doctrine')->getEntityManager());
        $questionnaireCreation->create($questionnaireItems);
    }

    protected function questions()
    {
        return array(
            array('PHPとのつきあいかた', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 1),
            array('ソーシャルゲームとクラウドとPHPについて', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 2),
            array('クラウドはもう目の前！ PHP on Windows Azure 〜 PHPをクラウドにどう載せる？どう使う？〜', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 3),
            array('スマートフォンサイトの作成術', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 4),
            array('スマートフォン時代のWebシステム', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 5),
            array('ライトニングトーク', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 6),
            array('本イベントに参加して', QuestionnaireItem::ANSWER_TYPE_REVIEW, QuestionnaireItem::OPTIONALITY_REQUIRED, 7),
            array('次回開催された際は参加したいですか', QuestionnaireItem::ANSWER_TYPE_WISH, QuestionnaireItem::OPTIONALITY_REQUIRED, 8),
            array('今後聴いてみたいテーマはありますか？', QuestionnaireItem::ANSWER_TYPE_TEXT, QuestionnaireItem::OPTIONALITY_OPTIONAL, 9),
            array('今後参加してみたいイベントはありますか？', QuestionnaireItem::ANSWER_TYPE_TEXT, QuestionnaireItem::OPTIONALITY_OPTIONAL, 10),
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
