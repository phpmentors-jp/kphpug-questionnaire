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

namespace KPHPUG\QuestionnaireBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem;

/**
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class AnswerDetailsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $answerDetails = $options['answer']->getAnswerDetails();
        for ($i = 0; $i < count($answerDetails); ++$i) {
            $answerDetailOptions = array();
            switch ($answerDetails[$i]->getQuestionnaireItem()->getAnswerType()) {
            case QuestionnaireItem::ANSWER_TYPE_REVIEW:
                $type = 'choice';
                $answerDetailOptions['choices'] = QuestionnaireItem::$CHOICES_REVIEW;
                $answerDetailOptions['expanded'] = true;
                break;
            case QuestionnaireItem::ANSWER_TYPE_WISH:
                $type = 'choice';
                $answerDetailOptions['choices'] = QuestionnaireItem::$CHOICES_WISH;
                $answerDetailOptions['expanded'] = true;
                break;
            case QuestionnaireItem::ANSWER_TYPE_TEXT:
                $type = 'text';
                break;
            }

            $answerDetailOptions['label'] = $answerDetails[$i]->getQuestionnaireItem()->getText();
            $answerDetailOptions['required'] = $answerDetails[$i]->getQuestionnaireItem()->getOptionality() == QuestionnaireItem::OPTIONALITY_REQUIRED;
            $answerDetailOptions['property_path'] = '[' . $i . '].input';
            $builder->add('answerDetail' . $i, $type, $answerDetailOptions);
        }
    }

    public function getDefaultOptions(array $options)
    {
        if (!array_key_exists('answer', $options)) {
            $options['answer'] = null;
        }
        return $options;
    }

    public function getName()
    {
        return 'answerdetails';
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
