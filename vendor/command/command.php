<?php
namespace Command;

use FTP\Ftp;
use Pet\FrontClasses\FrontClasses;


class Command{
    private $command;
    private $DIR;

    public  function __construct($comm, $DIR){
        $this->DIR = $DIR;
        $this->routCommand($comm);
    }


    static function start($comm, $DIR)
    {
        new Command($comm, $DIR);
    }


    public function routCommand($comm){
       
         unset($comm[0]);
        switch (trim($comm[1])) {
           case 'serve-apache':$this->serve();
                break;
            case 'srv': $this->serve();
                break;
            case 'serve':$this->serveLocal();
                break;
            case 'controller:make':$this->make($comm);
                break;
            case 'migrate:make': $this->make($comm);
                break;
            case 'model:make':$this->make($comm);
                break;
            case 'middleware:make':$this->make($comm);
                break;
            case 'migrate':$this->migration($comm, "up");
                break;
            case 'rollback:migrate':$this->migration($comm, "back");
                break;
            case '-info': $this->info();
                break;
            case '-help': $this->info();
                break;
            case 'ftp_load': $this->ftp_load();
                break;
            default:
                echo "no command ";
        }

    }

    public function ftp_load(){

        $DIRPROJECT = $this->DIR.'/'.env('FOLDER_PROJECT', 'dist');
        $DIRVENDOR = $this->DIR . '/vendor';
        $DIRIGNORE = explode("|", env('FTP_FOLDER_EXEPTION'));
        $FILEIGNORE = explode("|", env('FTP_FILE_EXEPTION'));

        $is_npm = 'n';
        $is_vendor = false;
        exec('git branch', $output);
        foreach($output as $branch){
            if(strpos($branch, "*") !== false){
                file_put_contents($this->DIR.'/'.env('FOLDER_PROJECT', 'dist').'/PHP/api/GITINFO.txt', 'Загруженая ветка <'.$branch .'>   Актуальность: '.date('d.m.Y H:i'));
            }
        }
        echo "Выполнить build Webpack перед загрузкой на сервер? (y/n)";
        osInput($is_npm);
        if($is_npm == 'y'){
            exec('cd "'.$this->DIR.'"&& npm run build', $output);
            foreach($output as $row){
                echo $row."\n";
            }
       }
       echo "Выполнить загрузку папки vendor? (y/n)";
       osInput($is_vendor);
         $ftp = new Ftp();

       $ftp->host = env('FTP_HOST');
       $ftp->login = env('FTP_LOGIN');
       $ftp->fileIgnore = $FILEIGNORE;
       $ftp->dirIgnore = $DIRIGNORE;
       
        if (trim(env('FTP_PASSWORD')) == '') {
            echo "Не введен пароль \n";
            return false;
        }

        $ftp->password = env('FTP_PASSWORD');
         if($ftp->connectCount(5))
         {
            $ftp->dirHost = env('FTP_FOLDER');
            $ftp->dir(env('FTP_FOLDER'));
            $ftp->putDirFiles($DIRPROJECT, 'dist');
            if($is_vendor == 'y' )$ftp->putDirFiles($DIRVENDOR, 'vendor');
            $ftp->close();
         }else{
             echo 'Not ftp connect';
         }
    }
  
   


    public  function serveLocal()
    {
        $host = env("WEBSITEDEV");
        $hostName = str_replace(['https://', 'http://'], '', $host);
        echo "\033[02;32m  \n\r \n\r   site: $host \033[0m \n\r \n\r";
        exec("php -S $hostName dist/bootstrap.php");
    }


    public  function serve(){

        $pathXampp = env('XAMPP', 'C:\xampp');

        $httpd = file($pathXampp."\\apache\\conf\\httpd.conf", FILE_SKIP_EMPTY_LINES);
        $this->DIR .= "\\".env('FOLDER_PROJECT', 'dist');


        $control = false;
        $old_dir = '';
        $newConfig = '';

        foreach($httpd as $line => $text){

            if(!str_contains($text, '#')){

                if(str_contains($text, 'DocumentRoot')){

                    $old_dir = str_replace(['DocumentRoot ', '"'],"", $text);
                    $httpd[$line] = 'DocumentRoot "'.$this->DIR.'"'."\n\r";
                    echo $httpd[$line];
                    $control = true;

                }

                if($control){
                    if (str_contains($text, '<Directory "'.$old_dir.'">')) {
                        $httpd[$line] = '<Directory "'. $this->DIR .'">'."\n\r";
                        echo $text;
                        $control = false;
                    }
                }
            }

           $newConfig .= $httpd[$line];
        }

        // echo $newConfig;
    
        file_put_contents($pathXampp . "\\apache\\conf\\httpd.conf", $newConfig);

        exec($pathXampp."\\xampp_start.exe");

        echo "\033[02;32m  \n\r \n\r \n\r \n\r  site: " .  'http://localhost' . " \033[0m \n\r \n\r \n\r \n\r";
        echo "\033[00;32m".'puch Enter stop server apache'. " \033[0m \n\r";

        exec("pause");

        echo "\033[01;31m stops working...  \033[0m \n\r";

        exec($pathXampp . "\\xampp_stop.exe");
        echo "\033[01;31m Server stop apache \033[0m  ";
    }


     function make($name){

        $folder_project = env('FOLDER_PROJECT','dist');
        $path = $this->DIR . "\\" . $folder_project . "\\PHP\\";
    

        if($name[1] == "controller:make"){
           
        if(count($name) < 2) die("not name controller");
            $sample = file_get_contents(__DIR__ . '\\txt\\sample.controller.txt');
            $Name = ucfirst($name[2]);
            $controller = str_replace(['{name}'], $Name, $sample);
            file_put_contents("$path\\controller\\{$name[2]}.php", $controller);

        }

        if ($name[1] == "model:make") {

            if (count($name) < 2) die("not name model");
            $sample = file_get_contents(__DIR__ . '\\txt\\sample.model.txt');
            $Name = ucfirst($name[2]);
            $model = str_replace(['{name}'], $Name, $sample);
            file_put_contents("$path\\model\\{$name[2]}.php", $model);

        } 



        if ($name[1] == "migrate:make") {

            if(count($name) < 2) die("set migration [name]");

             $Text = file_get_contents(__DIR__.'\\txt\\sample.mig.txt');
             $Text = str_replace('{name}', $name[2], $Text);
             $date = date('Ymd');

             file_put_contents("$path\\{$date}-{$name[2]}.mig.php", $Text);

        }

        if ($name[1] == "middleware:make") {

            if (count($name) < 2) die("set middleware [name]");

            $Text = file_get_contents(__DIR__ . '\\txt\\sample.middleware.txt');
            $Name = ucfirst($name[2]);
            $Text = str_replace('{name}', $Name, $Text);
         

            file_put_contents("$path\\middleware\\ $Name.php", $Text);
        }

    }




   function migration($comm, $method){

       

        $folder_project = env('FOLDER_PROJECT');
        $path = $this->DIR."\\".$folder_project."\\PHP\\";
        $files = include_dir($path, ".mig.php");

        
        foreach($files as $file){

            preg_match("/[_A-Za-z]{1,}/", $file, $mcth);
            $front = new FrontClasses(); 
            $front->classStarted([$mcth[0], $method]);

            if($method == 'up'){

              $tables =  $front->classStarted([$mcth[0], 'setAfter']);

              if(in_array('-m', $comm)){

                $sampleModel = file_get_contents(__DIR__."\\txt\\sample.model.txt");

                foreach($tables as $name => $join){

                    $Name = ucfirst($name);

                    $Model = str_replace(['{name}'], $Name, $sampleModel);
                    $Model = str_replace(['{table}'],'"'.$name.'"', $Model);
                   
                   
                    $Model = str_replace(['{join}'],"['".implode("','",$join)."']", $Model);
                    
                    file_put_contents($path."\\model\\{$Name}.php", $Model);
                }
              }
            }
        }
    }



     function info()
    {
        $file = file(__DIR__."\\txt\\info.txt");

        foreach($file as $row){

            if(trim($row) == '') continue;
            $text = explode("--", $row);
            echo "\033[02;32m {$text[0]}\033[0m {$text[1]}";

        }
    }
}
?>