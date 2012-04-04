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
 * @ORM\Table(name="questionnaire_item",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="questionnaireitem_itemnumber_idx", columns={"item_number"})
 *      })
 * @ORM\Entity(repositoryClass="KPHPUG\QuestionnaireBundle\Domain\Entity\QuestionnaireItemRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @package    KPHPUGQuestionnaireBundle
 * @copyright  2012 GOTO Hidenori <hidenorigoto@gmail.com>
 * @copyright  2012 KUBO Atsuhiro <kubo@iteman.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since      Class available since Release 0.1.0
 */
class QuestionnaireItem
{
    const ANSWER_TYPE_REVIEW = 'review'; // 選択 満足・ほぼ満足・普通・やや不満・不満
    const ANSWER_TYPE_WISH = 'wish'; // 選択 希望する・どちらでもない・希望しない
    const ANSWER_TYPE_TEXT = 'text'; // テキスト
    const OPTIONALITY_REQUIRED = 'required';
    const OPTIONALITY_OPTIONAL = 'optional';

    /**
     * @var array
     */
    public static $CHOICES_REVIEW = array(
        '満足' => '満足',
        'ほぼ満足' => 'ほぼ満足',
        '普通' => '普通',
        'やや不満' => 'やや不満',
        '不満' => '不満',
    );

    /**
     * @var array
     */
    public static $CHOICES_WISH = array(
        '希望する' => '希望する',
        'どちらでもない' => 'どちらでもない',
        '希望しない' => '希望しない',
    );

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="text", type="text")
     *
     * @var string
     */
    protected $text;

    /**
     * @ORM\Column(name="answer_type", type="string", length=255)
     *
     * @var string
     */
    protected $answerType;

    /**
     * @ORM\Column(name="optionality", type="string", length=255)
     *
     * @var string
     */
    protected $optionality;

    /**
     * @ORM\Column(name="item_number", type="integer")
     *
     * @var integer
     */
    protected $itemNumber;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @var \DateTime
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
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $answerType
     */
    public function setAnswerType($answerType)
    {
        $this->answerType = $answerType;
    }

    /**
     * @return string
     */
    public function getAnswerType()
    {
        return $this->answerType;
    }

    /**
     * @param string $optionality
     */
    public function setOptionality($optionality)
    {
        $this->optionality = $optionality;
    }

    /**
     * @return string
     */
    public function getOptionality()
    {
        return $this->optionality;
    }

    /**
     * @param integer $itemNumber
     */
    public function setItemNumber($itemNumber)
    {
        $this->itemNumber = $itemNumber;
    }

    /**
     * @return integer
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
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
 * coding: utf-8
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
