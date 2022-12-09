<?php

namespace Evolv\Utils;

require_once __DIR__ . '/polyfill.php';

function flatten_recursive(array $current, string $parentKey) {
    $items = [];

    foreach($current as $key => $value) {
        $newKey = $parentKey ? ($parentKey . '.' . $key) : $key;
        if (is_array($value) && !array_is_list($value)) {
            $items = array_merge($items, flatten_recursive($current[$key], $newKey));
        } else {
            $items[$newKey] = $value;
        }
    }

    return $items;
}

function flatten(array $array) {
    return flatten_recursive($array, '');
}
