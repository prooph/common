<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/30/15 - 7:10 PM
 */
namespace ProophTest\Common\Mock;


use Traversable;
use Zend\Log\LoggerInterface;

final class ZF2LoggerMock implements LoggerInterface
{
    public $loggedEmerg;

    public $loggedAlert;

    public $loggedCrit;

    public $loggedErr;

    public $loggedWarn;

    public $loggedNotice;

    public $loggedInfo;

    public $loggedDebug;

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function emerg($message, $extra = array())
    {
        $this->loggedEmerg = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function alert($message, $extra = array())
    {
        $this->loggedAlert = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function crit($message, $extra = array())
    {
        $this->loggedCrit = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function err($message, $extra = array())
    {
        $this->loggedErr = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function warn($message, $extra = array())
    {
        $this->loggedWarn = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function notice($message, $extra = array())
    {
        $this->loggedNotice = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function info($message, $extra = array())
    {
        $this->loggedInfo = [$message => $extra];
    }

    /**
     * @param string $message
     * @param array|Traversable $extra
     * @return LoggerInterface
     */
    public function debug($message, $extra = array())
    {
        $this->loggedDebug = [$message => $extra];
    }
}