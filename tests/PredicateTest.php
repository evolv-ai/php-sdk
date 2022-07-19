<?php

use PHPUnit\Framework\TestCase;

use App\EvolvPredicate\Predicate;
use App\EvolvClient\EvolvClient;
use App\EvolvClient\EvolvStore;

require_once __DIR__ . '/../App/EvolvPredicate.php';
require_once __DIR__ . '/../App/EvolvContext.php';
require_once __DIR__ . '/../App/EvolvStore.php';

class PredicateTest extends TestCase
{
    private $predicate;
    private $context;
    private $config;
    private $localContext;

    public function setUp(): void
    {
        $this->predicate = new Predicate();

        $this->context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 'home'
            ]
        ];
        //$this->config = '{"_published":1657878514.4847507,"_client":{"browser":"chrome","device":"desktop","location":"RO","geo":{"city":"Bucharest","country":"RO","region":"B","metro":"","lat":"44.41520","lon":"26.16600","tz":"Europe/Bucharest"},"platform":"linux"},"_experiments":[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]}';
        $this->config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';
        // $this->config = '[{"uid":"user_id","eid":"5dd1f6cdb5","cid":"ef019bc45aa1:5dd1f6cdb5","genome":{"home":{"cta_text":"This way to the PDP!"},"pdp":{"page_layout":"Layout 1"}},"audience_query":{"id":1256,"name":"New Users","combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"ordinal":1,"group_id":"47a71ff2-f2d8-4ccb-92f9-8ec21a3974ad","excluded":false}]';
        $this->config = json_decode($this->config, true);
    }


   public function testGetKeyFromValueContext()
    {

        $activeKeys = $this->predicate->getKeyFromValeuContext($this->context);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testEvaluatePredicate()
    {


        $context = $this->predicate->getKeyFromValeuContext($this->context);

        $activeKeys = $this->predicate->evaluatePredicate($this->context, $this->config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testEvoluate()
    {

        $context = $this->predicate->getKeyFromValeuContext($this->context);

        $activeKeys = $this->predicate->evaluate($this->context, $this->config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testIsTrue()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 'home'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testExist()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategoryPage' => 'page'
            ]
        ];
        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategoryPage","fieldType":"string","operator":"exists","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testContains()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageSelect' => 'apple'
            ]
        ];
        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageSelect","fieldType":"string","operator":"contains","value":["apple","green","gold"],"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testDefined()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 'apple'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testEqual()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageEqual' => 'pdp'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageEqual","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testExists()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 'pdp'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

   public function testGreaterThan()
    {
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 25
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"greater_than","value":24,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testGreaterThanOrEqualTo(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 30
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"greater_than_or_equal_to","value":24,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testIsFalse(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageNumber' => false
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageNumber","fieldType":"string","operator":"is_false","value":24,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testNotContains()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageSelect' => 'cherry'
            ]
        ];
        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageSelect","fieldType":"string","operator":"not_contains","value":["apple","green","gold"],"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");

    }

    public function testNotDefined()
    {

        $context = [
            'native' => [
                'newUser' => true,
                'pageSelect' => ''
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageSelect","fieldType":"string","operator":"not_defined","value":"gold","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertEmpty($activeKeys, "data holder is not empty");

    }

    public function testLooseEqual(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 'pdp'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is empty");
    }

    public function testNotEqual(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategorys' => 'page'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategorys","fieldType":"string","operator":"not_equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testNotStartsWith(){

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategorys' => 'go'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategorys","fieldType":"string","operator":"not_starts_with","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertEmpty($activeKeys, "data holder is  empty");
    }

    public function testLessThan(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 20
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"less_than","value":24,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testLessThanOrEqualTo(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 2400
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"less_than_or_equal_to","value":2400,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testLooseNotEqual(){
        $context = [
            'native' => [
                'newUser' => true,
                'pageCategory' => 2401
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"loose_not_equal","value":2400,"type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"equal","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"apple","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testStartsWith(){

        $context = [
            'native' => [
                'newUser' => true,
                'pageCategorys' => 'pdp'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategory","fieldType":"string","operator":"defined","value":"home","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"native.pageCategorys","fieldType":"string","operator":"starts_with","value":"pdp","type":"attributes","readonly":false,"id":"52352c5c-5372-4fcc-b732-f4601e594d03"}],"readonly":false,"id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5"},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"not_exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertCount(2, $activeKeys);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }

    public function testRegex64Match(){

        $context = [
            'native' => [
                'newUser' => true,
                'url' => 'https?:\/\/[^/]+\/dev1\/features\.html(?:$|\?|#)/i'
            ]
        ];

        $config = '[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","rules":[{"field":"native.url","fieldType":"string","operator":"regex64_match","value":"L2h0dHBzPzpcL1wvW14vXStcL2RldjFcL2ZlYXR1cmVzXC5odG1sKD86JHxcP3wjKS9p","type":"attributes","readonly":false,"id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee"}],"readonly":false,"id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f"},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"id":"5dd1f6cdb5","_paused":false}]';

        $config = json_decode($config, true);

        $activeKeys = $this->predicate->evaluate($context, $config);

        $this->assertCount(2, $activeKeys);

        $this->assertNotEmpty($activeKeys, "data holder is  empty");
    }


}

