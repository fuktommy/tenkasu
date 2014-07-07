<?php
/* Web Output.
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
 * Web Output.
 * @package Tenkasu
 * @subpackage Web
 */
class Output
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * Constructor
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Put HTTP header.
     * @param string $key
     * @param string $value
     */
    public function putHeader($key, $value = null)
    {
        if (is_null($value)) {
            header($key);
        } else {
            header("{$key}: {$value}");
        }
    }

    /**
     * Set Cookie.
     */
    public function setCookie()
    {
        call_user_func_array('setcookie', func_get_args());
    }

    /**
     * Change HTTP body encoding.
     * @param string $encoding
     */
    public function switchEncoding($encoding)
    {
        mb_http_output($encoding);
        mb_internal_encoding('UTF-8');
        ob_start('mb_output_handler');
    }

    /**
     * Get variables holder.
     * @return \Tenkasu\Web\Variables
     */
    public function getVariables()
    {
        return new Variables();
    }

    /**
     * Display template.
     * @param string $path
     * @param \Tenkasu\Web\Variables $vars
     */
    public function display($path, Variables $vars)
    {
        global $var;
        $var = $vars;
        $base_path = rtrim($this->config['template_dir'], '/');
        $template_path = "{$base_path}/{$path}";
        if (! is_file($template_path)) {
            throw new \RuntimeException("template {$template_path} not exist.");
        }
        include $template_path;
    }
}
