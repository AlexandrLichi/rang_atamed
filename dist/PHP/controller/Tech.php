<?php

namespace Controller\TechController;

use api\parser;
use Model\ClientModel\ClientModel;
use Model\CompanyModel\CompanyModel;
use Model\FilialModel\FilialModel;
use Model\Parser_listModel\Parser_listModel;
use Pet\Controller\Controller;
use Pet\Inertia\Inertia;
use Pet\Request\Request;
use Model\ParsersModel\ParsersModel;
use Model\ReviewsModel\ReviewsModel;
use users_rewiews_settings;

class TechController extends Controller
{
    private function list(Request $request)
    {
        $request->set(['list' => (new Parser_listModel())->getlist()]);
        $request->set(['GIT' => file_get_contents(__DIR__ . '/../api/GITINFO.txt')]);
    }



    public function getNewApi(Request $request) {
        $newApi = rand(100, 999) . '-' . rand(100, 999) . '-' . rand(100, 999);
        if (count((new CompanyModel)->find(['api' => $newApi])) > 0) $this->getNewApi($request);
        $request->set(['api' => $newApi]);
        $this->json();
    }


    public function companies(Request $request) {

        $this->list($request);

        $parserModel = new ParsersModel();
        $companyModel = new CompanyModel();

        $companyArray = $companyModel->find();
        $parserArray = $parserModel->find([], [], ['filial' => 'filial_id']);

        $request->set([
            'company' => $companyArray,
            'parser' => $parserArray,
        ]);
        // dm(attr());
        Inertia::render('tech/companies', $request);
    }



    public function createCompany(Request $request) {
        $company = attr()['company'];
        $CM = new CompanyModel();
        $CM->insert = true;
        $CM->insert($company);
        (new users_rewiews_settings)->registerTableCompany($company['api']);
    }



    public function index(Request $request) {
        $this->list($request);
        Inertia::render('tech', $request);
    }



    public function parser(Request $request) {
        $this->list($request);
        $request->set(["platform" => supple('parser')]);
        $request->set([supple('parser') => (new ParsersModel)->parsers(supple('parser'))]);
        Inertia::render('tech/plat/parser', $request);
    }



    public function NewProject(Request $request) {
        $this->list($request);
        Inertia::render('tech/new-project', $request);
    }

    public function listfilial(Request $request) {
        // dm($request);
        echo json_encode((new FilialModel)->findApi(supple('api')), JSON_UNESCAPED_UNICODE);
    }




    public function putNewFilial(Request $request) {

        $company = (new CompanyModel())->companyApi(attr()['api']);

        if (count($company) == 0) return 'not api';

        $filialModel = new FilialModel();

        // проверяем наличие филиала;
        $fil = $filialModel->find(['filial' => attr()['filial'], 'api' => attr()['api']]);

        if (count($fil) == 0) {

            $filialModel->insert = true;

            $filialModel->insert(
                [
                    'company_id' => $company[0]['id'],
                    "filial" => attr()['filial'],
                    'api' => attr()['api']
                ]
            );

            $idFilial = $filialModel->max();
        } else {

            $idFilial = $fil[0]['id'];
        }



        $parserName = supple('parser');
        $list_parser = (new Parser_listModel)->find(['name' => $parserName], ['id', 'query'])[0];


        $parserModel = new ParsersModel();

        $parserModel->insert = true;

        $row = [
            'parser_id' => $list_parser['id'],
            'filial_id' => $idFilial,
            'setting' => $list_parser['query'],
            'api' => attr()['api'],
            'filial' => attr()['filial']
        ];

        // проверям наличие 
        if (count($parserModel->find($row)) == 0) {

            $parserModel->insert($row);
            echo "Добавлено";
        } else {
            echo "Такой филиал уже есть базе у этого парсера";
        }
    }


    public function downloand(Request $request) {
        $this->list($request);

        $parserModel = new ParsersModel();
        $companyModel = new CompanyModel();

        $companyArray = $companyModel->find(['api' => supple('api')], ['id', 'company', 'api']);
        $parserArray = $parserModel->find(
            ['api' => supple('api')],
            [
                'parsers.id',
                'filial.company_id',
                "parsers-list.name",
                "parsers-list.query",
                "setting",
                "parsers.filial"
            ],
            ['filial' => 'filial_id', 'parsers-list' => 'parser_id']
        );
        // dd($parserArray);
        foreach ($parserArray as $i => $row) {
            $parserArray[$i]['count'] =
                (new ReviewsModel(supple('api')))->count(['filial' => $row['filial'], 'platform' => $row['name']]);
        }

        $request->set([
            'company' => $companyArray,
            'parser' => $parserArray,
        ]);

        Inertia::render("tech/download", $request);
    }

    function downloandParser(Request $request) {

        $parser = (new ParsersModel)->find(supple(), [], ['parsers-list' => 'parser_id']);

        $response = (new parser())->parse($parser[0]['name'], $parser[0]['setting']);
        if ($response) {
            $response = (new ReviewsModel(supple('api')))->set($response['reviews'], $parser[0]['filial'], $parser[0]['name']);
        }

        $request->set(['download' => $response]);
        $this->json();
    }



    function settingConfig(Request $request) {

        $this->list($request);
        $conf = (new parser())->config(false);
        $request->set(['config' => $conf]);
        Inertia::render('tech/setting', $request);
    }

    function setConfig(Request $request) {

        (new parser())->updateConfig(attr()['config']);
    }

    function uploadIndex(Request $request) {
        $this->list($request);
        Inertia::render('tech/upload', $request);
    }



    function newParser(Request $request) {
        unset($request->attribute['user']);

        (new Parser_listModel)->setParser(attr());
        //    dm($request);
        if ($request->file('icon-blue'))
            $this->saveFile('icon-blue', __DIR__ . "/../../view/images/" . 'icon8-' . attr('name') . '-blue.png');

        if ($request->file('icon-origin'))
            $this->saveFile('icon-origin', __DIR__ . "/../../view/images/" . 'search-' . attr('name') . '.png');
    }

    function replaceUrlDefault() {
        $data = [];
        $data['default_url'] = attr('url_default');
        $list_parser = new Parser_listModel();
        $list_parser->insert = true;
        $list_parser->findUpdate(supple(), $data);
    }

    function newParam() {
        $list_parser = new Parser_listModel();
        $list_parser->insert = true;
        $lists = $list_parser->find(supple());

        // Обновили параметр в парсер лист
        foreach ($lists as $list) {

            $default_url = json_decode($list['query'], true);
            $default_url[attr('new_param')] = "";
            $list_parser->findUpdate(['id' => $list['id']], ['query' => json_encode($default_url, JSON_UNESCAPED_UNICODE)]);
        }

        $parser = new ParsersModel();
        $parser->insert = true;
        $finds = $parser->find(['parser_id' => supple('id')]);
        //обновляем по всем парсерам
        foreach ($finds as $find) {
            $setting = json_decode($find['setting'], true);
            $setting[attr('new_param')] = "";
            $parser->findUpdate(['id' => $find['id']], ['setting' => json_encode($setting, JSON_UNESCAPED_UNICODE)]);
        }
    }

   public  function logs(Request $request) {
        $this->list($request);
        $request->set(["LOG" => [
            "LOCAL" =>  file(__DIR__ . '/../../../Log.txt'),
            "LOCALSQL" => file(__DIR__ . '/../../../LogSql.txt'),
        ]]);

        Inertia::render('tech/logs', $request);
    }


    public function clearlogs()
    {
        logs()->clear();
    }

    public function TechClient(Request $request)
    {
        // dd($request);
        $this->list($request);
        $request->set(['client' => array_reverse((new ClientModel())->find())]);

        Inertia::render('tech/client', $request);
    }

    public function News(Request $request) {
        $this->list($request);
        $request->set(['news' => json_decode(file_get_contents(__DIR__ . '/../api/news.json'), true)['data']]);
        Inertia::render('tech/news', $request);
    }
    public function setNews(Request $request) {
        $news = [];
        $news['data'] = attr('data');
        file_put_contents(__DIR__ . '/../api/news.json', json_encode($news, JSON_UNESCAPED_UNICODE));
        $this->json('{}');
    }
}
