<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta;

/**
 * MetaBuilder - used to encapsulate different operations to perform on post meta, this class allows us 
 * to generate several different instances of several different classes.
 * 
 * This class also provides extra flexibility, we could have instance methods that allow us to set instance level member 
 * variables and pass them to each operation class handler.
 */
class MetaBuilder
{
  /**
   * Create a new instance of our Select operation handler class. This allows you to create several 
   * instances of the Select class all with its own member vars.
   * 
   * Example:
   * 
   * $builder = new MetaBuilder();
   * $select1 = $builer->select();
   * $select2 = $builer->select();
   *
   * @uses MetaFactory::createSelect - generates a new instance of Select
   * 
   * @return Select
   */
  public function select()
  {
    return MetaFactory::createSelect();
  }

  /**
   * Create a new instance of our Insert operation handler class. This allows you to create several 
   * instances of the Insert class all with its own member vars. 
   * 
   * Example:
   * 
   * $builder = new MetaBuilder();
   * $insert1 = $builer->insert();
   * $insert2 = $builer->insert();
   *
   * @uses MetaFactory::createInsert - generates a new instance of Insert
   * 
   * @return Insert
   */
  public function insert()
  {
    return MetaFactory::createInsert();
  }

  /**
   * Create a new instance of our Upsert operation handler class. This allows you to create several 
   * instances of the Upsert class all with its own member vars.
   * 
   * Example:
   * 
   * $builder = new MetaBuilder();
   * $upsert1 = $builer->upsert();
   * $upsert2 = $builer->upsert();
   *
   * @uses MetaFactory::createUpsert - generates a new instance of Upsert
   * 
   * @return Upsert
   */
  public function upsert()
  {
    return MetaFactory::createUpsert();
  }
}