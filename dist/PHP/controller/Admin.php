<?php
namespace Controller\AdminController;

use api\parser;
use Lib\ImapGet\ImapGet;
use Model\AnaliticsModel\AnaliticsModel;
use Model\CompanyModel\CompanyModel;
use Model\FilialModel\FilialModel;
use Model\UserModel\UserModel;
use Pet\Controller\Controller;
use Pet\Inertia\Inertia;
use Pet\Request\Request;
use Model\Parser_listModel\Parser_listModel;
use Model\ParsersModel\ParsersModel;
use Model\ReviewsModel\ReviewsModel;
use Model\ClientModel\ClientModel;
use Model\HistoryClientModel\HistoryClientModel;
use Pet\DataBase\DBSQL;

class AdminController extends Controller{

    public function __construct() {
        $this->list(request());
    }

    private function list(Request $request)
    {
    //    $sql = new DBSQL();
    //     $sql->find(['id' => 1]);
    //     dm($sql->error);
    //    exit;


        $filial = (new FilialModel())->filialCompany(attr('user')['company_id']);
        $prefix = [];
    
        foreach($filial as $key => $fil){
            if($fil['imap']){
                foreach(json_decode($fil['imap'], true)['prefix'] as $pr){
                   $prefix[] = $pr;
                }
            }
        }
        $company = (new CompanyModel())->company(attr('user')['company_id'])[0];
        $request->set(['filial' => $filial]);
        $request->set(['prefix' => $prefix]);
        $request->set(['api'=>$company['api']]);
        $request->set(['sample_bonus'=> $company['sample_bonus'] ]);
        $request->set(["platform-list"=>
        (new ParsersModel())->listApiNameParser(attr('api'))]
    
    );
    }

    public function index(Request $request){
           $attr = attr();
           if(gettype($attr) == 'array' && !array_key_exists('user', $attr)) $request->location('/login');
           if(array_key_exists('company_id', $attr['user']))
           {
               $company = (new CompanyModel)->company(attr()['user']['company_id']);
               $filial = (new FilialModel)->filial(attr()['user']['company_id']);
           }else{
                $company = [];
                $filial = [];
           }
           $request->set(['company'=>$company,
                          'filial'=>$filial,
                          'news'=>json_decode(file_get_contents(__DIR__.'/../api/news.json'), true)['data'],
                            ]);


           Inertia::render('admin', $request);
       
    }

    public function reviews(Request $request){
        $search = null;
        

        if(attr('search')){
            $search = json_decode( attr('search'), true);
            $reviews = ["reviews"=>[]];
            
            $reviews["reviews"] = (new ReviewsModel(attr('api')))->getReviews($search);

            $reviews["reviews"] = array_reverse($reviews["reviews"]);

            $request->set($reviews);
        }
        
        Inertia::render('admin/reviews', $request);
    }


    public function request(Request $request)
    {
        // $this->reloadHistory();
        $search = attr('search');
        if ($search && $search != '') {
            $request->set(['client' => (new ClientModel())->getsearch(attr('api'), cookie('filial'), $search)]);
        } else {
            $request->set(['client' => (new ClientModel())->getReport(attr('api'), cookie('filial'))]);
        }
        $history = [];
        foreach ($request->attribute['client'] as $client) {
            $history[$client['id']] = (new HistoryClientModel())->getHistory($client['id']);
        }
        $request->set(['history' => $history]);

        Inertia::render('admin/request', $request);
    }

    public function setBonus()
    {
        $client = new ClientModel();
        $client->insert = true;
        $id = attr('id');
        $client->update(['bonus_check' => true], "`id`='$id'");
        $this->json('{}');
    }

    public function SettingPage(Request $request){
       $USERS = (new UserModel)->getAllUsers(["company_id"=>attr('user')['company_id']],
        ['email','job','id','name', 'active']);
        $request->set(["users"=>$USERS]);

        // шаблон клиента
        $sample = (new CompanyModel())->find(['id'=>attr('user')['company_id']],['sample-client', 'sample_image_wa']);
        if(!$sample[0]['sample-client']){
            $sample = json_decode(file_get_contents(__DIR__.'/../api/sample.json'),true);
            $request->set(["sample-client"=>$sample]);
        }else{
            $request->set(["sample-client"=>json_decode($sample[0]['sample-client'], true)]);
        }
        $request->set(['names-platform'=>json_decode(file_get_contents(__DIR__.'/../api/names.json'),true)]);
        $request->set(["sample_image_wa"=>$sample[0]['sample_image_wa']]);

        Inertia::render('admin/setting', $request);
    }


    public function setFileSample(Request $request){
      $this->saveFile('file', __DIR__.'/../../view/static/images/'.attr('name'));
      $company = new CompanyModel();
      $company->insert = true;
      $company->findUpdate(['id'=>attr('user')['company_id']], ['sample-client'=>attr('sample')]);
    }


    public function setTextSample(Request $request){
        $company = new CompanyModel();
        $company->insert = true;
        attr('reload')?
         $company->findUpdate(['id'=>attr('user')['company_id']], ['sample-client'=>null])
        :
        $company->findUpdate(['id'=>attr('user')['company_id']], ['sample-client'=>json_encode(attr('sample'),JSON_UNESCAPED_UNICODE)]);
        echo "{}";
    }

    public function setTestSample(Request $request){
      $result = (new ClientModel())->setStackTest(attr('telefon'), attr('api'),attr('filialId'));

        if(gettype($result)== 'array'){
            
            $url  = (new parser())->config(false);
            file_get_contents($url['PROTOCOL'].'://'.$url['IP'].'/callback-send-rang.php');

            $this->json(["request" => "Отправлено"]);
        }else{
            $this->json(["request" => $result]);
        }
    }

    public function setBonusSample(Request $request){

        $company = new CompanyModel();
        $company->insert = true;
        $company->findUpdate(['id'=>attr('user')['company_id']], ['sample_bonus'=>attr('sample_bonus')]);
        $this->json([]);
    }

    public function newUser(Request $request){

     $this->json(['password' => (new UserModel)->set(attr('newUser'))]);
    }

    public function updatePass(Request $request){
        $this->json(['password' => (new UserModel)->updatePass(attr('email'))]);
    }

    public function myfilial(Request $request){
        $parser = (new ParsersModel())->getFilialApi(attr('api'));
        $request->set(['parser'=>$parser]);
        Inertia::render('admin/myfilial', $request);
    }

    public function analytics(Request $request){
        // $request->set(['analytics'=>'1']);
        Inertia::render("admin/analytics", $request);
    }



    public function analyticsSearch(Request $request){
      $request->set(["analytics" => (new AnaliticsModel)->get(attr('search'))]);
      $this->json();
    }



    public function setImap(Request $request){
    
        $attr = attr('imap');
        $imap = json_encode($attr, JSON_UNESCAPED_UNICODE);
        $filialModel =  new FilialModel();
        $filialModel->insert = true;
        $filialModel->findUpdate(['id'=>$attr['id']], ['imap'=>$imap]);
    }
    public function help(Request $request)
    {
        Inertia::render('admin/help', $request);
    }


    public function setUrlImageWA(Request $request){
        $this->saveFile("file",__DIR__."/../../view/static/images/".attr('sample_images'));
        $company = new CompanyModel();
        $company->insert = true;
        $company->findUpdate(['id'=>attr('user')['company_id']], ['sample_image_wa'=>env('WEBSITEDEV').'/view/static/images/'.attr('sample_images')]);
    }
}
?>