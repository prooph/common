<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/30/15 - 7:15 PM
 */
namespace ProophTest\Common;


use Prooph\Common\Logger\ZF2\PsrZF2Logger;
use ProophTest\Common\Mock\ZF2LoggerMock;

final class PsrZF2LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_maps_all_psr_log_levels_correctly()
    {
        $zf2Logger = new ZF2LoggerMock();
        $psrLogger = new PsrZF2Logger($zf2Logger);

        $psrLogger->emergency('emergency', ['context' => 'emergency']);
        $this->assertEquals(['emergency' => ['context' => 'emergency']], $zf2Logger->loggedEmerg);

        $psrLogger->critical('critical', ['context' => 'critical']);
        $this->assertEquals(['critical' => ['context' => 'critical']], $zf2Logger->loggedCrit);

        $psrLogger->alert('alert', ['context' => 'alert']);
        $this->assertEquals(['alert' => ['context' => 'alert']], $zf2Logger->loggedAlert);

        $psrLogger->error('error', ['context' => 'error']);
        $this->assertEquals(['error' => ['context' => 'error']], $zf2Logger->loggedErr);

        $psrLogger->warning('warning', ['context' => 'warning']);
        $this->assertEquals(['warning' => ['context' => 'warning']], $zf2Logger->loggedWarn);

        $psrLogger->notice('notice', ['context' => 'notice']);
        $this->assertEquals(['notice' => ['context' => 'notice']], $zf2Logger->loggedNotice);

        $psrLogger->info('info', ['context' => 'info']);
        $this->assertEquals(['info' => ['context' => 'info']], $zf2Logger->loggedInfo);

        $psrLogger->debug('debug', ['context' => 'debug']);
        $this->assertEquals(['debug' => ['context' => 'debug']], $zf2Logger->loggedDebug);
    }
} 