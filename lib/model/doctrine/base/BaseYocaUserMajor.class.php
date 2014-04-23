<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('YocaUserMajor', 'doctrine');

/**
 * BaseYocaUserMajor
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $YocaUser
 * 
 * @method integer             getId()       Returns the current record's "id" value
 * @method string              getName()     Returns the current record's "name" value
 * @method Doctrine_Collection getYocaUser() Returns the current record's "YocaUser" collection
 * @method YocaUserMajor       setId()       Sets the current record's "id" value
 * @method YocaUserMajor       setName()     Sets the current record's "name" value
 * @method YocaUserMajor       setYocaUser() Sets the current record's "YocaUser" collection
 * 
 * @package    yoca
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseYocaUserMajor extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('yoca_user_major');
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
        $this->hasMany('YocaUser', array(
             'local' => 'id',
             'foreign' => 'major_id'));
    }
}