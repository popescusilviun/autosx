<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * MotorizareRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MotorizareRepository extends EntityRepository
{
    public function findArrayMotorizari($conn, $id_model) {

        $where = " m.id_model = " . $id_model . " AND m.is_active = true ORDER BY m.nume ASC";
        $sql = "SELECT id, nume, an_start, an_final, kw, cp, cmc, caroserie, cod_motor, carburant FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function findArrayMinMaxAniMotorizari($conn, $id_model, $caroserie_selected) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND m.caroserie= '".$caroserie_selected."' ORDER BY m.nume ASC";
        $sql = "SELECT MIN(an_start) as an_min, MAX(an_final) as an_max FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetch(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            $j = 0;
            for($i = $results['an_min']; $i<=$results['an_max']; $i++) {
                $return[$j]['id'] = $i;
                $return[$j]['nume'] = $i;
                $j++;
            }
        }
        return $return;
    }

    public function findArrayMotorizariCombustibili($conn, $id_model, $caroserie_selected, $an_selected) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND m.an_start <= ".$an_selected." AND m.an_final >= ".$an_selected."
         AND m.caroserie = '".$caroserie_selected."' ORDER BY m.carburant ASC";
        $sql = "SELECT DISTINCT (carburant) FROM motorizare m WHERE $where";



        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            foreach($results as $r => $result) {
                $return[$r]['id'] = $result['carburant'];
                $return[$r]['nume'] = $result['carburant'];
            }
        }
        return $return;
    }

    public function findArrayMotorizariCombustibilAn($conn, $id_model, $an_selected, $combustibil_selected) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND an_start <= ".$an_selected." AND an_final >= ".$an_selected." AND carburant='".$combustibil_selected."' ORDER BY m.nume DESC";
        $sql = "SELECT id, nume, an_start, an_final, kw, cp, cmc, caroserie, cod_motor, carburant FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);



        return $results;
    }

    public function findArrayCaroserii($conn, $id_model) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true ORDER BY m.caroserie ASC";
        $sql = "SELECT DISTINCT (caroserie) FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            foreach($results as $r => $result) {
                $return[$r]['id'] = $result['caroserie'];
                $return[$r]['nume'] = $result['caroserie'];
            }
        }
        return $return;
    }

    public function findArrayCmcs($conn, $id_model, $caroserie_selected, $an_selected, $combustibil_selected) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND m.an_start <= ".$an_selected." AND m.an_final >= ".$an_selected."
         AND m.caroserie = '".$caroserie_selected."'
         AND m.carburant = '".$combustibil_selected."' ORDER BY m.cmc ASC";
        $sql = "SELECT DISTINCT (cmc) FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            foreach($results as $r => $result) {
                $return[$r]['id'] = $result['cmc'];
                $return[$r]['nume'] = $result['cmc'];
            }
        }
        return $return;

    }

    public function findArrayPuteri($conn, $id_model, $caroserie_selected, $an_selected, $combustibil_selected, $cmc_selected) {
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND m.an_start <= ".$an_selected." AND m.an_final >= ".$an_selected."
         AND m.caroserie = '".$caroserie_selected."'
         AND m.carburant = '".$combustibil_selected."'
         AND m.cmc = '".$cmc_selected."' ORDER BY m.kw ASC";
        $sql = "SELECT DISTINCT (kw), cp FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            foreach($results as $r => $result) {
                $return[$r]['id'] = $result['kw'].'-'.$result['cp'];
                $return[$r]['nume'] = $result['kw'] . ' KW / ' . $result['cp'] . ' CP';
            }
        }
        return $return;

    }
    public function findArrayMotorizariLast($conn, $id_model, $caroserie_selected, $an_selected, $combustibil_selected, $cmc_selected, $putere_selected) {
        $putere = explode('-', $putere_selected);
        $kw = $putere[0];
        $cp = $putere[1];
        $where = " m.id_model = " . $id_model . " AND m.is_active = true AND m.an_start <= ".$an_selected." AND m.an_final >= ".$an_selected."
         AND m.caroserie = '".$caroserie_selected."'
         AND m.carburant = '".$combustibil_selected."'
         AND m.cmc = '".$cmc_selected."'
         AND m.kw = '".$kw."'
         AND m.cp = '".$cp."' ORDER BY m.cmc ASC";
        $sql = "SELECT * FROM motorizare m WHERE $where";

        $sth = $conn->query($sql);
        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $return = array();
        if($results) {
            foreach($results as $r => $result) {
                $return[$r]['id'] = $result['id'];
                $return[$r]['nume'] = $result['nume'] . ' | ' . $result['cmc'] . ' cmc | ' .
                $result['kw'] . ' kw - ' . $result['cp'] . ' cp | cod motor: ' . $result['cod_motor'];
            }
        }
        return $return;

    }

    public function findOneArray($conn, $id_motorizare) {

        $where = " m.id = " . $id_motorizare . " AND m.is_active = true ORDER BY m.id ASC";
        $sql = "SELECT nume,slug,an_start,an_final,carburant FROM motorizare m WHERE $where";

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