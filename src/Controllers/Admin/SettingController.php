<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Piclou\Ikcms\Facades\Ikcms;
use Piclou\Ikcms\Requests\Admin\SettingsRequest;
use SEO;
use Setting;

class SettingController extends Controller
{
    protected $viewPath = 'ikcms::settings.';
    private $route = 'ikcms.admin.settings.';

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
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route)
    {
        $this->route = $route;
    }

    public function index()
    {
        SEO::setTitle(__("ikcms::admin.navigation_parameters"));
        SEO::setDescription(__("ikcms::admin.navigation_parameters"));
        SEO::opengraph()->setUrl(route("ikcms.admin.settings.index"));
        return view($this->viewPath . "index");
    }

    public function store(SettingsRequest $request)
    {
        $insertLogo = setting('website.logo');
        if($request->hasFile('website_logo')){
            $insertLogo = (new Ikcms)->uploadImage('settings/website', $request->website_logo);
            if(!empty(setting('website.logo'))) {
                if(file_exists(setting('website.logo'))) {
                    unlink(setting('website.logo'));
                }
            }
        }
        $save = [
            "website.logo" => $insertLogo,
            "website.name" => $request->website_name,
            "website.email" => $request->website_email,
            "website.maintenance" => ($request->website_maintenance)?1:0,
            "recaptcha.active" => ($request->recaptcha_active)?1:0,
            "recaptcha.public" => $request->recaptcha_public,
            "recaptcha.secret" => $request->recaptcha_secret,
            "company.name" => $request->company_name,
            "company.address" => $request->company_address,
            "company.zip" => $request->company_zip,
            "company.city" => $request->company_city,
            "facebook" => $request->facebook,
            "twitter" => $request->twitter,
            "instagram" => $request->instagram,
            "pinterest" => $request->pinterest,
            "youtube" => $request->youtube,
            "seo.robot" => ($request->seo_robot)?1:0,
            "seo.title" => $request->seo_title,
            "seo.description" => $request->seo_description,
            "seo.twitter" => $request->seo_twitter,
            "slider.arrows" => ($request->slider_arrows)?1:0,
            "slider.dots" => ($request->slider_dots)?1:0,
            "slider.type" => $request->slider_type,
            "slider.transition" => $request->slider_transition,
            "slider.duration" => $request->slider_duration,
            "slider.duration_transition" => $request->slider_duration_transition,
        ];
        setting($save)->save();
        session()->flash('success', __("ikcms::admin.setting_success"));
        return redirect()->route($this->route. 'index');
    }

}