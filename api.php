<?php
header("Access-Control-Allow-Origin: *");
require('./configs.php');
require('./vendor/autoload.php');

use GuzzleHttp\Client;

$api = new Client([
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Mobile Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Cache-Control' => 'no-cache',
        'X-Requested-With' => 'XMLHttpRequest',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive'
    ],
    'base_uri' => URI,
    'cookies' => true,

]);

//&###############################################
//############### inputs data ####################
//################################################

$post = $_POST;

function postFunction356($user356, $pass356, $method, $betValue, $hc, $ls)
{
    global $api, $body;

    if ($user356 == true) {
        $api->post(LOGIN, [
            'form_params' => [
                'txtUserName' => $user356,
                'txtPassword' => $pass356,
                'txtType' => 47
            ]
        ]);

        if ($method == 'login') {
            $response = $api->get(URI . PROFILE);
            $content = $response->getBody()->getContents();
            $string = substr($content, 19974, 19994);
            preg_match_all('/([\w\d\.\-\_]+)@([\w\d\.\_\-]+)/mi', $string, $matches);
            $body->data = $matches[0];
        }

        if ($method == 'profile') {
            $response = $api->post(LOGIN, [
                'form_params' => [
                    'txtUserName' => $user356,
                    'txtPassword' => $pass356,
                    'txtType' => 47
                ]
            ]);
            return $response->getBody()->getContents();;
        }

        if ($method == 'balance') {
            $response = $api->get(URI . BALANCE);
            $body->data = $response->getBody()->getContents();
        }

        if ($method == 'vsports') {
            $response = $api->get(URI . VSPORTS);
            return $response->getBody()->getContents();
        }

        if ($method == 'sendBet') {
            try {
                $response = $api->post('https://www.bet365.com/' . PULLBET, [
                    'form_params' => [
                        'bs' => '1',
                        //'ns' => 'f=88960914#fp=750885681#st='.$betValue,
                        'ns' => 'o='.$ls.'#ln='.$hc.'#f=88960920#fp=752036435#st=' . $betValue,
                        //'ns' => '',
                        'betsource' => 'FlashInPLay',
                        'tagType' => 'WindowsDesktopBrowser'
                    ]
                ]);
                return $response->getBody()->getContents();
            } catch (Error $err) {
                return $body->data = $err;
            }
        }

        if ($method == 'addBet') {
            try {
                $response = $api->post('https://www.bet365.com/' . ADDBET, [
                    'form_params' => [
                        'bs' => '1',
                        'ns' => 'pt=N#o=10/3#f=88952643#fp=750512621#|TP=BS88952643-750512621#||'
                    ]
                ]);
                return $response->getBody()->getContents();
            } catch (Error $err) {
                return $body->data = $err;
            }
        }
    } else {
        $body->data = 'invalid-token';
    }
    return json_encode($body);
}

echo postFunction356($post['user356'], $post['pass356'], $post['method'], $post['betValue'], $post['handCap'], $post['ls']);
