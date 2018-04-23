<?php


/*******************************************************
 *
 *  driver : mysql , sqlite
 *  host : localhost , ""
 *  Controllers : mysql db name , sqlite db path with name
 *  port : 3306
 *  username : Controllers user
 *  password : Controllers password
 *  option : true or false
 *
 *******************************************************/



/*


$config =  [
'driver' => 'mysql',
'host' =>  'localhost',
'database' => '',
'username' => 'root',
'password' => '',
'port' => '3306',
"option" => true,
"engine" => 'InnoDB',
"charset" => 'utf8',
"collation" => 'utf8_general_ci',
];

 */



namespace Bindeveloperz;

use Exception;
use PDO;
use PDOException;

/**
 * Class Database
 * @package Bindeveloperz
 */
final class Database
{




    /*******************************************************
     *
     *  Database IUD FUNCTIONS
     *
     *******************************************************/









    private function nvl($string , $default = "")
    {
        return empty($string) ? $default : $string;
    }





    /*******************************************************
     *
     *  GETTER FUNCTIONS
     *
     *******************************************************/

    public function getSql()
    {
        return $this->dbQuery;
    }


    public function getErrors()
    {
        return $this->errors;
    }

    public function getResult()
    {
        return $this->dbResult;
    }

    public function getExecutedSql()
    {
        return $this->stmt->debugDumpParams();
    }




    /*******************************************************
     *
     *  MIGRATION QUERY BUILDER
     *
     *******************************************************/


    public function startQuery($actionType , $replace = false)
    {
        $this->actionType = $actionType ;

        switch ($actionType)
        {
            case "create-table":{
                $this->dbQuery = "CREATE TABLE";
                $this->dbQuery .= ($replace) ? " " : " IF NOT EXISTS ";
                $this->dbQuery .= "`" . $this->table . "`" . " ( \n";
                break;
            }
            case "drop-table":{
                $this->dbQuery = "DROP TABLE";
                $this->dbQuery .= ($replace) ? " IF EXISTS " : " ";
                $this->dbQuery .= "`" .  $this->table . "`" . " \n";
                break;
            }
            default : {
                die("Function: startQuery , Invalid parameters");
                break;
            }
        }
        return $this;

    }//startCreate end

    public function endQuery()
    {
        $this->generateColumns();
        switch ($this->actionType)
        {
            case "create-table":{
                $this->dbQuery .= ") $this->engine $this->charset $this->collation \n";
                break;
            }
            default : {
                $this->dbQuery .= "";
                break;
            }
        }
        $this->clearData();
    }

    public function dropTable()
    {
        $this->startQuery("drop-table" , "$this->table");
        $this->endQuery();
        $query = $this->getSql();
        if($this->executeQuery($query))
        {
            echo " Table `$this->table` dropped successfully`";
        }
        else
        {
            echo "FAILED TO DROP TABLE $this->table " . $this->getErrors();
        }
    }

    public function executeMigration()
    {
        $sts = $this->executeQuery($this->dbQuery);

        if($sts)
        {
            $this->clearVariables();
            return true;
        }
        return false;
    }

    /*********************************************************
     *  UNSIGNED INTEGER
     *********************************************************/


    public function unsignedInteger($columnName , $length = 11)
    {
        $integerType  =   "INT($length) UNSIGNED";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }

    public function unsignedTinyInteger($columnName , $length = 11)
    {
        $integerType  =   "TINYINT($length) UNSIGNED";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }

    public function unsignedSmallInteger($columnName , $length = 11)
    {
        $integerType  =   "SMALLINT($length) UNSIGNED";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }

    public function unsignedMediumInteger($columnName , $length = 11)
    {
        $integerType  =   "MEDIUMINT($length) UNSIGNED";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }

    public function unsignedLongInteger($columnName , $length = 11)
    {
        $integerType  =   "LONGINT($length) UNSIGNED";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }


    /*********************************************************
     *  INTEGER
     *********************************************************/


    public function integer($columnName , $length = 11)
    {
        $dbdriver = $this->driver;
        $integerType  = ($dbdriver == "sqlite") ? "INTEGER" : "INT($length)";
        $this->columns[] = "`$columnName` $integerType";
        return $this;
    }

    public function tinyInteger($columnName , $length = 4)
    {
        $this->columns[] = "`$columnName` TINYINT($length)";
        return $this;
    }

    public function smallInteger($columnName , $length = 11)
    {
        $this->columns[] = "`$columnName` SMALLINT($length)";
        return $this;
    }

    public function mediumInteger($columnName , $length = 11)
    {
        $this->columns[] = "`$columnName` MEDIUMINT($length)";
        return $this;
    }

    public function bigInteger($columnName , $length = 11)
    {
        $this->columns[] = "`$columnName` BIGINT($length)";
        return $this;
    }



    /*********************************************************
     *  FLOATING
     *********************************************************/

    public function float($columnName , $total = 8, $places = 2)
    {
        $dataType  = "FLOAT($total , $places)";
        $this->columns[] = "$columnName $dataType";
        return $this;
    }

    public function double($columnName, $total = 10, $places = 2)
    {
        $dataType  = ($total == null && $places == null ) ? "DOUBLE" :  "DOUBLE($total , $places)";
        $this->columns[] = "$columnName $dataType";
        return $this;
    }

    public function decimal($columnName, $total = 10, $places = 2)
    {
        $dataType  = ($total == null && $places == null ) ? "DECIMAL" :  "DECIMAL($total , $places)";
        $this->columns[] = "$columnName $dataType";
        return $this;
    }


    /*********************************************************
     *  STRING
     *********************************************************/


    public function char($columnName, $length = 255)
    {
        $stringType = "CHAR($length)";
        $this->columns[] = "$columnName $stringType";
        return $this;
    }

    public function string($columnName, $length = 255)
    {
        $dbdriver = $this->driver;
        $stringType = ($dbdriver == "sqlite") ? "TEXT" : "VARCHAR($length)";
        $this->columns[] = "`$columnName` $stringType";
        return $this;
    }

    public function text($columnName)
    {
        $this->columns[] = "`$columnName` TEXT";
        return $this;
    }

    public function tinyText($columnName)
    {
        $this->columns[] = "`$columnName` tinytext";
        return $this;
    }

    public function mediumText($columnName)
    {
        $this->columns[] = "`$columnName` MEDIUMTEXT";
        return $this;
    }

    public function longText($columnName)
    {
        $this->columns[] = "`$columnName` LONGTEXT";
        return $this;
    }



    /*********************************************************
     *  DATE TIME
     *********************************************************/


    public function datetime($columnName)
    {
        $this->columns[] = "`$columnName` DATETIME";
        return $this;
    }



    /*********************************************************
     *  BOOLEAN
     *********************************************************/

    public function boolean($columnName , $default = null)
    {
        $default = ($default == null) ? "" : "DEFAULT $default";
        $dataType  = "TINYINT(1) $default";
        $this->columns[] = "`$columnName` $dataType";
        return $this;
    }

    public function enum($columnName , $value)
    {
        $value = explode( ',',$value);

        $values = "";
        $countValues = count($value) - 1;
        foreach($value as $k => $v)
        {
            $values .= "'";
            $values .= $v ;
            $values .= ($k < $countValues) ? "'," : "'";
        }
        $dataType  = "ENUM($values)";
        $this->columns[] = "`$columnName` $dataType";
        return $this;
    }


    /*********************************************************
     *  INCREMENT
     *********************************************************/

    public function increments($columnName = "id"  , $length=11 , $unsigned = true , $primary = true)
    {
        $dbdriver = $this->driver;
        $datatype = ($dbdriver == "sqlite") ? "INTEGER" : "INT($length)";
        $notNull = ($dbdriver == "sqlite") ? "" : "NOT NULL";
        $auto = ($dbdriver == "sqlite") ? "AUTOINCREMENT" : "AUTO_INCREMENT";
        $primary = ($primary) ? "PRIMARY KEY" : "";
        $unsigned = ( ($dbdriver == "mysql") && $unsigned) ? "UNSIGNED" : "" ;
        $this->columns[] = "`$columnName` $datatype $unsigned $notNull $primary $auto ";
        return $this;
    }

    public function tinyIncrements($columnName = "id"  , $unsigned = true , $primary = true)
    {
        $dbdriver = $this->driver;
        $auto = ($dbdriver == "sqlite") ? "AUTOINCREMENT" : "AUTO_INCREMENT";
        $primary = ($primary) ? "PRIMARY KEY" : "";
        $unsigned = ( ($dbdriver == "mysql" ) && $unsigned) ? "UNSIGNED" : "" ;
        $this->columns[] = "`$columnName` TINYINT(11) NOT NULL $unsigned $auto $primary";
        return $this;
    }

    public function smallIncrements($columnName = "id"  , $unsigned = true , $primary = true)
    {
        $dbdriver = $this->driver;
        $auto = ($dbdriver == "sqlite") ? "AUTOINCREMENT" : "AUTO_INCREMENT";
        $primary = ($primary) ? "PRIMARY KEY" : "";
        $unsigned = ( ($dbdriver == "mysql" ) && $unsigned) ? "UNSIGNED" : "" ;
        $this->columns[] = "`$columnName` SMALLINT(11) NOT NULL $unsigned $auto $primary";
        return $this;
    }

    public function mediumIncrements($columnName = "id"  , $unsigned = true , $primary = true)
    {
        $dbdriver = $this->driver;
        $auto = ($dbdriver == "sqlite") ? "AUTOINCREMENT" : "AUTO_INCREMENT";
        $primary = ($primary) ? "PRIMARY KEY" : "";
        $unsigned = ( ($dbdriver == "mysql") && $unsigned) ? "UNSIGNED" : "" ;
        $this->columns[] = "`$columnName` MEDIUMINT(11) NOT NULL $unsigned $auto $primary";
        return $this;
    }

    public function bigIncrements($columnName = "id"  , $unsigned = true , $primary = true)
    {
        $dbdriver = $this->driver;
        $auto = ($dbdriver == "sqlite") ? "AUTOINCREMENT" : "AUTO_INCREMENT";
        $primary = ($primary) ? "PRIMARY KEY" : "";
        $unsigned = ( ($dbdriver == "mysql") && $unsigned) ? "UNSIGNED" : "" ;
        $this->columns[] = "`$columnName` BIGINT(11) NOT NULL $unsigned $auto $primary";
        return $this;
    }


    public function engine($name = "InnoDB"){
        $this->engine = "ENGINE= $name";
    }


    public function charset($name = "utf8"){
        $this->charset = "CHARACTER SET $name";
    }

    public function collation($name = "utf8_general_ci"){
        $this->collation = "COLLATE $name";
    }


    public function foreign($key , $table , $reference , $keyName = "")
    {
        if($keyName != "")
        {
            $keyName = $this->nvl($keyName , "CONSTRAINT FK_" . $key);
        }
        $this->columns[] = "$keyName FOREIGN KEY ($key) REFERENCES $table($reference)";
        return $this;
    }

    public function active($columnName , $length = 1 , $default = 1)
    {
        $this->columns[] = "`$columnName` TINYINT($length) UNSIGNED NOT NULL DEFAULT " . $this->whichDataType($default);
        return $this;
    }


    public function onUpdate($action = "CASCADE")
    {
        $this->addColumn("ON UPDATE $action");
        return $this;
    }

    public function onDelete($action = "CASCADE")
    {
        $this->addColumn("ON DELETE $action");
        return $this;
    }

    public function key($columns , $indexName = "")
    {
        $columns = str_replace(',' , '`,`' , $columns);
        $this->columns[] = "KEY `$indexName` (`$columns`)";
        return $this;
    }

    public function uniqueKey($columns , $indexName = null)
    {
        if($indexName != null)
        {
            $indexName = "KEY `$indexName`" ;
        }
        
        $columns = preg_replace('/\s+/', '', $columns);
        $columns = trim(str_replace(',' , '`,`' , $columns));
        $this->columns[] = "UNIQUE $indexName (`$columns`)";
        return $this;
    }

    public function primary()
    {
        $this->addColumn("PRIMARY KEY");
        return $this;
    }


    public function unique()
    {
        $this->addColumn("UNIQUE");
        return $this;
    }


    public function unsigned()
    {
        $this->addColumn("UNSIGNED");
        return $this;
    }

    public function nullable()
    {
        $this->addColumn("NULL");
        return $this;
    }

    public function comment($string = "")
    {
        $dbdriver = $this->driver;
        $comment = ($dbdriver == "sqlite") ? "/* '$string' */ " : "COMMENT '$string'";
        $this->addColumn("$comment" );
        return $this;
    }


    public function notNullable()
    {
        $this->addColumn("NOT NULL");
        return $this;
    }

    public function default($value = "")
    {
        $this->addColumn("DEFAULT " . $this->whichDataType($value));
        return $this;
    }


    public function currentTimeStamp()
    {
        $this->addColumn("DEFAULT CURRENT_TIMESTAMP");
        return $this;
    }



    /*******************************************************
     *
     *  PRIVATE FUNCTIONS
     *
     *******************************************************/

    private function clearData()
    {
        $this->columns = [];
    }


    private function generateColumns()
    {
        $count = count($this->columns);
        foreach($this->columns  as $i => $q)
        {
            $this->dbQuery .= "$q";
            $this->dbQuery .= ($i < $count - 1 ) ? ",\n" : "\n";
        }
    }


    private function addColumn($value)
    {
        $index = count($this->columns) - 1 ;
        $this->columns[$index] = $this->columns[$index] . " " . $value ;
    }//addColumn end


    private function whichDataType($value)
    {
        if(is_int($value))
        {
            return $value;
        }
        return  "'" . $value . "'";
    }

    private function clearVariables()
    {
        $this->table = "";
        $this->field = "*";
        $this->primaryKey = "id";

        $this->where = "";
        $this->orderBy = "";
        $this->groupBy = "";
        $this->having = "";
        $this->limit = "";
        $this->offset = "";

    }


}//class end