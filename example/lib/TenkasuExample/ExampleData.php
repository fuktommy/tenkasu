<?php
/*
 * Copyright (c) 2012,2014 Satoshi Fukutomi <info@fuktommy.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

namespace TenkasuExample;
use Tenkasu\Db\Migration;
use Tenkasu\Resource;

/**
 * Storage
 * @package TenkasuExample
 */
class ExampleData
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * Constructor.
     * @param \Tenkasu\Resource $resource
     * @throws PDOException
     */
    public function __construct(Resource $resource)
    {
        $this->db = $resource->getDb();
    }

    /**
     * Set up storage.
     *
     * Call it before append().
     */
    public function setUp()
    {
        $this->db->beginTransaction();
        $migration = new Migration($this->db);
        $migration->execute(
            "CREATE TABLE IF NOT EXISTS `example`"
            . " (`id` INTEGER PRIMARY KEY NOT NULL,"
            . "  `message` TEXT NOT NULL)"
        );
        $migration->execute(
            "CREATE INDEX `message` ON `example` (`message`)"
        );
    }

    /**
     * Select last message
     * @return string|false
     * @throws PDOException
     */
    public function getLastMessage()
    {
        $state = $this->db->prepare(
            "SELECT `message` FROM `example`"
            . " ORDER BY `id` DESC LIMIT 1"
        );
        $state->execute();
        return $state->fetchColumn();
    }

    /**
     * Append new message
     * @param string $message
     * @throws PDOException
     */
    public function append($message)
    {
        $state = $this->db->prepare(
            "INSERT INTO `example` (`message`) VALUES (:message)"
        );
        $state->execute(['message' => $message]);
    }

    /**
     * Commit transaction.
     *
     * Call it after append()
     */
    public function commit()
    {
        $this->db->commit();
    }
}
