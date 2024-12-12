<?php

namespace Controller\ApiController;

use Pet\Controller\Controller;
use Pet\Request\Request;
use Model\ParsersModel\ParsersModel;
use api\parser;
use Exception;
use Model\ClientModel\ClientModel;
use Model\FilialModel\FilialModel;
use Model\ReviewsModel\ReviewsModel;
use Pet\Inertia\Inertia;
use Lib\Edna\Edna;
use Lib\ImapGet\ImapGet;
use Lib\Matchs\Matchs;
use Lib\Sms\Sms;
use Model\AnaliticsModel\AnaliticsModel;
use Model\Parser_listModel\Parser_listModel;
use Pet\Validator\Validator;

use function PHPSTORM_META\type;

class ApiController extends Controller {

    public function updateReviews(Request $request) {

        $parser = (new ParsersModel)->find([], [], ['parsers-list' => 'parser_id']);
        $count = 0;
        $response = [];

        foreach ($parser as $row) {

            if ($row['name'] == explode('-', supple('parser'))[0]) {
                $count++;

                $response[] = (new parser())->parse(supple('parser'), $row['setting']);

                $i = count($response) - 1;
                if (gettype($response[$i]) == 'array') {
                    $data = [
                        'api' => $row['api'],
                        'date' => date('Y-m-d'),
                        'filial' => $row['filial'],
                        'count' => $response[$i]['reviews-all'],
                        'star' => $response[$i]['star'],
                        'real_count' => '',
                        'platform' => $row['name'],
                    ];
                    (new AnaliticsModel())->set($data);
                    $response[$i] = (new ReviewsModel($row['api']))->set($response[$i]['reviews'], $row['filial'], $row['name']);


                    $response[$i] = array_merge(["API" => $row['api'], "FILIAL" => $row['filial']], $response[$i]);
                }
            }
        }

        $response[] = ["COUNT" => $count];
        $request->set(['response' => $response]);
        $this->json();
    }



    // страница формы запроса.
    function feedback(Request $request) {

        // dd(supple());
        $filial = (new FilialModel())->find(supple(), ['company.api', 'filial', 'company', 'filial.id', "company_id"], ['company' => 'company_id']);
        count($filial) != 0 ?

            $request->set($filial[0]) :

            $request->redirect_code(404);

        Inertia::render('feedback', $request);
    }


    function sendClient(Request $request) {

        date_default_timezone_set(env('TIME_ZONE', 'Europe/Moscow'));

        $data = (new ClientModel())->setStack($request);


        if (gettype($data) == 'array') {
            $url  = (new parser())->config(false);
            file_get_contents($url['PROTOCOL'] . '://' . $url['IP'] . '/callback-send-rang.php');
            $this->json(["request" => "Отправлено"]);
        }


        if (gettype($data) == 'string') $this->json(["request" => $data]);
    }






    public function match() {
        $clients = (new ClientModel())->find(['path' => "POSITIVE"], ['name', 'platform_path', 'date_path', 'url', 'api', "id", "filial"]);
        $count = count($clients);
        $countResult = 0;
        $namesResult = [];

        foreach ($clients as $client) {
            if (strtotime($client['date_path']) < mktime(0, 0, 0, date('m') - 1, date('d'), date("Y"))) {
                (new ClientModel())->status_path($client['url'], "DESTROYED", null, null, null);
                continue;
            }

            $search = [
                'platform' => [$client['platform_path']],
                'filial' => $client['filial'],
                'st' => explode(' ', $client['date_path'])[0],
                'en' => date('Y-m-d'),
            ];

            $rewiews = (new ReviewsModel($client['api']))->getReviews($search);
            // Подготовка данных клиента
            try {
                $client['name'] = explode(' ', $client['name']);
                $FAMILY = mb_strtolower(strtolower(trim($client['name'][0])));
                $NAME = mb_strtolower(strtolower(trim($client['name'][1])));
                $SURNAME = !empty($client['name'][2]) ? mb_strtolower(trim($client['name'][2])) : " ";
            } catch (Exception $e) {
                continue;
            }

            foreach ($rewiews as $row) {
                $result = null;
                $text = mb_strtolower(str_replace(['.', ','], '', $row['text']));
                $nameIncoming = mb_strtolower((str_replace(['.', ','], '', $row['name'])));
                $options = include __DIR__ . '/../lib/Match.php';

                foreach ($options as $option) {
                    if (str_contains($nameIncoming, $option) || str_contains($text, $option)) {
                        $result = 'FAMILY';
                        break;
                    }
                }

                if ($result) {
                    (new ClientModel())->status_path($client['url'], $result, null, null, $row['text']);
                    $countResult++;
                    $namesResult[] = '<i>' . $row['name'] . " -- status: " . $result . "</i>";
                    $result = null;
                    break;
                }
            }
        }

        echo "Принято к сверки клиентов: $count ; <br> Сверилось: $countResult <br><b>Имена</b> <br>" . implode('<br>', $namesResult);
    }


    public function updateImap(Request $request) {
        $filials = (new FilialModel())->find([], ['filial', 'imap', 'api']);
        $result = [];

        foreach ($filials as $filial) {
            if ($filial['imap']) {

                $imap = json_decode($filial['imap'], true);
                $imap['filial'] = $filial['filial'];
                $imap['api'] = $filial['api'];

                $reviews = (new ImapGet())->getReviews($imap);
                if (count($reviews) != 0) {
                    foreach ($reviews as $prefix => $row) {
                        $result[] =  (new ReviewsModel($filial['api']))->set($row, $filial['filial'], $prefix);
                    }
                }
            }
        }

        $request->set(['response' => $result]);
        $this->json();
    }


    public function paralele(Request $request) {
        $data = [];
        // сначала идем собираем list Parser
        $list_parsers = (new Parser_listModel())->find();
        $parsers = new ParsersModel();


        foreach ($list_parsers as $list) {
            $pars = $parsers->find(['parser_id' => $list['id']]);
            $data[$list['name']] =  [];

            foreach ($pars as $filial) {
                $fil = json_decode($filial['setting'], true);
                $fil['api'] = $filial['api'];
                $fil['filial'] = $filial['filial'];
                $data[$list['name']][] = $fil;
            }
        }

        $sendParalele = new parser();
        $data =  $sendParalele->parse('paralele', json_encode($data));
        set_time_limit(10);
        dm($data);
    }





    public function updateReviewsCallback() {

        $parser = new parser;
        $conf = $parser->config(false);
        $json = file_get_contents($conf['PROTOCOL'] . '://' . $conf['IP'] . '/callback.php');
        $json = json_decode($json, true);


        foreach ($json as $platform => $filial_array) {

            foreach ($filial_array as $filial) {

                if (!array_key_exists('data', $filial)) {

                    logs()->set("<p style='color: red;'>ФИЛИАЛ: " . $filial['filial'] . " ПАРСЕР: $platform  Нет данных </p> \n");
                    continue;
                }
                if (gettype($filial['data']) == 'string') {
                    logs()->set("<p style='color: red;'> ФИЛИАЛ: " . $filial['filial'] . " ПАРСЕР: $platform  Нет данных ERROR:" . $filial['data'] . "</p> \n");
                    continue;
                }

                $data =
                    [
                        'api' => $filial['api'],
                        'date' => date('Y-m-d'),
                        'filial' => $filial['filial'],
                        'count' => $filial['data']['reviews-all'],
                        'star' => $filial['data']['star'],
                        'real_count' => '',
                        'platform' => $filial['data']['name'],
                    ];

                (new AnaliticsModel())->set($data);

                $response = (new ReviewsModel($filial['api']))->set(
                    $filial['data']['reviews'],
                    $filial['filial'],
                    $filial['data']['name']
                );

                logs()->set("<p style='color: green;'>ФИЛИАЛ: " . $filial['filial'] . " Парсер: " . $filial['data']['name'] . " Обновил: " . $response['UPDATE'] . " Добавил:" . $response['INSERT'] . " </p> \n");

                if (count($response['ERROR']) != 0) {
                    foreach ($response['ERROR'] as $error) {
                        logs()->set("<p style='color: red;'> ERROR: " . $error . " </p> \n");
                    }
                }
            }
        }
    }


    // внешнее подключение виджета 
    public function getJsWigets() {
        header("Content-type: text/javascript");
        $js =   file(__DIR__ . '/../lib/widget/widgets.js');
        foreach ($js as $i => $row) {
            if (str_contains($row, 'host')) {
                $host = "    host = '";
                $js[$i] =   env('IS_DEV') == 1 ? $host . env('WEBSITEDEV') . "'; \n" : $host . env("WEBSITEPOROJECT") . "';" ;
                echo implode(' ', $js);
                return;
            }
        }
    }
    public function getCssWigets() {
        header("Content-type: text/css");
        echo  file_get_contents(__DIR__ . '/../lib/widget/widgets.css');
    }

    // отдаем данные которые запросил wigets
    public function getDataWidgets() {

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:  GET,  OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Max-Age", "3600");
        header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Accept, Content-Type');
        header('Content-type: application/json');
        header('Cache-control: no-cache');
        $filials = explode(';', attr('filial'));
        $data = [];

        foreach ($filials as $filial) {
            $data[$filial] = [];


            $platforms =  (new ParsersModel)->find([
                'api' => supple('api'),
                'filial' => $filial,
            ], ['name'], ['parsers-list' => 'parser_id']);

            $origin = json_decode(file_get_contents(__DIR__ . '/../api/names.json'), true);

            foreach ($platforms as $platform) {

                $data[$filial][$platform['name']] = [];
                $data[$filial][$platform['name']]['star'] = (new AnaliticsModel())->actual(supple('api'), $platform['name'], $filial);
                $data[$filial][$platform['name']]['img'] = env('WEBSITEDEV') . "/PHP/lib/widget/images-widgets/{$platform['name']}.png";
                $data[$filial][$platform['name']]['origin'] = $origin[$platform['name']];
            }
        }

        echo  json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
