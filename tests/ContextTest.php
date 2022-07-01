<?php

use PHPUnit\Framework\TestCase;

use App\EvolvContext\Context;
require_once __DIR__ . '/../App/EvolvContext.php';


class EvolvContextTest extends TestCase {

    public function test() {
        $context = new Context();
    }

}
