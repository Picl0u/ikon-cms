<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Aschmelyun\Larametrics\Models\LarametricsLog;
use Aschmelyun\Larametrics\Models\LarametricsModel;
use Aschmelyun\Larametrics\Models\LarametricsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Piclou\Ikcms\Helpers\DataTable;
use SEO;
use Carbon;
use Yajra\DataTables\DataTables;

class LogsController extends Controller
{
    protected $viewPath = 'ikcms::logs.';

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath(string $viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log_list(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTable_log();
        }
        $total = LarametricsLog::count();
        SEO::setTitle("Gestion des logs");
        SEO::setDescription("Gestion des logs");
        SEO::opengraph()->setUrl(route("ikcms.admin.logs.list"));
        return view($this->viewPath . 'log.index',compact("total"));
    }

    public function log_show(int $id)
    {
        $log = LarametricsLog::where("id", $id)->firstOrFail();
        SEO::setTitle("Affichage du log");
        SEO::setDescription("Affichage du log");
        return view($this->viewPath . 'log.show',compact("log"));
    }

    public function request_list(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTable_request();
        }

        $total = LarametricsRequest::count();
        SEO::setTitle("Gestion des requêtes");
        SEO::setDescription("Gestion des requêtes");
        SEO::opengraph()->setUrl(route("ikcms.admin.requests.list"));
        return view($this->viewPath . 'requests.index',compact("total"));
    }

    public function request_show(int $id)
    {
        $request = LarametricsRequest::where("id", $id)->firstOrFail();

        SEO::setTitle("Affichage de la requête");
        SEO::setDescription("Affichage de la requête");
        return view($this->viewPath . 'requests.show',compact("request"));
    }

    public function model_list()
    {

        $total = LarametricsModel::count();
        $modelChanges = LarametricsModel::groupBy('model')
            ->select('model', DB::raw('count(*) as total'))
            ->get()
            ->keyBy('model');

        $modelsAmounts = array();

        $earliestModel = LarametricsModel::orderBy('created_at', 'desc')
            ->first();

        foreach(config('larametrics.modelsWatched') as $model) {
            $modelsAmounts[$model] = array(
                'count' => $model::count(),
                'changes' => isset($modelChanges[$model]) ? $modelChanges[$model]['total'] : 0
            );
        }

        SEO::setTitle("Gestion des models");
        SEO::setDescription("Gestion des models");
        SEO::opengraph()->setUrl(route("ikcms.admin.models.list"));
        return view($this->viewPath . 'models.index',[
            'modelsAmounts' => $modelsAmounts,
            'watchLength' => $earliestModel ? $earliestModel->created_at->diffInDays(Carbon\Carbon::now()) : 0,
            'total' => $total
        ]);
    }

    public function model_show($model)
    {
        if(is_numeric($model)) {
            $larametricsModel = LarametricsModel::find($model);

            $modelPrimaryKey = (new $larametricsModel->model)->getKeyName();

            SEO::setTitle($larametricsModel->model);
            SEO::setDescription($larametricsModel->model);

            return view($this->viewPath . 'models.show', [
                'model' => $larametricsModel,
                'pageTitle' => $larametricsModel->model,
                'modelPrimaryKey' => $modelPrimaryKey
            ]);
        } else {
            $appModel = str_replace('+', '\\', $model);

            $models = LarametricsModel::where('model', $appModel)
                ->orderBy('created_at', 'desc')
                ->get();

            $earliestModel = LarametricsModel::orderBy('created_at', 'desc')
                ->first();

            $modelPrimaryKey = (new $appModel)->getKeyName();

            SEO::setTitle($appModel);
            SEO::setDescription($appModel);

            return view($this->viewPath . 'models.show', [
                'models' => $models,
                'pageTitle' => $appModel,
                'watchLength' => $earliestModel ? $earliestModel->created_at->diffInDays(Carbon\Carbon::now()) : 0,
                'modelPrimaryKey' => $modelPrimaryKey
            ]);
        }
    }

    public function model_revert(int $id)
    {
        $larametricsModel = LarametricsModel::where("id",$id)->firstorFail();

        $modelPrimaryKey = (new $larametricsModel->model)->getKeyName();

        SEO::setTitle($larametricsModel->model);
        SEO::setDescription($larametricsModel->model);

        return view($this->viewPath . 'models.detail', [
            'model' => $larametricsModel,
            'pageTitle' => $larametricsModel->model,
            'modelPrimaryKey' => $modelPrimaryKey
        ]);
    }

    /**
     * @return mixed
     */
    private function dataTable_log()
    {
        $datatable = new DataTable();
        $activities = LarametricsLog::select([
            'id','level','message','email','created_at','email'
        ]);

        return DataTables::of($activities)
            ->editColumn("level",function(LarametricsLog $log){
                if($log->level === 'warning' || $log->level === 'failed') {
                    return '<span class="label is-warning">' . strtoupper($log->level) .'</span>';
                }
                if($log->level === 'error' || $log->level === 'critical' || $log->level === 'alert' || $log->level === 'emergency') {
                    return'<span class="label is-error">' . strtoupper($log->level) .'</span>';
                }
                return '<span class="label is-success">' . strtoupper($log->level) .'</span>';
            })
            ->editColumn("message",function(LarametricsLog $log) use ($datatable) {
                return substr($log->message,0,70) . " ...";
            })
            ->editColumn("created_at",function(LarametricsLog $log) use ($datatable) {
                return $datatable->date($log->created_at);
            })
            ->addColumn("action",function(LarametricsLog $log){
                return ' <a href="'.route('ikcms.admin.logs.show', $log->id) .'" class="table-button">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    Détail
                </a>';
             })
            ->rawColumns(['level','action'])
            ->make(true);
    }

    private function dataTable_request()
    {
        $datatable = new DataTable();
        $activities = LarametricsRequest::select([
            'id','method','created_at','uri','ip','start_time','end_time'
        ]);

        return DataTables::of($activities)
            ->addColumn("level",function(LarametricsRequest $request){
                $textClass = 'fa-circle text-info';
                if($request->method === 'POST' || $request->method === 'PUT' || $request->method === 'OPTIONS') {
                    $textClass = 'fa-circle text-warning';
                }
                if($request->method === 'DELETE') {
                    $textClass = 'fa-circle text-danger';
                }
                return '<i class="fa '.$textClass .'"></i>';
            })
            ->addColumn("time",function(LarametricsRequest $request) {
                return floor(($request->end_time - $request->start_time) * 1000) ."ms";
            })
            ->editColumn("uri",function(LarametricsRequest $request) use ($datatable) {
                return '<strong></strong>'.substr($request->uri,0,50) . "...</strong>";
            })
            ->editColumn("created_at",function(LarametricsRequest $request) use ($datatable) {
                return $datatable->date($request->created_at);
            })
            ->addColumn("action",function(LarametricsRequest $request){
                return ' <a href="'.route('ikcms.admin.requests.show', $request->id) .'" class="table-button">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    Détail
                </a>';
            })
            ->rawColumns(['level','uri','action'])
            ->make(true);
    }
}