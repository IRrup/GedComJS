<?php
namespace PEAR2\Genealogy\Gedcom\Structures;
use PEAR2\Genealogy\Gedcom\Exceptions;
use PHPUnit;
require 'pear2\\Autoload.php';

class DataTest extends \PHPUnit_Framework_TestCase
{

    public function testEmtpyObjectString()
    {
        $a = new Data();
        $expected = "PEAR2\Genealogy\Gedcom\Structures\Data(SourceName->, Date->, Copyright->)";
        $this->assertSame($expected, '' . $a);
    }

    public function testStringFull()
    {
        $a = new Data();
        $a->SourceName = 'Source Data';
        $a->Date = '2010-03-01';
        $a->Copyright = "2010 Ed Thompson";
        $expected = "PEAR2\Genealogy\Gedcom\Structures\Data(SourceName->Source Data, Date->2010-03-01, Copyright->2010 Ed Thompson)";
        $this->assertSame($expected, '' . $a);
    }

    public function testCustomField()
    {
        $a = new Data();
        try {
            $a->customField = 'test';
            $this->fail('Expected exception not thrown.');
        } catch(Exceptions\InvalidFieldException $expected) {
            $this->assertTrue(TRUE);
        }
    }

    public function testParse() {
        $a = new Data();
        $tree = array(array('1 DATA Data Source', array()));
        $a->parseTree($tree, '5.5.1');
        $expected = "PEAR2\Genealogy\Gedcom\Structures\Data(SourceName->Data Source, Date->, Copyright->)";
        $this->assertSame($expected, '' . $a);
    }

    public function testGedcom() {
        $expected = "1 DATA Data Source\n2 DATE 2010-03-01";
        $a = new Data();
        $a->parseTree(array(array('1 DATA Data Source', array(array('2 DATE 2010-03-01', array())))), '5.5.1');
        $this->assertSame($expected, $a->toGedcom('1', '5.5.1'));
    }

    public function testGedcomFullNoCONC() {
        $expected = "1 DATA Data Source\n2 DATE 2010-03-01\n2 COPR Line1\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I";
        $a = new Data();
        $tree = array(array('1 DATA Data Source',
        array(array('2 DATE 2010-03-01', array()),
        array('2 COPR Line1',
        array(array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I')))))
        );
        $a->parseTree($tree, '5.5.1');
        $this->assertSame($expected, $a->toGedcom('1', '5.5.1'));
    }

    public function testGedcomFullCONC() {
        $expected = "1 DATA Data Source\n2 DATE 2010-03-01\n2 COPR Line1\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R";
        $a = new Data();
        $tree = array(array('1 DATA Data Source',
        array(array('2 DATE 2010-03-01', array()),
        array('2 COPR Line1',
        array(array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        ))))
        );
        $a->parseTree($tree, '5.5.1');
        $this->assertSame($expected, $a->toGedcom('1', '5.5.1'));
    }

    public function testGedcomFullFirstLineCONC() {
        $expected = "1 DATA Data Source\n2 DATE 2010-03-01\n2 COPR Line1123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q12345\n3 CONC 6789R\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R";
        $a = new Data();
        $tree = array(array('1 DATA Data Source',
        array(array('2 DATE 2010-03-01', array()),
        array('2 COPR Line1',
        array(array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        ))))
        );
        $a->parseTree($tree, '5.5.1');
        $this->assertSame($expected, $a->toGedcom('1', '5.5.1'));
    }
    public function testGedcomFullCONCPlusOne() {
        $expected = "1 DATA Data Source\n2 DATE 2010-03-01\n2 COPR Line1123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q12345\n3 CONC 6789R1\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R\n3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I\n3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R\n3 CONC 1";
        $a = new Data();
        $tree = array(array('1 DATA Data Source',
        array(array('2 DATE 2010-03-01', array()),
        array('2 COPR Line1',
        array(array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R1'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R'),
        array('3 CONT 123456789A123456789B123456789C123456789D123456789E123456789F123456789G123456789H123456789I'),
        array('3 CONC 123456789J123456789K123456789L123456789M123456789N123456789O123456789P123456789Q123456789R1'),
        ))))
        );
        $a->parseTree($tree, '5.5.1');
        $this->assertSame($expected, $a->toGedcom('1', '5.5.1'));
    }
}
?>