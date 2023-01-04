<?php

use PHPUnit\Framework\TestCase;

use Evolv\EvolvClient;
use Evolv\HttpClient;

class PredicatedVariantsTest extends TestCase
{
    protected $client;
    protected $environment = '1';
    protected $uid = '1';
    protected $endpoint = "https://participants.test.com/";

    public function setUp(): void
    {
        $arr_location = '[{"uid":"12345","eid":"5eb1b07712","cid":"1aa4d9ad2402:5eb1b07712","genome":{"home":{"cta_text":{"_predicated_variant_group_id":"group1","_predicated_values":[{"_predicate_assignment_id":"p1","_predicate":{"combinator":"and","rules":[{"field":"device","operator":"loose_equal","value":"desktop"}]},"_value":"This way to the PDP!"},{"_predicate_assignment_id":"group1-9cb1a685f141dfd048051a425ddb4657","_predicate":null,"_value":"Go To PDP"}]}},"pdp":{"page_layout":"Layout 2"}},"audience_query":{"id":1256,"name":"New Users","combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"ordinal":10,"group_id":"d7ed45a6-3690-4867-bb40-5aa73d2391a4","excluded":false}]';
        $arr_config = '{"_published":1671577264.0705404,"_client":{"browser":"chrome","device":"desktop","location":"UA","geo":{"city":"Varash","country":"UA","region":"56","metro":"","postal":"34403","lat":"51.35050","lon":"25.83950","tz":"Europe/Kyiv"},"platform":"macos","timestamp":1671580145448},"_experiments":[{"web":{},"_predicate":{"id":1256,"combinator":"and","rules":[{"field":"native.newUser","operator":"is_true","value":""}]},"home":{"_is_entry_point":true,"_predicate":{"combinator":"and","id":"8670b6ba-f56c-4d63-a722-5e3acc975b4f","readonly":false,"rules":[{"field":"native.pageCategory","fieldType":"string","id":"c21dfad4-f9b3-4e86-8ea4-b743023552ee","operator":"loose_equal","readonly":false,"type":"attributes","value":"home"}]},"cta_text":{"_is_entry_point":false,"_predicate":null,"_values":true,"_initializers":true},"_initializers":true},"pdp":{"_is_entry_point":false,"_predicate":{"combinator":"and","id":"f0b82ec4-c68c-4b58-81a5-975c9525c0a5","readonly":false,"rules":[{"field":"native.pageCategory","fieldType":"string","id":"52352c5c-5372-4fcc-b732-f4601e594d03","operator":"loose_equal","readonly":false,"type":"attributes","value":"pdp"}]},"page_layout":{"_is_entry_point":false,"_predicate":{"combinator":"and","rules":[{"field":"extra_key","operator":"exists","value":"","type":"custom_attribute","readonly":false,"id":"f5ab3069-c022-4a5c-a736-30028547155f"}],"readonly":false,"id":"6ce9f491-3f36-43de-b155-b6389728be45"},"_values":true,"_initializers":true},"_initializers":true},"id":"5eb1b07712","_paused":false,"_include_eid_in_hash":true}],"_display_names":{"experiments":{"5eb1b07712":"Basic PHP"}}}';
        
        /** @var MockObject */ 
        $mock = $this->createMock(HttpClient::class);

        $mock->method('get')
            ->willReturn($arr_location, $arr_config);
        $this->client = new EvolvClient($this->environment, $this->endpoint, true, $mock);
        $this->client->initialize($this->uid, [], []);
    }

    /**
     * @test
     */
    public function shouldReturnDefaultVariantValueWhenItsPredicateDoesNotMatch()
    {
        // Arrange
        $this->client->context->set('native.newUser', true);
        $this->client->context->set('native.pageCategory', 'home');
        $this->client->context->set('device', 'mobile');

        // Act
        $predicatedVariantValue = $this->client->get('home.cta_text');
        $activeKeys = $this->client->context->get('keys.active');
        $activeVariants = $this->client->context->get('variants.active');

        // Assert
        $this->assertEquals('Go To PDP', $predicatedVariantValue);
        $this->assertEquals(['home.cta_text', 'home', 'home.cta_text.group1-9cb1a685f141dfd048051a425ddb4657'], $activeKeys);
        $this->assertEquals(['home.cta_text:-412250187', 'home:691848323'], $activeVariants);
    }

    /**
     * @test
     */
    public function shouldReturnTargetVariantValueWhenItsPredicateMatches()
    {
        // Arrange
        $this->client->context->set('native.newUser', true);
        $this->client->context->set('native.pageCategory', 'home');
        $this->client->context->set('device', 'desktop');

        // Act
        $predicatedVariantValue = $this->client->get('home.cta_text');
        $activeKeys = $this->client->context->get('keys.active');
        $activeVariants = $this->client->context->get('variants.active');

        // Assert
        $this->assertEquals('This way to the PDP!', $predicatedVariantValue);
        $this->assertEquals(['home.cta_text', 'home', 'home.cta_text.p1'], $activeKeys);
        $this->assertEquals(['home.cta_text:1640652282', 'home:39409096'], $activeVariants);
    }

    /**
     * @test
     */
    public function shouldNotReturnAnyVariantValueWhenItsPredicateFieldIsMissingInContext()
    {
        // Arrange
        $this->client->context->set('native.newUser', true);
        $this->client->context->set('native.pageCategory', 'home');

        // Act
        $predicatedVariantValue = $this->client->get('home.cta_text');
        $activeKeys = $this->client->context->get('keys.active');
        $activeVariants = $this->client->context->get('variants.active');

        // Assert
        $this->assertEquals(null, $predicatedVariantValue);
        $this->assertEquals(['home.cta_text', 'home'], $activeKeys);
        $this->assertEquals(['home:-462338277'], $activeVariants);
    }
}
