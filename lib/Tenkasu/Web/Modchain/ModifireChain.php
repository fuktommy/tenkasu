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
namespace Tenkasu\Web\Modchain;
use \enkasu\Web\Modchain\ModifireChainIterator;

/**
 * Wrapper of Value.
 *
 * @package Tenkasu
 * @subpackage Web
 */
class ModifireChain implements \IteratorAggregate
{
    /**
     * @var string Wrapped string.
     */
    private $value;

    /**
     * Wrap value.
     * @param string $value Wrapped string.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get wrapped value.
     * @return string
     */
    public function unpack()
    {
        return $this->value;
    }

    /**
     * Call function.
     * @param callback $callback
     * @param mixed $arguments...
     * @return ModifireChain
     */
    public function func($callback)
    {
        $arguments = func_get_args();
        $arguments[0] = $this->value;
        return new self(call_user_func_array($callback, $arguments));
    }

    /**
     * Call method.
     * @param string $name
     * @param mixed $arguments...
     * @return ModifireChain
     */
    public function method($name)
    {
        $arguments = func_get_args();
        array_shift($arguments);
        return new self(call_user_func_array(array($this->value, $name), $arguments));
    }

    /**
     * Call undefined methods.
     * @param string $name
     * @param array $arguments
     * @return ModifireChain
     */
    public function __call($name, $arguments)
    {
        if (is_object($this->value) && method_exists($this->value, $name)) {
            $method = 'method';
        } elseif (function_exists($name)) {
            $method = 'func';
        } else {
            return new self(null);
        }
        array_unshift($arguments, $name);
        return call_user_func_array(array($this, $method), $arguments);
    }

    /**
     * Get object field.
     * @param string $key
     * @return ModifireChain
     */
    public function __get($key)
    {
        if (is_object($this->value)) {
            return $this->prop($key);
        } elseif (is_array($this->value)) {
            return $this->get($key);
        } else {
            return new self(null);
        }
    }

    /**
     * Date format using strftime.
     * @param string $format
     * @return ModifireChain
     */
    public function dateFormat($format)
    {
        return new self(strftime($format, $this->value));
    }

    /**
     * Return default value if wrapped value is false.
     * @param string $value
     * @return string
     * @return ModifireChain
     */
    public function defaults($value)
    {
        if ($this->value) {
            return $this;
        } elseif ($value instanceof self) {
            return $value;
        } else {
            return new self($value);
        }
    }

    /**
     * Escape value.
     * @param string $how 'html', 'url'
     * @return ModifireChain
     */
    public function escape($how = 'html')
    {
        if ($how == 'html') {
            return new self(htmlspecialchars((string)$this->value,
                            ENT_QUOTES));
        } elseif ($how == 'url') {
            return new self(rawurlencode((string)$this->value));
        } else {
            return $this;
        }
    }

    /**
     * Replace by regexp.
     * @param string $pattern
     * @param string $replacement
     * @return ModifireChain
     */
    public function regexReplace($pattern, $replacement)
    {
        return new self(preg_replace($pattern, $replacement, $this->value));
    }

    /**
     * Replace by string.
     * @param string $pattern
     * @param string $replacement
     * @return ModifireChain
     */
    public function replace($pattern, $replacement)
    {
        return new self(str_replace($pattern, $replacement, $this->value));
    }

    /**
     * String format.
     * @param string $format
     * @return ModifireChain
     */
    public function stringFormat($format)
    {
        return new self(sprintf($format, $this->value));
    }

    /**
     * Print value.
     * @return ModifireChain
     */
    public function p()
    {
        echo $this->value;
        return $this;
    }

    /**
     * Print escaped value.
     * @param string $how 'html', 'url'
     * @return ModifireChain
     */
    public function e($how = 'html')
    {
        if ($how == 'url') {
            echo rawurlencode((string)$this->value);
        } else {
            echo htmlspecialchars((string)$this->value, ENT_QUOTES);
        }
        return $this;
    }

    /**
     * Get array element.
     * @param mixed $key
     * @param mixed $default
     * @return ModifireChain
     */
    public function get($key, $default = null)
    {
        if (is_array($this->value) && array_key_exists($key, $this->value)) {
            return new self($this->value[$key]);
        } elseif ($default instanceof self) {
            return $default;
        } else {
            return new self($default);
        }
    }

    /**
     * Get object property.
     * @param mixed $key
     * @param mixed $default
     * @return ModifireChain
     */
    public function prop($key, $default = null)
    {
        if (is_object($this->value) && property_exists($this->value, $key)) {
            return new self($this->value->$key);
        } elseif ($default instanceof self) {
            return $default;
        } else {
            return new self($default);
        }
    }

    /**
     * Get iterator.
     * @return ModifireChain_Iterator
     */
    public function getIterator()
    {
        if (is_array($this->value)) {
            $value = new \ArrayIterator($this->value);
        } elseif ($this->value instanceof \Iterator) {
            $value = $this->value;
        } elseif ($this->value instanceof \IteratorAggregate) {
            $value = $this->value->getIterator();
        } else {
            $value = new \ArrayIterator(array($this->value));
        }
        return new ModifireChainIterator($value);
    }
}
