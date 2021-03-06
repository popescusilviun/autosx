<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * ModelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelRepository extends EntityRepository
{
    public function findArrayModel($conn, $id_model) {

        $where = " m.id = " . $id_model . " AND m.is_active = true ORDER BY m.nume ASC";
        $sql = "SELECT id, nume, slug FROM model m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetch(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function findArrayModele($conn, $id_marca) {

        $where = " m.id_marca = " . $id_marca . " AND m.is_active = true ORDER BY m.nume ASC";
        $sql = "SELECT id, id_marca, nume, slug FROM model m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function findArrayModelByMarcaAndSlug($conn, $id_marca, $model_slug) {
        $where = " m.id_marca = " . $id_marca . " AND m.is_active = true AND m.slug = '".$model_slug."' ORDER BY m.nume ASC";
        $sql = "SELECT id, nume, slug FROM model m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetch(\PDO::FETCH_ASSOC);
        return $results;
    }
}
/*$parameters = array(
            'offset' => 0,
            'limit' => 20,
            'is_active' => Products::STATUS_ACTIVE
        );
        $selectColumns = $from = $where = $group_by = $order = '';
        $joins = array();

        $selectColumns .= "select p.*";
        $from .= ' from '.$this->_em->getClassMetadata("site\\BackendBundle\\Entity\\Products")->getTableName().' p LEFT JOIN categories c ON ( c.id = p.id_category )';
        $where .= " where p.is_active=:is_active ";

        if(!empty($search_term)) {
            $parameters['searchTermLike'] = '%'.$search_term.'%';
            $parameters['searchTerm'] = strtolower($search_term);
            $selectColumns .= " ,(LENGTH(LOWER(p.title)) - LENGTH(REPLACE(LOWER(p.title), :searchTerm, '')))/LENGTH(:searchTerm) as name_rel";
            $where .=" AND ( LOWER(p.title) LIKE :searchTermLike ) ";
        }

        $group_by .= " group by p.title ";
        $order .= " order by name_rel DESC limit :offset, :limit";
        $sql = "$selectColumns $from " . implode(' ', array_unique($joins)) . " $where $group_by $order";
        $rsm = $this->getMapper();
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameters($parameters);

        return $query->getResult();*/