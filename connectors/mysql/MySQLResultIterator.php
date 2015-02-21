<?php
/**                                                                                                                                                                                                                                                                            
 * MySQLResultIterator
 *                                                                                                                                                                                                                                                                             
 * @author Rafael Pinto <santospinto.rafael@gmail.com>                                                                                                                                                                                                                         
 */
namespace Connectors\MySQL;

/**
 * Iterator for mysql results.
 */
class MySQLResultIterator implements \Iterator
{
    protected $mysqlResult, $totalRows, $fetchedRows, $index, $current;
    
    /**
     * Constructs a MySQLResultIterator.
     * 
     * @param \mysqli_result $mysqlResult The mysqli result.
     */
    public function __construct(\mysqli_result $mysqlResult)
    {
        $this->mysqlResult = $mysqlResult;
        $this->totalRows   = $mysqlResult->num_rows;
        $this->fetchedRows = -1;
        $this->index       = 0;
        $this->current     = null;
    }
    
    /**
     * Returns the current element.
     * 
     * @return mixed
     */
    public function current()
    {
        if($this->fetchedRows != $this->index) {
            $this->fetchedRows = $this->index;
            $this->current     = $this->mysqlResult->fetch_assoc();
        }
        return $this->current;
    }

    /**
     * Returns the key of the current element.
     * 
     * @return scalar
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Moves the current position to the next element.
     * 
     * @return void
     */
    public function next()
    {
        return $this->index++;
    }

    /**
     * Rewinds back to the first element of the Iterator.
     * 
     * @return void
     */
    public function rewind()
    {
        $this->index       = 0;
        $this->fetchedRows = -1;
        $this->mysqlResult->data_seek(0);
    }

    /**
     *  Checks if current position is valid. 
     * 
     * @return boolean
     */
    public function valid()
    {
        return $this->fetchedRows < $this->totalRows -1;
    }
    
    /**
     * Returns the total rows.
     * 
     * @return int
     */
    public function getTotalRows()
    {
        return $this->totalRows;
    }

    /**
     * Returns the fetched rows.
     * 
     * @return int
     */
    public function getFetchedRows()
    {
        return $this->fetchedRows;
    }
    
    /**
     * Returns an array containing all the elements in the \Iterator.
     * 
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach($this as $elem) {
            $result[] = $elem;
        }
        return $result;
    }
}
