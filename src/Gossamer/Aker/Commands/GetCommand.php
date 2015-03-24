<?php

namespace Gossamer\Aker\Commands;

use Gossamer\Aker\Commands\AbstractCommand;

use Gossamer\Pesedget\Database\QueryBuilder;

/**
 * Save Command Class
 *
 * Author: Dave Meikle
 * Copyright: Quantum Unit Solutions 2013
 */
class GetCommand extends AbstractCommand {

    /**
     * retrieves a single row from the database
     *
     * @param array     URI params
     * @param array     POST params
     */
    public function execute($params = array()) {
        
        $this->getQueryBuilder()->where($params);
        $query = $this->getQueryBuilder()->getQuery($this->entity, QueryBuilder::GET_ITEM_QUERY);
        
        $firstResult = $this->query($query);
        $entityName = get_class($this->entity);
        $this->request->setAttribute($entityName, current($firstResult));            
        //add it to the request but return it also
        return $firstResult;
    }

}
