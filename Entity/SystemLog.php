<?php

namespace Kod3r\LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SystemLog
 *
 * @ORM\Table(name="tb_system_log")
 * @ORM\Entity(repositoryClass="Kod3r\LogBundle\Entity\SystemLogRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SystemLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="channel", type="string", length=255, nullable=true)
     */
    private $channel;

    /**
     * @var string
     *
     * @ORM\Column(name="log", type="text", nullable=true)
     */
    private $log;

    /**
     * @var string
     *
     * @ORM\Column(name="formatted_message", type="text", nullable=true)
     */
    private $formattedMsg;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=255, nullable=true)
     */
    private $level;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime")
     */
    private $modifiedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set log
     *
     * @param string $log
     *
     * @return SystemLog
     */
    public function setLog( $log )
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set serverData
     *
     * @param string $formattedMsg
     *
     * @return SystemLog
     */
    public function setFormattedMsg( $formattedMsg )
    {
        $this->formattedMsg = $formattedMsg;

        return $this;
    }

    /**
     * Get serverData
     *
     * @return string
     */
    public function getFormattedMsg()
    {
        return $this->formattedMsg;
    }

    /**
     * Set level
     *
     * @param string $level
     *
     * @return SystemLog
     */
    public function setLevel( $level )
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set modifiedAt
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setModifiedAt( \DateTime $date = null )
    {
        $date             = $date == null ? new \DateTime() : $date;
        $this->modifiedAt = $date;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAtValue()
    {
        $this->modifiedAt = new \DateTime();
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setCreatedAt( \DateTime $date = null )
    {
        $date = $date == null ? new \DateTime() : $date;

        $this->modifiedAt = $date;
        $this->createdAt  = $date;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $date             = new \DateTime();
        $this->modifiedAt = $date;
        $this->createdAt  = $date;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set channel
     *
     * @param string $channel
     *
     * @return SystemLog
     */
    public function setChannel( $channel )
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
