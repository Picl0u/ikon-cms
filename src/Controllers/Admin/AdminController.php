<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Aschmelyun\Larametrics\Models\LarametricsLog;
use Aschmelyun\Larametrics\Models\LarametricsModel;
use Aschmelyun\Larametrics\Models\LarametricsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SEO;

class AdminController extends Controller
{
    protected $viewPath = 'ikcms::admin.';

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

    public function login()
    {
        if(Auth::user()){
            if(Auth::user()->role == 'admin') {
                return redirect()->route('ikcms.admin.dashboard');
            }
        }
        SEO::setTitle(__("ikcms::admin.login_seo_title"));
        SEO::setDescription(__("ikcms::admin.login_seo_title"));
        SEO::opengraph()->setUrl(route("ikcms.admin.login"));

        return view($this->viewPath . "login");
    }

    public function dashboard()
    {
        $requests = LarametricsRequest::select('*',DB::raw('COUNT(id) as occurrences'))
            ->groupBy('uri')
            ->orderBy('occurrences', 'DESC')
            ->limit(10)
            ->get();

        $logs = LarametricsLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $models = LarametricsModel::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();


        SEO::setTitle(__("ikcms::admin.navigation_dashboard"));
        SEO::setDescription(__("ikcms::admin.navigation_dashboard"));
        SEO::opengraph()->setUrl(route("ikcms.admin.dashboard"));
        return view($this->viewPath . "dashboard", compact("activities","requests","logs","models"));
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard();
    }
}