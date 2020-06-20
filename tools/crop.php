<?php

namespace Hp;

//  PROJECT HONEY POT ADDRESS DISTRIBUTION SCRIPT
//  For more information visit: http://www.projecthoneypot.org/
//  Copyright (C) 2004-2020, Unspam Technologies, Inc.
//
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; either version 2 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
//  02111-1307  USA
//
//  If you choose to modify or redistribute the software, you must
//  completely disconnect it from the Project Honey Pot Service, as
//  specified under the Terms of Service Use. These terms are available
//  here:
//
//  http://www.projecthoneypot.org/terms_of_service_use.php
//
//  The required modification to disconnect the software from the
//  Project Honey Pot Service is explained in the comments below. To find the
//  instructions, search for:  *** DISCONNECT INSTRUCTIONS ***
//
//  Generated On: Sun, 31 May 2020 14:58:14 -0400
//  For Domain: cjthedj97.me
//
//

//  *** DISCONNECT INSTRUCTIONS ***
//
//  You are free to modify or redistribute this software. However, if
//  you do so you must disconnect it from the Project Honey Pot Service.
//  To do this, you must delete the lines of code below located between the
//  *** START CUT HERE *** and *** FINISH CUT HERE *** comments. Under the
//  Terms of Service Use that you agreed to before downloading this software,
//  you may not recreate the deleted lines or modify this software to access
//  or otherwise connect to any Project Honey Pot server.
//
//  *** START CUT HERE ***

define('__REQUEST_HOST', 'hpr5.projecthoneypot.org');
define('__REQUEST_PORT', '80');
define('__REQUEST_SCRIPT', '/cgi/serve.php');

//  *** FINISH CUT HERE ***

interface Response
{
    public function getBody();
    public function getLines(): array;
}

class TextResponse implements Response
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getBody()
    {
        return $this->content;
    }

    public function getLines(): array
    {
        return explode("\n", $this->content);
    }
}

interface HttpClient
{
    public function request(string $method, string $url, array $headers = [], array $data = []): Response;
}

class ScriptClient implements HttpClient
{
    private $proxy;
    private $credentials;

    public function __construct(string $settings)
    {
        $this->readSettings($settings);
    }

    private function getAuthorityComponent(string $authority = null, string $tag = null)
    {
        if(is_null($authority)){
            return null;
        }
        if(!is_null($tag)){
            $authority .= ":$tag";
        }
        return $authority;
    }

    private function readSettings(string $file)
    {
        if(!is_file($file) || !is_readable($file)){
            return;
        }

        $stmts = file($file);

        $settings = array_reduce($stmts, function($c, $stmt){
            list($key, $val) = \array_pad(array_map('trim', explode(':', $stmt)), 2, null);
            $c[$key] = $val;
            return $c;
        }, []);

        $this->proxy       = $this->getAuthorityComponent($settings['proxy_host'], $settings['proxy_port']);
        $this->credentials = $this->getAuthorityComponent($settings['proxy_user'], $settings['proxy_pass']);
    }

    public function request(string $method, string $uri, array $headers = [], array $data = []): Response
    {
        $options = [
            'http' => [
                'method' => strtoupper($method),
                'header' => $headers + [$this->credentials ? 'Proxy-Authorization: Basic ' . base64_encode($this->credentials) : null],
                'proxy' => $this->proxy,
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $body = file_get_contents($uri, false, $context);

        if($body === false){
            trigger_error(
                "Unable to contact the Server. Are outbound connections disabled? " .
                "(If a proxy is required for outbound traffic, you may configure " .
                "the honey pot to use a proxy. For instructions, visit " .
                "http://www.projecthoneypot.org/settings_help.php)",
                E_USER_ERROR
            );
        }

        return new TextResponse($body);
    }
}

trait AliasingTrait
{
    private $aliases = [];

    public function searchAliases($search, array $aliases, array $collector = [], $parent = null): array
    {
        foreach($aliases as $alias => $value){
            if(is_array($value)){
                return $this->searchAliases($search, $value, $collector, $alias);
            }
            if($search === $value){
                $collector[] = $parent ?? $alias;
            }
        }

        return $collector;
    }

    public function getAliases($search): array
    {
        $aliases = $this->searchAliases($search, $this->aliases);
    
        return !empty($aliases) ? $aliases : [$search];
    }

    public function aliasMatch($alias, $key)
    {
        return $key === $alias;
    }

    public function setAlias($key, $alias)
    {
        $this->aliases[$alias] = $key;
    }

    public function setAliases(array $array)
    {
        array_walk($array, function($v, $k){
            $this->aliases[$k] = $v;
        });
    }
}

abstract class Data
{
    protected $key;
    protected $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function key()
    {
        return $this->key;
    }

    public function value()
    {
        return $this->value;
    }
}

class DataCollection
{
    use AliasingTrait;

    private $data;

    public function __construct(Data ...$data)
    {
        $this->data = $data;
    }

    public function set(Data ...$data)
    {
        array_map(function(Data $data){
            $index = $this->getIndexByKey($data->key());
            if(is_null($index)){
                $this->data[] = $data;
            } else {
                $this->data[$index] = $data;
            }
        }, $data);
    }

    public function getByKey($key)
    {
        $key = $this->getIndexByKey($key);
        return !is_null($key) ? $this->data[$key] : null;
    }

    public function getValueByKey($key)
    {
        $data = $this->getByKey($key);
        return !is_null($data) ? $data->value() : null;
    }

    private function getIndexByKey($key)
    {
        $result = [];
        array_walk($this->data, function(Data $data, $index) use ($key, &$result){
            if($data->key() == $key){
                $result[] = $index;
            }
        });

        return !empty($result) ? reset($result) : null;
    }
}

interface Transcriber
{
    public function transcribe(array $data): DataCollection;
    public function canTranscribe($value): bool;
}

class StringData extends Data
{
    public function __construct($key, string $value)
    {
        parent::__construct($key, $value);
    }
}

class CompressedData extends Data
{
    public function __construct($key, string $value)
    {
        parent::__construct($key, $value);
    }

    public function value()
    {
        $url_decoded = base64_decode(str_replace(['-','_'],['+','/'],$this->value));
        if(substr(bin2hex($url_decoded), 0, 6) === '1f8b08'){
            return gzdecode($url_decoded);
        } else {
            return $this->value;
        }
    }
}

class FlagData extends Data
{
    private $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function value()
    {
        return $this->value ? ($this->data ?? null) : null;
    }
}

class CallbackData extends Data
{
    private $arguments = [];

    public function __construct($key, callable $value)
    {
        parent::__construct($key, $value);
    }

    public function setArgument($pos, $param)
    {
        $this->arguments[$pos] = $param;
    }

    public function value()
    {
        ksort($this->arguments);
        return \call_user_func_array($this->value, $this->arguments);
    }
}

class DataFactory
{
    private $data;
    private $callbacks;

    private function setData(array $data, string $class, DataCollection $dc = null)
    {
        $dc = $dc ?? new DataCollection;
        array_walk($data, function($value, $key) use($dc, $class){
            $dc->set(new $class($key, $value));
        });
        return $dc;
    }

    public function setStaticData(array $data)
    {
        $this->data = $this->setData($data, StringData::class, $this->data);
    }

    public function setCompressedData(array $data)
    {
        $this->data = $this->setData($data, CompressedData::class, $this->data);
    }

    public function setCallbackData(array $data)
    {
        $this->callbacks = $this->setData($data, CallbackData::class, $this->callbacks);
    }

    public function fromSourceKey($sourceKey, $key, $value)
    {
        $keys = $this->data->getAliases($key);
        $key = reset($keys);
        $data = $this->data->getValueByKey($key);

        switch($sourceKey){
            case 'directives':
                $flag = new FlagData($key, $value);
                if(!is_null($data)){
                    $flag->setData($data);
                }
                return $flag;
            case 'email':
            case 'emailmethod':
                $callback = $this->callbacks->getByKey($key);
                if(!is_null($callback)){
                    $pos = array_search($sourceKey, ['email', 'emailmethod']);
                    $callback->setArgument($pos, $value);
                    $this->callbacks->set($callback);
                    return $callback;
                }
            default:
                return new StringData($key, $value);
        }
    }
}

class DataTranscriber implements Transcriber
{
    private $template;
    private $data;
    private $factory;

    private $transcribingMode = false;

    public function __construct(DataCollection $data, DataFactory $factory)
    {
        $this->data = $data;
        $this->factory = $factory;
    }

    public function canTranscribe($value): bool
    {
        if($value == '<BEGIN>'){
            $this->transcribingMode = true;
            return false;
        }

        if($value == '<END>'){
            $this->transcribingMode = false;
        }

        return $this->transcribingMode;
    }

    public function transcribe(array $body): DataCollection
    {
        $data = $this->collectData($this->data, $body);

        return $data;
    }

    public function collectData(DataCollection $collector, array $array, $parents = []): DataCollection
    {
        foreach($array as $key => $value){
            if($this->canTranscribe($value)){
                $value = $this->parse($key, $value, $parents);
                $parents[] = $key;
                if(is_array($value)){
                    $this->collectData($collector, $value, $parents);
                } else {
                    $data = $this->factory->fromSourceKey($parents[1], $key, $value);
                    if(!is_null($data->value())){
                        $collector->set($data);
                    }
                }
                array_pop($parents);
            }
        }
        return $collector;
    }

    public function parse($key, $value, $parents = [])
    {
        if(is_string($value)){
            if(key($parents) !== NULL){
                $keys = $this->data->getAliases($key);
                if(count($keys) > 1 || $keys[0] !== $key){
                    return \array_fill_keys($keys, $value);
                }
            }

            end($parents);
            if(key($parents) === NULL && false !== strpos($value, '=')){
                list($key, $value) = explode('=', $value, 2);
                return [$key => urldecode($value)];
            }

            if($key === 'directives'){
                return explode(',', $value);
            }

        }

        return $value;
    }
}

interface Template
{
    public function render(DataCollection $data): string;
}

class ArrayTemplate implements Template
{
    public $template;

    public function __construct(array $template = [])
    {
        $this->template = $template;
    }

    public function render(DataCollection $data): string
    {
        $output = array_reduce($this->template, function($output, $key) use($data){
            $output[] = $data->getValueByKey($key) ?? null;
            return $output;
        }, []);
        ksort($output);
        return implode("\n", array_filter($output));
    }
}

class Script
{
    private $client;
    private $transcriber;
    private $template;
    private $templateData;
    private $factory;

    public function __construct(HttpClient $client, Transcriber $transcriber, Template $template, DataCollection $templateData, DataFactory $factory)
    {
        $this->client = $client;
        $this->transcriber = $transcriber;
        $this->template = $template;
        $this->templateData = $templateData;
        $this->factory = $factory;
    }

    public static function run(string $host, int $port, string $script, string $settings = '')
    {
        $client = new ScriptClient($settings);

        $templateData = new DataCollection;
        $templateData->setAliases([
            'doctype'   => 0,
            'head1'     => 1,
            'robots'    => 8,
            'nocollect' => 9,
            'head2'     => 1,
            'top'       => 2,
            'legal'     => 3,
            'style'     => 5,
            'vanity'    => 6,
            'bottom'    => 7,
            'emailCallback' => ['email','emailmethod'],
        ]);

        $factory = new DataFactory;
        $factory->setStaticData([
            'doctype' => '<!DOCTYPE html>',
            'head1'   => '<html><head>',
            'head2'   => '<title>corporeal zealous cjthedj97.me lane</title></head>',
            'top'     => '<body><div align="center">',
            'bottom'  => '</div></body></html>',
        ]);
        $factory->setCompressedData([
            'robots'    => 'H4sIAAAAAAAAA7PJTS1JVMhLzE21VSrKT8ovKVZSSM7PK0nNK7FVystPLErOyCxL1cnLz8xLSa3QScvPyckvV7IDANCDo6c3AAAA',
            'nocollect' => 'H4sIAAAAAAAAA7PJTS1JVMhLzE21VcrL103NTczM0U3Oz8lJTS7JzM9TUkjOzytJzSuxVdJXsgMAKsBXli0AAAA',
            'legal'     => 'H4sIAAAAAAAAA6VaXXPbthJ9v78C17mTJjNOYufLzjD1jOqoiTqpk2spyfQRIiESDQmwAGhH_fV3sQuQoEwp6dwH2ZRIgsDu2bNnF3zt-LoWLBd1bVueS1X-fHRyhN9bXhTx-1qbQhh_ePHamYt_vXYFHLiL-2pt24zRv_Dl9dpcvN5o5Viua21-vq2kExf69RP_24U_u6oEu_UHTrOt9gfaVcL4gy9iff_e6emLTPpvRvACx-OlUA7vqETz2B8orR65cIfFi_2nn8KhGTxZXyRTpUN_tGxFDg8_eYHfrvw8TjP16J3_ZltZCH_QGl3iTHnndBOfO2-49Lee460Lx6TFa-owCN5a3b_36iyzDP_hoE4b7g9uON3cCfqZ7vJ_n5Mh8JbS-KtOM7Gz2jvWH5Y053nFhgG4VP7Lg6PfuiJYk-FibIcrf57dNZtNHIejKH_lScZadNyGbXA-XG0ZrmUpaN43Mj6Bj-d7yEfFHR-N1_OH7ljuDxpvsaenGQ3O153FWXyWljzo4jM_L5b37509y1b-y8fZ9eqP1_9-9Ijx3Aj26BFesvx0_97Llxlc9_IVPuX1E-enABj_PtDh4wGIz-v2W892YLvzZDVOGESPI5-GOHAVfW1wcA8VD4RN4lWInQNm8zHjLXN6ktkNDigex2lOGnxzNyh2VsdrRg4VhIDT07Os4eruUC6dhD1ma7zbr-dVZtHoTtQi103TKZlzJ7XyHqDHFT28HJwbAwZ4yCTwocnkmu54_jJjgIrhfB9L_qdSHBNC6BygFR2lSuYqhJHwaD59OSz3-xgVkxhFbuDGbSmCGQ1bBZsBInPJa0RLKa2LP59nuOZbi88XZqPRUGCkthbf0Dy4XH8WCJgPHJM7vLKQnVHgIroSHqMxwnNwuz9tNLhK2L86wTpV-PE1cJMSN5J4v9Dd2q9OFdL7QxSDR_7w46z1jWD_J4C0sgIn1bYEbo7efMw8lP15YweK-HC9ZLPU2Wev4M_5Uxj13vkzXPl_P82vVnvCdHKK9rtTDDG4IWMjsv6k33IXIrGkULzBOGfgiFevMmSinBuxiaEBHg9jIfvxPBeYCAIngs0HY-7nb4KQkfiQQGsyYgYm4SC6cFT08RpytSyFgQzJSq3RhVaWasBYve1JQFhLMyqV_FskMYbP-oo3CUYMoilWiicaHVYJS1iQln0JnB9SwW5GmnSDm86_-Nl7MSL-WWZE4iZNsUtjzHInKXZEkApGDBm2gR_TVapijzyJDLpGk0pb80bm3pKGIpJ3ECGs5qrsQIuwW22-YgDBFUg0eoL7eRgzJ68leVzQSg4GVhvNdeeM3Hum3G_iyEg6ySWktHQjpm0Sn7MgL9OcESG9EumHf3s9nwehAUn06s3ianjML9fzGRqx0Uq3lQb2B0GpjbQVq-VfnSzYRpZAS85wLzy7mpuYm_9hKm5wla-ySg4RSdYPhE_zNoqEEIbl08xRVujqmkTF43jzSVZI0jGWGeLXkiLkVrrBe-jB5cf55cIbvrc4EKoNBPeYAgco-BveX0RvjAbZi6CgIhlx_sbnTzRSzotBxhT7mc9VSLD6thbF4JeajEEosCNYgLtDlvLJF_HhRQ4efGtxFl3bCpPX3FoME2KVYZAflOC7UsqO_bQf4uRArY4e7jdbwOOLrGhkkoHOM4kLUY5caoLq5rS-kpSwo6QEyYuQ4IJecKg5foTCSe_7u-GJNIj_-RZKHXxQeLz_-zROSQcs-s_qHRoakrf1WEK-UZCQayY2GwG8B7k5r7gBPjLV1lWBsWYrnMFbNrvCHH75jn349Z_ky_y7-RKKCtKrgm30oElKkNV9zurTxX7NMIOaITr7JOvQXPUDvNnZu7d9DbdhCFa6c4kywRBEX79PddnF-1FIBhVIviD_jsu8ADsoIp5P1UIjYFFGQRdVgteuYkBfIPKYgywBxZC1Gr46AWeN9o4BMZBOuII8aomaXTUgz9c0angAhO4AnVARrkmFI1CspZssktZPE2aLqVRgvWHrLVVu30Mvd_sdl04QHa5VlBtAJRWva9IJjsrbgjlKO_Bs4mc5DYyY83LdDbrXV60JO4XimI1suQvTi-vU7SWglUyWgEBRoBlI9jg3zUIN6T_vZtef58PFv2NWWy6HX1bv5r0g3Z-jYCKrOZU-pmtaUXQtcx2UQdxsSbKDsk1nCsoyR1VZ69uQoiwZlWYbqkXTkEHunZ3DQgCsLzLqUjyM-Q-XxhUlDAs1AMsN_3vL7C04R0O9YSttwD2qxPxBfDRVWlYRPTuxdkmJfjm_G1830vbpF_kPCkCu0MhGr3WA7TF1HuJHUkKcmEGkI4SSnbjg6wjgBePlAB6a0Sf0FfsPuUK3zIi8Bnczd6tboYCGcm0doHMj_ZeQUHt6xtyrh-EWijIFlzWRiNGVXCfGmX38-B67DqlxwPgdSVWg7mS0tWAl6YVQa3EQiMEhkszmLAgOoopKkkHjBM-zWKiYHQ_NL4fq6U06EcMW1EzzDIRcAILir90ACeWM7PP6EkZ6ni2u3vZpFcY_zSAOnp9kGBpztkJUXO_PNr9T1KQUPbtKJyeCHBGUFCh8c6pqNXOUmyck_YHGxGYkB5A8giRXkZ1IV9UcBBLpmaGw_0YaMRE4w8M90czJLqMwIG1HKPyJFuR__yxDb5NCAJMMxOWQeDADFsejvBb6HYwaHj3khOiV0otn2cljhrl-sz98YmvJX6a8nsC1F2F4XJyketJoXjAQvrz2JaXiriOR6UQx0j6Ucc-y2S_vRyzgjoHF8DH7YzWkATSEJokkTMghMCRcgqv0esvu5aZeRwxGjxFD_oEvOBEoOUiXHOxuJutajhAK8ulgUyFxFRYXgpQzUCuKGQHE0iSILfp6KpAU3TupOGJd-naGLY-HbPUB1_f78qCoezOa_-WHUYjt1-VJCyNUBFD1hr7SsIAvAnuZeD7XsU4igIc-hTO6xvPbKGGFYqYDf7SyFFAXeTzpjmHtDORm5I4DrheXqwnpVode2gBl3z0aWuQQv4am1bSjIZUdxPYJ8Udwg6FGmVyzTuW-Jeb3HHxKpA6-Y9RlaUF4G7dlghvb1tI5qbBkFaUBjVfA-daXZBIu4cUNwDYEDR9Yp4PKjXIYXIKD1yzpDVgRVnKHY97PvlBbsNagMn12utN6JM_JmKyQ3ZmeqJ9SwIuJkjF2N9fYyMwde-DSnAB-F_Ss24j4k6yJaaoS1LwDOShyWpO52XXt58Vysbrr2o8mNLfiSEdk_5y0seflTbzj3fyaGgwgw9hiGGM5v_58ICgWq8WHw4Fg-0CgPk007YnX13vLAQxN6Vvt-2spv6jY24U6DdIWqDPBarEBha8KQBDkrlp-RdD0nQPiCEviD2i1I9sjogOUv-NjF3SV2i2WPlwt96_o19RMirKWCL3zvKZcWyRJjPoL9QFxpkfyxDfrWgJIkLd9yy6v9k-LFJ6Jm4UFJGW0U12DpiIqCZ2pfGe1qFeuxrT4W7pIbVhL-3omytdUBhEJWF3znJwG8-uMg3tE4TczxFg0ePhiJIROn6K4WMYeAzYkMNny_Kvw2RZ-6IQqNBPfcukJ5U_hxmNCzg6FK5opNkM3d1Z65hVYutBPy9FKwbI4xKIIm6rFqDcIlqD6zn_eHrPlinYaujqHKfHay1TRmW1b8VJ3wFlQ2Bjfj4i6-Q2VBisMzMXlfBANk2F5dSfZpnKwSdsvvqdwGO6_bNnN_X4PlwZ90PtwvashK58-Ypj4HxnGMiyo2aWtD2ObYu01YhMdazdxzJAokezNTUC4m2acqGFKTnKujxDdmrAHHoEp6yFKgpQIFAWZw-LTgDiOD5OQ9o3_naV9ul4sRxWCRrUkqFkIeW6DEwB5TFUQond_wzpcgXZ9HDfneujfhkqvSnRaG7h_N2rnO4XCaJKcCLAwgxiQGykGWbWFCMWHbKHeS3aYQgnz62JOO0v9A3-wPxZd9mk5n0jEBLmjACjElgj1OogjmXROjlbJZIO0orWsQ9-YUbHs76dtKBt0e2Kjq0cAxafZp99HYWNDzpho4kQy5aR8bCVb1jC3bUXOLQT4X52spRraAsLE7aaJVFP0HZt0WsKFwRtZAENtJJBmKRoor00DBY-GdQCq4AEoMvRNeLfCt5ppuyxun6LX1taFhhOalENI9dTq23h2xyJeOo4iMzRfS9qS1g1LnEPd10Ot8wFePYaXjraqkzo0Dyl7QgNEvIRIWAMY8-ikcREwDOfh_suYt2MjiEL-CFjVbzNuQukqhY38uwhtn7UOtWzopfsxtHocx_MsBYXrNWYmKBECzyvQJwqqyxrAELx0MECCx9kXsU6IyVAro2QPCPFNmDylK7-TsFd5WmJ6Qx0PvWHLxMxxBz9xN_UPerN9HpUNU2Vj3ou887PsO3tfKojSvscAhgzpUYVmnBo02diHUsWelsc225AE8jscA6T6FwWo4SPxjaHQTkf7JzZld6T01V3-MaHTWVbklpVI3p3wexwIHuwTAu6p8dFy_yYSK6Wprd-kk_6bhiTI_cENRK8RJb1kMIxl0t0j6mvFdykGg4TYCGVJ8KylLILx-wygUhxiffo1D0q4SKBwGxQQjgo1N_uYjnO1_HWOfajF6tNq_gMItrEDiXUHpC-ebJXxuFNBZQZUNbgU2rEgNQVVXUPNQG8wWinT1OfYWeBygZH3YdSkXg8VasRMDarQUQLTKWXhJDYhZzbp4L0QpmuqrtlRDGntvj8EfflMzyWOlQ0RRknwARXsdqAYOj9sTXYahzvYgV5hMnGno4h9HT2KBt9vwicQq63l39wAk3BpmIX05BCPvSgOY3mS4lOzKQ5w8WzYVOn80BQ6To8UiFcKOG__TgrmylZ_hUqq1bX0rynVcGTcBr5pYGUoDjqqA64OSwo69TBuPOCrN2g0ruhFQcDP0UPEhGCjlwdOT858q25vkz55DYpTmlCR_PElBlOA_XzTwmx8HdNbcnhGitJ94oa3bR3ncxo6Kul-9pRO6Hd2kuVEJ44q085bFWcOxENdyb07A_2geBmAclD-obwylVQa6Ev75krXQp1VSkdMJm0VEs0QdwQIreJ7d1Oosh1UqDh2pxRiz4A9ZXgHa-edxWUwP9IFQEQmnELpZ70nFGOSEipVGX64l1lHlYGO0TRVFEXbrPCK3N0RjqlHRcPD9kKrQ4QH-uuvQ8JiM_TV7PJyPpDtfoi3RlPKUnED0Dzsu9bPMVGcn2Q1ARVsMxBB7DgM1e1EZzt2LQM1DnfvfrbMDbA7ZrekVJq2c8M9o9eIiQ3aRJ77lGjjzCCVOTJYqMiPEfYTgbkZT9FfVh7v7O8m3dbdT_9Cocxpn1nX9dZ3SwFxgz5XsRcQgxKKzeQdKN9roXdYCpkmhxuhqEJt6O3Mhhsv-xtd4Lsl4hs8xL9aCAvhqgC0s3XN868DzPXgKUaiQxVTq_BYKgK9S9oQjo2bEbzeLa7f-C8ratZc_kjWLqhGhfw8MBL-QHu5JtlG-pEPaiU32ut029Gmh-rnTYnsiTbfG5RATDIlAWKISMFIIW8CdU4Fadijud_vJNV9DE0H9OwKbHg-nEwM-ATf33-C73_CAZz9H5_MTKYEMAAA',
            'style'     => 'H4sIAAAAAAAAAyXMOw6AIAwA0KuYuIqfFYgj9yhQIglpDe2gMd7dwXeA50XvhjvMAgfHJ3HjbscQgitMaiO3PGzreQ3QK7RJgMQI9lqc4qUmY-IOWpksMaF7_fKHH2ld_DJYAAAA',
            'vanity'    => 'H4sIAAAAAAAAA22S207DMAyGX8XKbmEdp0nL2goxDSEk2DTggsu0ydpAiCPHrOztScu44KDIkh3F3_87Sc6qcgZq41wMqra-KcRE9GVQWh_KCkkb6rPIe2cKUan6tSF891qOZrPZvLOaW3l6Ngkfc1HmTCk07JSzjS8EY_huPEAlnIQPOE1xkeI8dX1JHJNtWpYRndXDkdFiseiJyZuHA2OLnmWFTkOvB4qsckdR-XgcDdntvEaHJEfT6XSelGXvKWC0bNFLMk6x3ZnEvMyznlrmGes_duGQO7NlAb_MnyXVSVrnX9MqaMlsC9EyB5llXdeNA-GLqblFb_YBeYzUZAJqp2IsRFQtVqK8W95dLTewuob1ZnW7XDzCzep--Qzr1WOeqTKv6F_0u0-u38Y1vomfvIe0DTeKdiayIVgTcrKQhoZ7wx3Sa09MxnZWGw3VHp4G0qA1XEHWP1s2_IfyEy7Ky9kXAgAA',
        ]);
        $factory->setCallbackData([
            'emailCallback' => function($email, $style = null){
                $value = $email;
                $display = 'style="display:' . ['none',' none'][random_int(0,1)] . '"';
                $style = $style ?? random_int(0,5);
                $props[] = "href=\"mailto:$email\"";
        
                $wrap = function($value, $style) use($display){
                    switch($style){
                        case 2: return "<!-- $value -->";
                        case 4: return "<span $display>$value</span>";
                        case 5:
                            $id = 'nohocr3';
                            return "<div id=\"$id\">$value</div>\n<script>document.getElementById('$id').innerHTML = '';</script>";
                        default: return $value;
                    }
                };
        
                switch($style){
                    case 0: $value = ''; break;
                    case 3: $value = $wrap($email, 2); break;
                    case 1: $props[] = $display; break;
                }
        
                $props = implode(' ', $props);
                $link = "<a $props>$value</a>";
        
                return $wrap($link, $style);
            }
        ]);

        $transcriber = new DataTranscriber($templateData, $factory);

        $template = new ArrayTemplate([
            'doctype',
            'injDocType',
            'head1',
            'injHead1HTMLMsg',
            'robots',
            'injRobotHTMLMsg',
            'nocollect',
            'injNoCollectHTMLMsg',
            'head2',
            'injHead2HTMLMsg',
            'top',
            'injTopHTMLMsg',
            'actMsg',
            'errMsg',
            'customMsg',
            'legal',
            'injLegalHTMLMsg',
            'altLegalMsg',
            'emailCallback',
            'injEmailHTMLMsg',
            'style',
            'injStyleHTMLMsg',
            'vanity',
            'injVanityHTMLMsg',
            'altVanityMsg',
            'bottom',
            'injBottomHTMLMsg',
        ]);

        $hp = new Script($client, $transcriber, $template, $templateData, $factory);
        $hp->handle($host, $port, $script);
    }

    public function handle($host, $port, $script)
    {
        $data = [
            'tag1' => '696b13c0ce5b80f1858565d7d0ae7419',
            'tag2' => '87f0432f6877739a8b4100ada981d7f5',
            'tag3' => '3649d4e9bcfd3422fb4f9d22ae0a2a91',
            'tag4' => md5_file(__FILE__),
            'version' => "php-".phpversion(),
            'ip'      => $_SERVER['REMOTE_ADDR'],
            'svrn'    => $_SERVER['SERVER_NAME'],
            'svp'     => $_SERVER['SERVER_PORT'],
            'sn'      => $_SERVER['SCRIPT_NAME']     ?? '',
            'svip'    => $_SERVER['SERVER_ADDR']     ?? '',
            'rquri'   => $_SERVER['REQUEST_URI']     ?? '',
            'phpself' => $_SERVER['PHP_SELF']        ?? '',
            'ref'     => $_SERVER['HTTP_REFERER']    ?? '',
            'uagnt'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        $headers = [
            "User-Agent: PHPot {$data['tag2']}",
            "Content-Type: application/x-www-form-urlencoded",
            "Cache-Control: no-store, no-cache",
            "Accept: */*",
            "Pragma: no-cache",
        ];

        $subResponse = $this->client->request("POST", "http://$host:$port/$script", $headers, $data);
        $data = $this->transcriber->transcribe($subResponse->getLines());
        $response = new TextResponse($this->template->render($data));

        $this->serve($response);
    }

    public function serve(Response $response)
    {
        header("Cache-Control: no-store, no-cache");
        header("Pragma: no-cache");

        print $response->getBody();
    }
}

Script::run(__REQUEST_HOST, __REQUEST_PORT, __REQUEST_SCRIPT, __DIR__ . '/phpot_settings.php');

