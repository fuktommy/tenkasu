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

ini_set('display_errors', 1);

require_once __DIR__ . '/../lib/bootstrap.php';
use Tenkasu\Bootstrap;
use Tenkasu\Context;
use Tenkasu\Web;


/**
 * Index Page
 */
class IndexAction implements Web\Action
{
    /**
     * Execute
     * @param Tenkasu\Context $context
     */
    public function execute(Context $context)
    {
        $data = new ExampleData($context->getResource());
        $data->setUp();

        $var = $context->webOutput->getVariables();

        $data->append('Hello');
        $var->hello = $data->getLastMessage();
        
        $nickname = $context->webInput->get('get', 'nickname', 'guest');
        $data->append($nickname);
        $var->nickname = $data->getLastMessage();
        
        $csrfBlocker = new Web\CsrfBlocker();
        $csrfBlocker->setTokens($context->webInput, $context->webOutput, $var);

        $context->webOutput->display('index.phtml', $var);
    }
}


(new Controller())->run(new IndexAction(), Bootstrap::getContext());
