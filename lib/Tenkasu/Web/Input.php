<?php
/* Web Input.
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
namespace Tenkasu\Web;

/**
 * Web Iinput.
 * @package Tenkasu
 * @subpackage Web
 */
class Input
{
    /**
     * @var array $_GET
     */
    public $get = [];

    /**
     * @var array $_POST
     */
    public $post = [];

    /**
     * @var array $_COOKIE
     */
    public $cookie = [];

    /**
     * @var array $_REQUEST
     */
    public $request = [];

    /**
     * @var array getallheaders()
     */
    public $header = [];

    /**
     * @var array $_SERVER
     */
    public $server = [];

    /**
     * @var array $_FILES
     */
    public $files = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->request = $_REQUEST;
        $this->header = is_callable('getallheaders')
                      ? getallheaders() : [];
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }

    /**
     * Getter
     * @param string $property 'get', 'post', ...
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($property, $key, $default = null)
    {
        $valueExists = isset($this->$property)
                    && is_array($this->$property)
                    && array_key_exists($key, $this->$property);
        if ($valueExists) {
            return $this->{$property}[$key];
        } else {
            return $default;
        }
    }

    /**
     * Access with Ajax or not.
     * @return bool
     */
    public function isAjax()
    {
        return strcasecmp(
            $this->get('server', 'HTTP_X_REQUESTED_WITH'),
            'XMLHttpRequest'
        ) === 0;
    }
}
