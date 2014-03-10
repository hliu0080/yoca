<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('YocaIndustry', 'doctrine');

/**
 * BaseYocaIndustry
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $Event
 * 
 * @method integer             getId()    Returns the current record's "id" value
 * @method string              getName()  Returns the current record's "name" value
 * @method Doctrine_Collection getEvent() Returns the current record's "Event" collection
 * @method YocaIndustry        setId()    Sets the current record's "id" value
 * @method YocaIndustry        setName()  Sets the current record's "name" value
 * @method YocaIndustry        setEvent() Sets the current record's "Event" collection
 * 
 * @package    yoca
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseYocaIndustry extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('yoca_industry');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 45,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Event', array(
             'local' => 'id',
             'foreign' => 'industry'));
    }
}