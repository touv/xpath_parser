<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 encoding=utf-8 fdm=marker :
// {{{ Licence
// +--------------------------------------------------------------------------+
// | XPath_Parser                                                             |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2009 Nicolas Thouvenin                                     |
// +--------------------------------------------------------------------------+
// | This library is free software; you can redistribute it and/or modify it  |
// | under the terms of the GNU Lesser General Public License as published by |
// | the Free Software Foundation; either version 2.1 of the License, or      |
// | (at your option) any later version.                                      |
// | This library is distributed in the hope that it will be useful, but      |
// | WITHOUT ANY WARRANTY; without even the implied warranty of               |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                     |
// | See the GNU Lesser General Public License for more details.              |
// |                                                                          |
// | You should have received a copy of the GNU Lesser General Public License |
// | along with this library; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA              |
// +--------------------------------------------------------------------------+
// }}}

/**
 * @category  XPath
 * @package   XPath_Parser
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2009 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/lgpl-license.php LGPL
 * @version   SVN: $Id$
 * @link      http://www.touv.fr/
 */



/**
 * Classe Permettant
 *
 * @category  XPath
 * @package   XPath_Parser
 * @author    Nicolas Thouvenin <nthouvenin@gmail.com>
 * @copyright 2009 Nicolas Thouvenin
 * @license   http://opensource.org/licenses/lgpl-license.php LGPL
 */
class XPath_Parser
{
    protected $buffer;
    protected $pointer;
    protected $currentChar;
    protected $nextChar;
    protected $currentString;

    /**
     * Constructeur
     *
     * @param string $xpath
     */
    public function __construct($xpath)
    {
        $this->buffer = $xpath;
        $this->pointer = -1;
        $this->forward();
    }
    protected function forward($i = 1)
    {
        $this->pointer += $i;
        $this->currentChar = isset($this->buffer[$this->pointer]) ? $this->buffer[$this->pointer] :  null;
        $this->nextChar = isset($this->buffer[$this->pointer+1]) ? $this->buffer[$this->pointer+1] : '';
        $this->currentString = substr($this->buffer, $this->pointer);
    }
    public function getAST()
    {
        $ret = array();
        $this->location($ret);
        return $ret;
    }
    protected function location(array &$ret)
    {
        $ret['location'] = array();
        $i = 0;
        do {
            $ret['location'][$i] = array();
            $ctrl = $this->axis($ret['location'][$i]);
            if (is_null($ctrl)) {
                unset($ret['location'][$i]);
                break;
            }

            do {
                $c1 = $this->position($ret['location'][$i]);
                $c2 = $this->condition($ret['location'][$i]);
                if (is_null($c1) and is_null($c2)) break;
            } while(1);

            ++$i;
        } while (1);
        if ($i === 0) return null;
        else return true;
    }
    protected function axis(array &$ret)
    {
        if (preg_match(',^\s*[/]?(child::|attribute::|parent::|self::|descendant-or-self::)([\w\(\):{1}]+),i', $this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['axis'] = trim($m[1], ':/');
            $ret['localName'] = $m[2];

            return true;
        }
        elseif (preg_match(',^\s*(//|[/]?@|\.\./|\./|/)([\w:{1}]+),i', $this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['axis'] = strtr($m[1], array('/@'=>'attribute','@'=>'attribute','./'=>'self','//'=>'descendant-or-self', '/'=>'child'));
            $ret['localName'] = $m[2];
            return true;
        }
        else return null;
    }
    protected function position(array &$ret)
    {
        if (preg_match(',^\s*\[([0-9]+)\],',$this->currentString, $m)) { // or [position()=1]
            $this->forward(strlen($m[0]));
            $ret['position'] = $m[1];
            return true;
        }
        else return null;
    }
    protected function condition(array &$ret)
    {
        if (preg_match(',^\s*\[\s*,',$this->currentString, $m)) {
            $this->forward(strlen($m[0]));

            if (!isset($ret['condition'])) {
                $ret['condition'] = array();
                $i = 0;
            }
            else {
                $i = count($ret['condition']);
            }
            do {
                $ret['condition'][$i] = array();
                $ctrl = $this->location($ret['condition'][$i]);
                if (is_null($ctrl)) {
                    unset($ret['condition'][$i]);
                    break;
                }

                $ctrl = $this->operator($ret['condition'][$i]);
                if (is_null($ctrl)) break;

                $ctrl = $this->literal($ret['condition'][$i]);
                if (is_null($ctrl)) break;
                $ctrl = $this->logical($ret['condition'][$i]);
                if (is_null($ctrl)) break;

                ++$i;
            } while(1);

            if (preg_match(',^\s*\]\s*,',$this->currentString, $m)) {
                $this->forward(strlen($m[0]));
                return true;
            }
            else return null;
        }
        else return null;
    }
    protected function operator(array &$ret)
    {
        if (preg_match(',^\s*(<=|<|>=|=|!=)\s*,',$this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['operator'] = $m[1];
            return true;
        }
        else return null;
    }
    protected function literal(array &$ret)
    {
        if (preg_match(',^\s*([0-9]+)\s*,',$this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['literal'] = $m[1];
            return true;
        }
        elseif (preg_match("{^\s*[\"]([^\"\\\\]*(?:\\\\.[^\"\\\\]*)*)[\"]}x",$this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['literal'] = stripslashes($m[1]);
            return true;
        }
        elseif (preg_match('{ ^\s*[\']([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)[\']}x',$this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            $ret['literal'] = stripslashes($m[1]);
            return true;
        }
        else return null;
    }
    protected function logical(array &$ret)
    {
        if (preg_match(',^\s*(and|or)\s*,',$this->currentString, $m)) {
            $this->forward(strlen($m[0]));
            if ($m[1] == 'or')
                $ret['logical'] = $m[1];
            return true;
        }
        else return null;
    }


}



