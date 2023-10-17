<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PostgresCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postgres:crud {name} {--schema=public} {--make="all"} {--routes=true} {--breadcrumbs=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builder CRUD Laravel 8';
    protected $Field="";
    protected $schema="";
    protected $arrayField=[];
    protected $primaryKey="";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function getStub($type){
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function generate_crud($make,$name,$schema){
        $results = DB::select("
        SELECT DISTINCT c.table_schema,c.table_name,tc.constraint_type,c.column_name,c.column_default,
        c.is_nullable,c.data_type,c.character_maximum_length
        FROM information_schema.columns AS c
        LEFT JOIN information_schema.constraint_column_usage AS ccu ON ccu.column_name = c.column_name AND ccu.table_schema = c.table_schema and ccu.table_name = c.table_name
        LEFT JOIN information_schema.table_constraints tc ON c.table_schema = tc.constraint_schema
        AND tc.table_name = c.table_name AND ccu.column_name = c.column_name AND tc.constraint_type = 'PRIMARY KEY'
        WHERE c.table_schema = '".$schema."'
        AND c.table_name = '".strtolower($name)."';
        ");
        $this->Field ="[\n";
        foreach($results as $x=>$rs){
            if($rs->constraint_type == "PRIMARY KEY"){
                $this->primaryKey = $rs->column_name;
            }
            $this->Field .= "'".$rs->column_name."',\n";
            $this->arrayField[] = "'$rs->column_name'";
        }
        $this->Field = rtrim($this->Field,",\n")."\n]";
        $this->schema = $schema;
        if ($make == 'controller') {
            $this->controller($name,$results);
        }elseif ($make == 'model') {
            $this->model($name,$schema);
        }elseif ($make == 'view') {
            $this->view($name,$results);
        }else{
            $this->controller($name,$results);
            $this->model($name,$schema);
            $this->view($name,$results);
        }
    }

    protected function controller($name,$table){
        
        $ModelValidation = "[\n";
        $ModelDefaultValue = "[\n";
        foreach($table as $x=>$rs){
            if($rs->constraint_type == "PRIMARY KEY"){
                $this->primaryKey = $rs->column_name;
            }

            if($rs->constraint_type != "PRIMARY KEY"){
                $ModelValidation .= "'".$rs->column_name."'   =>  ";
                $valid = "'',\n";
                if($rs->is_nullable == 'NO'){
                    $valid = "'required',\n";
                }
                $ModelValidation .= $valid;
            }

            $ModelDefaultValue .= "'".$rs->column_name."'   =>  '".($rs->constraint_type=="PRIMARY KEY"?null:$rs->column_default)."',\n";
        }
        $ModelValidation = rtrim($ModelValidation,",\n")."\n]";
        $ModelDefaultValue = rtrim($ModelDefaultValue,",\n")."\n]";
        
        $controllerTemplate = str_replace([
           '{{ModelName}}',
           '{{ModelGroup}}',
           '{{ModelRoute}}',
           '{{ModelField}}',
           '{{ModelPrimaryKey}}',
           '{{$ModelValidation}}',
           '{{$ModelDefaultValue}}',
        ],
        [
           ucfirst($name),
           strtolower(($name)),
           strtolower($name),
           $this->Field,
           $this->primaryKey,
           $ModelValidation,
           $ModelDefaultValue
        ],
        $this->getStub('Controller'));

        //insert record to table
        DB::table("table_generator")->insert([
            "schema_name"       => $this->schema,
            "table_name"        => $name,
            "table_element"     => "controller"
        ]);
        $controler= ucfirst($name);
        file_put_contents(app_path("/Http/Controllers/{$controler}Controller.php"), $controllerTemplate);
     }

     protected function model($name,$schema){
        $modelTemplate = str_replace(
           ['{{ModelName}}', '{{ModelTable}}', '{{ModelField}}','{{ModelPrimaryKey}}'],
           [$name, strtolower(($schema.".".$name)),$this->Field,$this->primaryKey],
           $this->getStub('Model')
        );

        //insert record to table
        DB::table("table_generator")->insert([
            "schema_name"       => $this->schema,
            "table_name"        => $name,
            "table_element"     => "model"
        ]);
        file_put_contents(app_path("Models/{$name}.php"), $modelTemplate);
     }
     
     protected function view($name,$table){
        $tableHeader = "";
        $name = strtolower($name);
        $tableHeader .= implode(",",$this->arrayField);
        $viewIndexTemplate = str_replace(
           ['{{ModelName}}'],
           [strtolower(($name))],
           $this->getStub('v_index')
        );
        $viewDataTemplate =  str_replace(
            ['{{ModelCol}}', '{{ModelName}}'],
            [$tableHeader, strtolower(($name))],
            $this->getStub('v_data')
        );
        $patch = resource_path("views/{$name}");
		if (is_dir($patch)) {
			// delete_files($patch,TRUE);
			array_map('unlink', array_filter( 
				(array) array_merge(glob($patch."/*"))));
			rmdir($patch); 
		}
        mkdir($patch);
        file_put_contents($patch."/index.blade.php", $viewIndexTemplate);
        file_put_contents($patch."/data.blade.php", $viewDataTemplate);
        //Create view form
        $form =[];
        foreach($table as $x=>$rs){
            $required = '';
            if($rs->is_nullable == 'NO'){
               $required = ',
               "required"  => "true"';
            }
            if($rs->constraint_type != 'PRIMARY KEY') {
                $form[$x] = '{!! 
                    Create::input("'.$rs->column_name.'",[
                    "value"     => $'.$name.'->'.$rs->column_name.''.$required.'
                    ])->render("group"); 
                !!}';
            }
        }
        $form = implode("\n",$form);
        $modelTemplate = str_replace(
            ['{{ModelForm}}', '{{ModelName}}', '{{ModelPrimaryKey}}'],
            [$form, $name,$this->primaryKey],
            $this->getStub('v_form')
         );

        file_put_contents($patch."/form.blade.php", $modelTemplate);

         //insert record to table
        DB::table("table_generator")->insert([
            "schema_name"       => $this->schema,
            "table_name"        => $name,
            "table_element"     => "view"
        ]);
     }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $make = $this->option('make');
        $schema = $this->option('schema');
        $routes = $this->option('routes');
        $breadcrumbs = $this->option('breadcrumbs');
        DB::beginTransaction();
        try {
            $this->generate_crud($make,$name,$schema);
            if ($routes == 'true') {
                File::append(base_path('routes/web.php'),
                "
                Route::get('" . (strtolower($name)) . "/get_dataTable','{$name}Controller@get_dataTable');
                Route::resource('" . (strtolower($name)) . "', {$name}Controller::class);");
            }
    
            if ($breadcrumbs == 'true') {
                File::append(base_path('routes/breadcrumbs.php'),
                '
                Breadcrumbs::for("'. (strtolower($name)) .'", function (BreadcrumbTrail $trail) {
                    $trail->parent("home");
                    $trail->push("'. (strtolower($name)) .'", route("'. (strtolower($name)) .'.index"));
                });');
            }
            DB::commit();
            $resp = [
                "code"      => "200",
                "message"   => "Element berhasil di bangun",
                "response"  => [
                    "url"   => url(strtolower($name))
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $resp = [
                "code"      => 201,
                "message"   => $e->getMessage()
            ];
        }
       $this->info(json_encode($resp));
    }
}
