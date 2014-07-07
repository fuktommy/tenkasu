<?php
/* 
 *  Copyright (c) 2009,2014 Satoshi Fukutomi <info@fuktommy.com>.
 *  All rights reserved.
 * 
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *  1. Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *  2. Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 * 
 *  THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 *  IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 *  ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
 *  FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 *  DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 *  OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 *  HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 *  OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 *  SUCH DAMAGE.
 */ 
namespace Tenkasu\Web;
use Tenkasu\Web\Modchain\ModifireChain;

/**
 * Template Variables Holder.
 *
 * <code>
 *   $var = new Variables();
 *   $var->foo = 100;
 *   $var->foo->stringFormat('%.1f')->e();   // puts '100.0'
 * </code>
 *
 * Methods come from Smarty, but selected.
 *
 * @package Tenkasu
 * @subpackage Web
 */
class Variables
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * Getter.
     * @param string $key
     * @return \Tenkasu\Web\Modchain\ModifireChain
     */
    public function __get($key)
    {
        return new ModifireChain(
            array_key_exists($key, $this->values) ?
                $this->values[$key] : null
        );
    }

    /**
     * Setter.
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Pack value.
     * @param mixed $value
     * @return ModifireChain
     */
    public function pack($value)
    {
        return new ModifireChain($value);
    }
}
