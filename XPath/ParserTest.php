<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 encoding=utf-8 fdm=marker :

ini_set('include_path', dirname(__FILE__).PATH_SEPARATOR.ini_get('include_path'));

require_once 'PHPUnit/Framework.php';
require_once 'XPath/Parser.php';

class XPath_ParserTest extends PHPUnit_Framework_TestCase
{
    function test_01()
    {
        $this->_t(
            '/a',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                )
            )
            ,
            '/child::a'
        );
    }
    function test_02()
    {
        $this->_t("child::a",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                )
            ),
            '/child::a'
        );
    }
    function test_03()
    {
        $this->_t('/a/b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                )
            ),
            '/child::a/child::b'
        );
    }
    function test_04()
    {
        $this->_t('child::a/child::b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                )
            ),
            '/child::a/child::b'
        );
    }
    function test_05()
    {
        $this->_t('/a/b/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                )
            ),
            '/child::a/child::b'
        );
    }
    function test_06()
    {
        $this->_t('/a/b/c',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'c',
                )
            ),
            '/child::a/child::b/child::c'
        );
    }
    function test_07()
    {
        $this->_t('/a@b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'attribute',
                    'localName' => 'b',
                )
            ),
            '/child::a/attribute::b'
        );
    }
    function test_08()
    {
        $this->_t('child::a/attribute::b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'attribute',
                    'localName' => 'b',
                )
            ),
            '/child::a/attribute::b'
        );
    }
    function test_09()
    {
        $this->_t('/a/b@c',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                ),
                array (
                    'axis' => 'attribute',
                    'localName' => 'c',
                )
            ),
            '/child::a/child::b/attribute::c'
        );
    }
    function test_10()
    {
        $this->_t('child::a/child::b/attribute::c',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                ),
                array (
                    'axis' => 'attribute',
                    'localName' => 'c',
                )
            ),
            '/child::a/child::b/attribute::c'
        );
    }
    function test_11()
    {
        $this->_t('/a[1]',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
            ),
            '/child::a[1]'
        );
    }
    function test_12()
    {
        $this->_t('/a[1]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
            ),
            '/child::a[1]'
        );
    }
    function test_13()
    {
        $this->_t('/a[1]/@b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'attribute',
                    'localName' => 'b',
                ),
            ),
            '/child::a[1]/attribute::b'
        );
    }
    function test_14()
    {
        $this->_t('/a[1]/b',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                )
            ),
            '/child::a[1]/child::b'
        );
    }
    function test_15()
    {
        $this->_t('/a[1]/b[2]',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                    'position' => '2',
                )
            ),
            '/child::a[1]/child::b[2]'
        );
    }
    function test_16()
    {
        $this->_t('/a[1]/b[2]/c[3]',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                    'position' => '2',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'c',
                    'position' => '3',
                )
            ),
            '/child::a[1]/child::b[2]/child::c[3]'
        );
    }
    function test_17()
    {
        $this->_t('/a[1]/b/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                ),
            ),
            '/child::a[1]/child::b'
        );
    }
    function test_18()
    {
        $this->_t('/a/b[1]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                    'position' => '1',
                ),
            ),
            '/child::a/child::b[1]'
        );
    }
    function test_19()
    {
        $this->_t('/a[1]/b[2]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'b',
                    'position' => '2',
                ),
            ),
            '/child::a[1]/child::b[2]'
        );
    }
    function test_20()
    {
        $this->_t('/a[1][@a="b"]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'b',
                        ),
                    ),
                ),
            ),
            '/child::a[1][attribute::a = \'b\']'
        );
    }
    function test_21()
    {
        $this->_t('/a[1][@a="\'"]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '\'',
                        ),
                    ),
                ),
            ),
            "/child::a[1][attribute::a = '\'']"
        );
    }
    function test_22()
    {
        $this->_t('/a[1][@a="\\""]/',
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'position' => '1',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '"',
                        ),
                    ),
                ),
            ),
            '/child::a[1][attribute::a = \'"\']'
        );
    }
    function test_23()
    {
        $this->_t("/a[@a='a']/",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'a',
                        ),
                    ),
                ),
            ),
            '/child::a[attribute::a = \'a\']'
        );
    }
    function test_24()
    {
        $this->_t("/a[@a='a' or @b='b']/",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'a',
                            'logical' => 'or',
                        ),
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'b',
                        ),
                    ),
                ),
            ),
            '/child::a[attribute::a = \'a\' or attribute::b = \'b\']'
        );
    }
    function test_25()
    {
        $this->_t("/a[@a='a'][@b='b']/",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'a',
                        ),
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),

                            'operator' => '=',
                            'literal' => 'b',
                        ),
                    ),
                ),
            ),
            '/child::a[attribute::a = \'a\' and attribute::b = \'b\']'
        );
    }
    function test_26()
    {
        $this->_t("/a[@a='a'][@b='b']",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'a',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'a',
                        ),
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => 'b',
                        ),
                    ),
                ),
            ),
            '/child::a[attribute::a = \'a\' and attribute::b = \'b\']'
        );
    }
    function test_27()
    {
        $this->_t("/a[@b='2' and child::c='3']",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '2',
                        ),
                        array (
                            'location' => array (
                                array (
                                    'axis' => 'child',
                                    'localName' => 'c',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '3',
                        ),
                    ),
                ),
            ),
            '/child::a[attribute::b = \'2\' and child::c = \'3\']'
        );
    }
    function test_28()
    {
        $this->_t("/a//b",
            array (
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                ),
                array (
                    'axis' => 'descendant-or-self',
                    'localName' => 'b',
                ),
            ),
            '/child::a/descendant-or-self::b'
        );
    }
    function test_29()
    {
        $this->_t("/a[@b='1']/c[@b='2' and child::e='3']//d[@b='4']",
            
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array(
                        array(
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '1',
                        )
                    ),
                ),
                array (
                    'axis' => 'child',
                    'localName' => 'c',
                    'condition' => array(
                        array(
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '2',
                        ),
                        array(
                            'location' => array (
                                array (
                                    'axis' => 'child',
                                    'localName' => 'e',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '3',
                        )
                    ),
                ),
                array (
                    'axis' => 'descendant-or-self',
                    'localName' => 'd',
                    'condition' => array(
                        array(
                            'location' => array (
                                array (
                                    'axis' => 'attribute',
                                    'localName' => 'b',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '4',
                        )
                    ),
                ),
            ),
            '/child::a[attribute::b = \'1\']/child::c[attribute::b = \'2\' and child::e = \'3\']/descendant-or-self::d[attribute::b = \'4\']'
        );
    }
    function test_30()
    {
        $this->_t("/a[child::b/child::c='3']",
            array(
                array (
                    'axis' => 'child',
                    'localName' => 'a',
                    'condition' => array (
                        array(
                            'location' => array (
                                array (
                                    'axis' => 'child',
                                    'localName' => 'b',
                                ),
                                array (
                                    'axis' => 'child',
                                    'localName' => 'c',
                                ),
                            ),
                            'operator' => '=',
                            'literal' => '3',
                        )
                    )
                )
            ),
            '/child::a[child::b/child::c = \'3\']'
        );
    }

    function _t($a, $d, $l = null)
    {
        $b = new XPath_Parser($a);
        $c = current($b->getArray());
        $this->assertEquals($d, $c);
        $e = $b->getLocalization();

        if ($l != '')
            $this->assertEquals($l, $e);
    }
}
