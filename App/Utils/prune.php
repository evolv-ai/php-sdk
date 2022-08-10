<?php

namespace Evolv\Utils;

function prune(array $array, array $active)
{
    $pruned = [];

    foreach($active as $key) {
        $keys = explode('.', $key);
        $current = $array;
        for ($i = 0; $i < count($keys); $i++) { 
            $now = isset($keys[$i]) && isset($current[$keys[$i]]) ? $current[$keys[$i]] : null;
            if (isset($now)) {
                if ($i === (count($keys) - 1)) {
                    $pruned[$key] = $now;
                    break;
                }
                $current = $now;
            } else {
                break;
            }
        }
    }

     reattributePredicatedValues($pruned, $active);

    return $pruned;
}

/**
 * @param array $pruned
 * @param array $active
 * @returns void
 */
function reattributePredicatedValues($pruned, $active) {

    reattribute( [], $active, []);

    reattribute($pruned, $active, []);
}

function reattribute( array $obj, array $active, $collected = null) {

    if (!is_array($obj) || empty($obj)) {
        return $obj;
    }

    if (isset($obj['_predicated_values'])) {

         $predicatedKeyPrefix = implode(".", $collected);

        for ($i = 0; $i < count($obj['_predicated_values']); $i++) {

            if ($predicatedKeyPrefix . '.' . $obj['_predicated_values'][$i]['_predicate_assignment_id'] && in_array( $predicatedKeyPrefix,$active)) {
                return $obj['_predicated_values'][$i]['_value'];
            }

        }

        return null;
    }

    $keys = array_keys($obj);

    for ( $i = 0; $i < count($keys); $i++) {
        $key = $keys[$i];
        $newCollected = array_merge($collected,[$key]);

        if(is_array($obj[$key])) {
            $obj[$key] = reattribute($obj[$key], $active, $newCollected);
        }

    }

    return $obj;
}


