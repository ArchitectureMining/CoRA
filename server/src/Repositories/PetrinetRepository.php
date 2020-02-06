<?php

namespace Cora\Repositories;

use Cora\Systems\Petrinet\Petrinet;
use Cora\Systems\Petrinet\Flow;
use Cora\Systems\Tokens\IntegerTokenCount;

use Ds\Map;
use Exception;
use PDO;

class PetrinetRepository extends AbstractRepository {
    public function getPetrinet($id) {
        if (!$this->petrinetExists($id))
            return NULL;
        // collect places
        $query = sprintf("SELECT `name` FROM %s WHERE petrinet = :pid",
                         PETRINET_PLACE_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":pid" => $id]);
        $places = array_map(
            function($k) { return $k["name"]; },
            $statement->fetchAll());
        // collect transitions
        $query = sprintf("SELECT `name` FROM %s WHERE petrinet = :pid",
                         PETRINET_TRANSITION_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":pid" => $id]);
        $transitions = array_map(
            function($k) { return $k["name"]; },
            $statement->fetchAll());
        // collect flows
        $fromCol   = "from_element";
        $toCol     = "to_element";
        $weightCol = "weight";
        // place -> transition
        $query = sprintf("SELECT %s, %s, %s FROM %s WHERE `petrinet` = :pid",
                         $fromCol, $toCol, $weightCol, PETRINET_FLOW_PT_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":pid" => $id]);
        $flowsPt = $statement->fetchAll();
        // transition -> place
        $query = sprintf("SELECT %s, %s, %s FROM %s WHERE `petrinet` = :pid",
                         $fromCol, $toCol, $weightCol, PETRINET_FLOW_TP_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":pid" => $id]);
        $flowsTp = $statement->fetchAll();
        $flows = array_merge($flowsPt, $flowsTp);
        $flowMap = new Map();
        foreach($flows as $i => $flow) {
            $f = new Flow($flow[$fromCol], $flow[$toCol]);
            $weight = intval($flow[$weightCol]);
            $flowMap->put($f, $weight);
        }
        // get initial marking
        $query = sprintf(
            "SELECT `id` FROM %s WHERE `petrinet` = :pid",
            PETRINET_MARKING_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":pid" => $id]);
        $rows = $statement->fetchAll();
        $markingId = NULL;
        if (count($rows) > 0)
            $markingId = intval($rows[0]["id"]);
        $marking = NULL;
        if (!is_null($markingId)) {
            $query = sprintf(
                "SELECT `place`, `tokens` FROM %s WHERE marking = :mid",
                PETRINET_MARKING_PAIR_TABLE);
            $statement = $this->db->prepare($query);
            $statement->execute([":mid" => $markingId]);
            $marking = [];
            foreach($statement->fetchAll() as $i => $pair) 
                $marking[$pair["place"]] = intval($pair["tokens"]);
        }
        $petrinet = new Petrinet($places, $transitions, $flowMap, $marking);
        return $petrinet;
    }

    public function getPetrinets(int $limit = 0, int $offset = 0) {
        $query = sprintf("SELECT `id`, `name` FROM %s", PETRINET_TABLE);
        if ($limit > 0)
            $query .= sprintf(" LIMIT %d", $limit);
        if ($offset > 0)
            $query .= sprintf(" OFFSET %d", $offset);
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function savePetrinet($petrinet, $user, $name=NULL) {
        $this->db->beginTransaction();
        if(is_null($name)) 
            $name = sprintf("%s-%s", $user, date("Y-m-d-H:i:s"));
        $query = sprintf(
            "INSERT INTO %s (`creator`, `name`) VALUES(:creator, :name)",
            PETRINET_TABLE);
        $statement = $this->db->prepare($query);
        $statement->bindValue(":creator", $user, PDO::PARAM_INT);
        $statement->bindValue(":name", $name, PDO::PARAM_STR);
        $statement->execute();
        $petrinetId = $this->db->lastInsertId();

        $places      = $petrinet->getPlaces();
        $transitions = $petrinet->getTransitions();
        $flows       = $petrinet->getFlows();
        // register places
        $values = implode(", ", array_map(function($p) {
            return sprintf("(:pid, :%sname)", $p); }, $places->toArray()));
        $query = sprintf(
            "INSERT INTO %s (`petrinet`, `name`) VALUES %s",
            PETRINET_PLACE_TABLE, $values);
        $statement = $this->db->prepare($query);
        $statement->bindValue(":pid", $petrinetId, PDO::PARAM_INT);
        foreach($places as $i => $place)
            $statement->bindValue(sprintf(":%sname", $place), $place, PDO::PARAM_STR);
        $statement->execute();
        // register transitions
        $values = implode(", ", array_map(function($t) {
            return sprintf("(:pid, :%sname)", $t); }, $transitions->toArray()));
        $query = sprintf(
            "INSERT INTO %s (`petrinet`, `name`) VALUES %s",
            PETRINET_TRANSITION_TABLE, $values);
        $statement = $this->db->prepare($query);
        $statement->bindValue(":pid", $petrinetId, PDO::PARAM_INT);
        foreach($transitions as $i => $transition) 
            $statement->bindValue(sprintf(":%sname", $transition),
                                  $transition, PDO::PARAM_STR);
        $statement->execute();
        // register flows
        $ptValues = [];
        $tpValues = [];
        $i = 0;
        foreach($flows as $flow => $weight) {
            $s = sprintf("(:pid, :%dfrom, :%dto, :%dweight)", $i, $i, $i);
            if($places->contains($flow->from) && $transitions->contains($flow->to)) 
                array_push($ptValues, $s);
            elseif($transitions->contains($flow->from) && $places->contains($flow->to)) 
                array_push($tpValues, $s);
            else { // error!
                $this->db->rollBack();
                throw new Exception(sprintf(
                    "Inconsistent Flow. From: %s, to: %s",
                    $flow->from,
                    $flow->to)
                );
            }
            $i++;
        }
        $valuesPT = implode(", ", $ptValues);
        $valuesTP = implode(", ", $tpValues);

        $queryPT = sprintf(
            "INSERT INTO %s (`petrinet`, `from_element`, `to_element`, `weight`) "
            . "VALUES %s", PETRINET_FLOW_PT_TABLE, $valuesPT);
        $queryTP = sprintf(
            "INSERT INTO %s (`petrinet`, `from_element`, `to_element`, `weight`) "
            . "VALUES %s", PETRINET_FLOW_TP_TABLE, $valuesTP);

        $statementPT = $this->db->prepare($queryPT);
        $statementTP = $this->db->prepare($queryTP);
        $statementPT->bindValue(":pid", $petrinetId, PDO::PARAM_INT);
        $statementTP->bindValue(":pid", $petrinetId, PDO::PARAM_INT);
        $i = 0;
        foreach($flows as $flow => $weight) {
            // place -> transition flow
            if($places->contains($flow->from)) {
                $statementPT->bindValue(sprintf(":%dfrom", $i),
                                        $flow->from, PDO::PARAM_STR);
                $statementPT->bindValue(sprintf(":%dto", $i),
                                        $flow->to, PDO::PARAM_STR);
                $statementPT->bindValue(sprintf(":%dweight", $i),
                                        $weight, PDO::PARAM_INT);
            }
            // transition -> place flow
            else {
                $statementTP->bindValue(sprintf(":%dfrom", $i),
                                        $flow->from, PDO::PARAM_STR);
                $statementTP->bindValue(sprintf(":%dto", $i),
                                        $flow->to, PDO::PARAM_STR);
                $statementTP->bindValue(sprintf(":%dweight", $i),
                                        $weight, PDO::PARAM_INT);
            }
            $i++;
        }
        $statementPT->execute();
        $statementTP->execute();
        // register marking meta information
        $query = sprintf("INSERT INTO %s (`petrinet`) VALUES (:pid)",
                         PETRINET_MARKING_TABLE);
        $statement = $this->db->prepare($query);
        $statement->bindValue(":pid", $petrinetId, PDO::PARAM_INT);
        $statement->execute();
        $markingId = $this->db->lastInsertId();
        $marking = $petrinet->getInitial();

        // register marking map information
        $filteredPlaces = array_filter(
            array_keys($marking->toArray()),
            function($p) use ($marking, $places) {
                if (!$places->contains($p)) {
                    $this->db->rollBack();
                    $message = sprintf("Tokens assigned to a place that is not "
                                       . "part of the Petri net: %s", $place);
                    throw new Exception($message);
                } 
                $tokens = $marking->get($p);
                if (!$tokens instanceof IntegerTokenCount) {
                    $this->db->rollBack();
                    $message = sprintf("Could not store marking: improper "
                                       . "type: %s", get_class($tokens));
                    throw new Exception($message);
                }
                else if ($tokens->value > 0) 
                    return $p;
        });
        $values = implode(", ", array_map(
            function($p) {
                return sprintf("(:mid, :%spid, :%stid)", $p, $p);
            },
            $filteredPlaces
        ));
        $query = sprintf("INSERT INTO %s (`marking`, `place`, `tokens`) VALUES %s",
                         PETRINET_MARKING_PAIR_TABLE, $values);
        $statement = $this->db->prepare($query);
        $statement->bindValue(":mid", $markingId, PDO::PARAM_INT);
        foreach($marking as $place => $tokens) {
            if($tokens->value <= 0) continue;
            $statement->bindValue(sprintf(":%spid", $place),
                                  $place, PDO::PARAM_STR);
            $statement->bindValue(sprintf(":%stid", $place),
                                  $tokens->value, PDO::PARAM_INT);
        }
        $statement->execute();
        $this->db->commit();
        return $petrinetId;
    }

    public function petrinetExists($id) {
        $query = sprintf("SELECT * FROM %s WHERE `id` = :id", PETRINET_TABLE);
        $statement = $this->db->prepare($query);
        $statement->execute([":id" => $id]);
        return !empty($statement->fetchAll());
    }
}
