<?php
namespace Controller\ClientController;
use Pet\Controller\Controller;
use Pet\Request\Request;
use Model\ClientModel\ClientModel;
use Model\CompanyModel\CompanyModel;
use Model\Parser_listModel\Parser_listModel;
use Model\ParsersModel\ParsersModel;
use Pet\Inertia\Inertia;
use Pet\View\View;

class ClientController extends Controller
{

    public function index(Request $request) {
        $clientModel = new ClientModel();
        $client = $clientModel->getClientUrl(supple('url'));
        if (count($client) != 0) {

            if (!$client[0]['path'] || $client[0]['path'] == '') $clientModel->status_path(supple('url'), "CONNECT", null, null, null, $client[0]['watch']);
            $clientModel->insert = true;
            $GLOBALS['star'] = $client[0]['like_star'];
            $GLOBALS['platform'] = explode('//', $client[0]['platform_marketing']);
            $GLOBALS['name'] = $client[0]['name'];
            $GLOBALS['path'] = $client[0]['path'];
            $GLOBALS['text_negative'] = $client[0]['text_negative'];
            $GLOBALS['clinic'] = $client[0]['filial'];
            $GLOBALS['bonus'] = $client[0]['bonus'];

            $sample = (new CompanyModel())->find(["api" => $client[0]['api']], ['sample-client']);

            if (!$sample[0]['sample-client']) {

                $GLOBALS["sample"] = json_decode(file_get_contents(__DIR__ . '/../api/sample.json'), true);
            } else {

                $GLOBALS["sample"] = json_decode($sample[0]['sample-client'], true);
            }

            View::open('static/otzyv');
        } else {
            $request->redirect_code(404);
        }

    }



    public function putClientParam(Request $request){

        $clientModel = new ClientModel();
        $clientModel->insert = true;
        $client = $clientModel->getClientUrl(supple('url'));

        if (count($client) != 0) {

            if (attr('star')) {
                $clientModel->status_path(supple('url'), "STAR", attr('star'), null, null, $client[0]['watch']);
            }

            if (attr('platform_path')) {
                $clientModel->status_path(supple('url'), "POSITIVE", null, attr('platform_path'), null, $client[0]['watch']);
                echo $this->formUrl($request);
            }

            if (attr('text_negative')) {
                $clientModel->status_path(supple('url'), "NEGATIVE", null, null, attr('text_negative'), $client[0]['watch']);
            }
        }
    }







    public function set(Request $request){

        (new ClientModel())->status_path(supple('url'),attr('path'), attr('like_star'), attr('platform_path'), attr('text_negative'));

        if(attr('platform_path')){
           echo $this->formUrl($request);
        }
    }

    private function formUrl(Request $request){

        $client =  (new ClientModel())->getClientUrl(supple('url'))[0];
        $filial = $client['filial'];
        $data = (new ParsersModel)->find(['parsers-list.name' => attr('platform_path'), 'filial' => $filial, 'api' => $client['api']], ['setting'], ['parsers-list' => 'parser_id'])[0];
        $data = json_decode($data['setting'], true);
        $data['filial'] = str_replace('+', '%2B', $filial);
        $url = (new Parser_listModel())->find(['name' => attr('platform_path')], ['default_url'])[0]['default_url'];

        foreach($data as $key=>$value){
           $url = preg_replace("/\{$key\}/", $value, $url);
        }

        return str_replace(' ' , '+', $url);
    }

    
}
?>