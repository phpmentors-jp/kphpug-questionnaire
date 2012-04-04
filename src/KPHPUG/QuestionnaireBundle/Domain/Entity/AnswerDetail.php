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

namespace KPHPUG\QuestionnaireBundle\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="answer_detail",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="questionnaire_item_answer_idx", columns={"answer_id", "questionnaire_item_id"})
 *      })
 * @ORM\Entity(repositoryClass="KPHPUG\QuestionnaireBundle\Domain\Entity\AnswerDetailRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class AnswerDetail
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Answer", inversedBy="answerDetails")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     *
     * @var \KPHPUG\QuestionnaireBundle\Domain\Entity\Answer
     */
    protected $answer;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionnaireItem")
     * @ORM\JoinColumn(name="questionnaire_item_id", referencedColumnName="id")
     *
     * @var \KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem
     */
    protected $questionnaireItem;

    /**
     * @ORM\Column(name="input", type="text", nullable="true")
     *
     * @var string
     */
    protected $input;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \KPHPUG\QuestionnaireBundle\Domain\Entity\Answer $answer
     */
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return \KPHPUG\QuestionnaireBundle\Domain\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param \KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem $questionnaireItem
     */
    public function setQuestionnaireItem(QuestionnaireItem $questionnaireItem)
    {
        $this->questionnaireItem = $questionnaireItem;
    }

    /**
     * @return \KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItem
     */
    public function getQuestionnaireItem()
    {
        return $this->questionnaireItem;
    }

    /**
     * @param string $input
     */
    public function setInput($input)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
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
