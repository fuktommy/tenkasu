<?php
/* IO Root.
 *
 * Copyright (c) 2010-2014 Satoshi Fukutomi <info@fuktommy.com>.
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
namespace Tenkasu;

/**
 * IO Root.
 * @package Tenkasu
 */
class Context
{
    /**
     * @var array
     */
    public $config = [];

    /**
     * @var \Tenkasu\Web\Input
     */
    public $webInput;

    /**
     * @var \Tenkasu\Web\Output
     */
    public $webOutput;

    /**
     * Constructor
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->webInput = new \Tenkasu\Web\Input();
        $this->webOutput = new \Tenkasu\Web\Output($this->config);
    }

    /**
     * Factory for Log
     * @param string $ident
     * @return Log
     */
    public function getLog($ident = '')
    {
        require_once 'Log.php';
        $logfile = $this->config['log_dir'] . strftime('/debug.%Y%m%d.log');
        return \Log::singleton('file', $logfile, $ident);
    }

    /**
     * Factory for model settings.
     * @return Tenkasu\Resource
     */
    public function getResource()
    {
        return new Resource($this);
    }
}
