<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Piclou\Ikcms\Entities\PageCategories;
use Illuminate\Http\Request;
use Piclou\Ikcms\Helpers\DataTable;
use Piclou\Ikcms\Requests\Admin\PageCategoriesRequest;
use SEO;
use Yajra\DataTables\DataTables;

class PageCategoriesController extends Controller
{
    protected $viewPath = 'ikcms::pages.categories.';
    private $route = 'ikcms.admin.pagecategories.';

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

        $total = PageCategories::count();
        $active = PageCategories::where('published',1)->count();
        $desactive = PageCategories::where('published',0)->count();

        SEO::setTitle("Gestion des sections pour vos pages");
        SEO::setDescription("Gestion des sections pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "index"));
        return view($this->viewPath . 'index',compact("total","active", "desactive"));
    }

    public function create()
    {
        $pageCategory = new PageCategories();
        SEO::setTitle("Gestion des sections pour vos pages - Ajouter");
        SEO::setDescription("Gestion des sections pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'create', compact("pageCategory"));
    }

    public function store(PageCategoriesRequest $request)
    {;
        $create = [
            'published' => ($request->published)?1:0,
            'name' => json_encode($request->name, true),
        ];
        $pageCategory = PageCategories::create($create);

        session()->flash('success', "La section a bien été créée.");
        return redirect()->route($this->route . 'index');
    }

    public function edit(string $uuid)
    {
        $pageCategory = PageCategories::where('uuid', $uuid)->FirstOrFail();
        SEO::setTitle("Gestion des sections pour vos pages - Modifier") ;
        SEO::setDescription("Gestion des sections pour vos pages");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'edit', compact("pageCategory"));
    }

    public function update(PageCategoriesRequest $request, string $uuid)
    {
        $pageCategory = PageCategories::where('uuid', $uuid)->FirstOrFail();
        $update = [
            'published' => ($request->published)?1:0,
            'name' => json_encode($request->name, true),
        ];

        $pageCategory->update($update);

        session()->flash('success', "La section a bien été modifiée.");
        return redirect()->route($this->route . 'index');

    }

    public function destroy(string $uuid)
    {
        $pageCategory = PageCategories::where('uuid', $uuid)->FirstOrFail();
        $pageCategory->delete();
        session()->flash('success',"La section a bien supprimée.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $datatable = new DataTable();
        $pageCategories = PageCategories::select([
            'id',
            'uuid',
            'published',
            'name',
            'updated_at'
        ]);

        return DataTables::of($pageCategories)
            ->addColumn('actions', function(PageCategories $pageCategory) {
                return $this->getTableButtons($pageCategory->uuid);
            })
            ->editColumn("published",function(PageCategories $pageCategory) use ($datatable) {
                return $datatable->yesOrNot($pageCategory->published);
            })
            ->editColumn("updated_at",function(PageCategories $pageCategory) use ($datatable) {
                return $datatable->date($pageCategory->updated_at);
            })
            ->editColumn("name",function(PageCategories $pageCategory){
                return $pageCategory->translate("name");
            })
            ->rawColumns(['actions','published',])
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