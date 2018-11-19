<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Piclou\Ikcms\Entities\Page;
use Piclou\Ikcms\Entities\PageCategories;
use Piclou\Ikcms\Helpers\DataTable;
use Piclou\Ikcms\Ikcms;
use Piclou\Ikcms\Requests\Admin\PagesRequest;
use SEO;
use Yajra\DataTables\DataTables;

class PagesController extends Controller
{
    protected $viewPath = 'ikcms::pages.pages.';
    private $route = 'ikcms.admin.pages.';

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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTable();
        }

        $total = Page::count();
        $active = Page::where('published',1)->count();
        $desactive = Page::where('published',0)->count();
        $public = Page::where('visibility',"public")->count();
        $private = Page::where('visibility',"private")->count();

        SEO::setTitle("Gestion des contenus pour vos pages");
        SEO::setDescription("Gestion des contenus pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "index"));
        return view($this->viewPath . 'index',compact("total","active", "desactive","public","private"));
    }

    public function create()
    {
        $page = new Page();
        $section_list = PageCategories::orderBy('name','asc')->get();
        $sections = [];
        foreach($section_list as $section) {
            $sections[$section->id] = $section->name;
        }
        SEO::setTitle("Gestion des contenus pour vos pages - Ajouter");
        SEO::setDescription("Gestion des contenus pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'create', compact("page","sections"));
    }

    public function store(PagesRequest $request)
    {
        $create = [
            'published' => ($request->published)?1:0,
            'name' => json_encode($request->name, true),
            'slug' => ($request->slug && !empty($request->slug))?str_slug($request->slug):str_slug($request->name[config("app.locale")]),
            'visibility' => $request->visibility,
            'visibility_password' => ($request->visibility_password && !empty($request->visibility_password))?bcrypt($request->visibility_password):null,
            'page_category_id' => $request->page_category_id,
            'summary' => json_encode($request->summary, true),
            'description' => json_encode($request->description, true),
            'seo_robots' => ($request->seo_robots)?1:0,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description
        ];
        $page = Page::create($create);
        if($request->hasFile('image')){
            $page->uploadImage('image', 'pages', $request->image);
            $page->update();
        }

        session()->flash('success', "Le contenu a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    public function edit(string $uuid)
    {
        $page = Page::where('uuid', $uuid)->FirstOrFail();
        $section_list = PageCategories::orderBy('name','asc')->get();
        $sections = [];
        foreach($section_list as $section) {
            $sections[$section->id] = $section->name;
        }
        SEO::setTitle("Gestion des contenus pour vos pages - Modifier") ;
        SEO::setDescription("Gestion des contenus pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'edit', compact("page", "sections"));
    }

    public function update(PagesRequest $request, string $uuid)
    {
        $page = Page::where('uuid', $uuid)->FirstOrFail();
        $update = [
            'published' => ($request->published)?1:0,
            'name' => json_encode($request->name, true),
            'slug' => ($request->slug)?str_slug($request->slug):str_slug($request->name),
            'visibility' => $request->visibility,
            'page_category_id' => $request->page_category_id,
            'summary' => json_encode($request->summary, true),
            'description' => json_encode($request->description, true),
            'seo_robots' => ($request->seo_robots)?1:0,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description
        ];

        if($request->visibility_password && !empty($request->visibility_password)) {
            $update['visibility_password'] = bcrypt($request->visibility_password);
        }
        $page->update($update);

        if($request->hasFile('image')){
            $medias = $page->getMedias("image");
            $page->uploadImage('image', 'pages', $request->image);
            if($medias && file_exists($medias['target_path'])) {
                unlink($medias['target_path']);
            }
            $page->update();
        }

        session()->flash('success', "Le contenu a bien été modifié.");
        return redirect()->route($this->route . 'index');

    }

    public function destroy(string $uuid)
    {
        $page = Page::where('uuid', $uuid)->FirstOrFail();
        $page->deleteFile('image');
        $page->delete();
        session()->flash('success',"Le contenu a bien supprimé.");
        return redirect()->route($this->route . 'index');
    }

    public function updateImage(Request $request, string $uuid)
    {
        $page = Page::where('uuid', $uuid)->FirstOrFail();
        $medias = $page->getMedias("image");
        $updateMedia = [
            'target_path' => $medias['target_path'],
            'file_name' => $medias['file_name'],
            'file_type' => $medias['file_type'],
            'alt' => ($request->alt)?$request->alt:$medias['alt'],
            'description' => ($request->description)?$request->description:$medias['description'],
        ];
        $page->update([
            'image' => json_encode($updateMedia),
        ]);

        return response()->json(["message" => "L'image a bien été modifiée."]);
    }

    public function deleteImage(string $uuid)
    {
        $page = Page::where('uuid', $uuid)->FirstOrFail();
        $page->deleteFile('image');
        $page->update([
            'image' => null,
        ]);
        session()->flash('success',"L'image a bien été supprimée.");
        return redirect()->route($this->route . 'edit',["uuid" => $uuid]);
    }

    public function positions()
    {
        $positions = Page::ordered()->get();
        SEO::setTitle("Gestion des contenus - Gestion des positions");
        SEO::setDescription("Gestion des positions");
        SEO::opengraph()->setUrl(route($this->route . "position"));
        return view($this->viewPath . "position",compact("positions"));
    }

    public function positionsStore(Request $request)
    {
        $positions = [];
        foreach($request->orders as $order){
            if(isset($order['id']) && !empty($order['id'])) {
                $positions[] = $order['id'];
            }
        }
        if(!empty($positions)){
            Page::setNewOrder($positions);
        }
        return "Les positions ont bien été mises à jours.";
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $datatable = new DataTable();
        $ikCms = new Ikcms();
        $pages = Page::select([
            'pages.id',
            'pages.uuid',
            'pages.published',
            'pages.image',
            'pages.visibility',
            'pages.name',
            'pages.updated_at',
            "page_categories.name as section_name"
        ])->leftJoin('page_categories', 'pages.page_category_id', '=', 'page_categories.id');

        return DataTables::of($pages)
            ->addColumn('actions', function(Page $page) {
                return $this->getTableButtons($page->uuid);
            })
            ->editColumn("published",function(Page $page) use ($datatable) {
                return $datatable->yesOrNot($page->published);
            })
            ->editColumn("visibility",function(Page $page){
                if($page->visibility == "public") {
                    return '<span class="label is-success">Public</span>';
                }
                return '<span class="label is-error">Protégé par mot de passe</span>';
            })
            ->editColumn("updated_at",function(Page $page) use ($datatable) {
                return $datatable->date($page->updated_at);
            })
            ->editColumn("name",function(Page $page){
                return $page->translate("name");
            })
            ->editColumn("section_name",function(Page $page){
                return $page->translate("section_name");
            })

            ->editColumn("image",function(Page $page) use ($datatable, $ikCms) {
                $medias = $page->getMedias("image");
                if ($medias) {
                    return $datatable->image(
                        $ikCms->resizeImage($medias['target_path'], 30, 30),
                        $medias['target_path'],
                        $medias['alt']
                    );
                } else {
                    return "";
                }
            })
            ->rawColumns(['actions','published','image','visibility'])
            ->make(true);
    }

    private function getTableButtons($uuid): string
    {
        $editRoute = route($this->getRoute() . "edit",['uuid' => $uuid]);
        $deleteRoute = route($this->getRoute() . "delete",['uuid' => $uuid]);
        $html ='<a href="#" data-jq-dropdown="#drop-'.$uuid.'" class="droplink"><i class="fa fa-chevron-down"></i></a>';
        $html .='<div id="drop-'.$uuid.'" class="jq-dropdown jq-dropdown-tip action-drop-down table-drop-down">';
        $html .='<ul class="jq-dropdown-menu">';

        $html .= '<li><a href="'.$editRoute.'"><i class="fa fa-pencil"></i> Modifier</a></li>';
        $html .= '<li><a href="'.$deleteRoute.'" class="confirm-alert"><i class="fa fa-trash"></i> Supprimer</a></li>';

        $html .='</ul>';
        $html .='</div>';
        return $html;
    }
}