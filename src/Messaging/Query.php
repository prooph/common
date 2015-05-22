<?php
/*
 * This file is part of the prooph/common.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/22/15 - 10:26 PM
 */
namespace Prooph\Common\Messaging;

/**
 * Class Query
 *
 * This is the base class for queries used to fetch data from read model.
 *
 * @package Prooph\Common\Messaging
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Query extends DomainMessage
{
    /**
     * Should be either MessageHeader::TYPE_COMMAND or MessageHeader::TYPE_EVENT or MessageHeader::TYPE_QUERY
     *
     * @return string
     */
    protected function messageType()
    {
        return MessageHeader::TYPE_QUERY;
    }
}