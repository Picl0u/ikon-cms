<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Piclou\Ikcms\Entities\User;
use Piclou\Ikcms\Helpers\DataTable;
use Piclou\Ikcms\Requests\Admin\UsersRequest;
use SEO;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    protected $viewPath = 'ikcms::users.';
    private $route = 'ikcms.admin.users.';

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

        $total = User::count();
        $active = User::where('online',1)->count();
        $desactive = User::where('online',0)->count();

        SEO::setTitle("Gestion des administrateurs");
        SEO::setDescription("Gestion des administrateurs");
        SEO::opengraph()->setUrl(route("ikcms.admin.users.index"));
        return view($this->viewPath . 'index',compact("total","active","desactive"));
    }

    public function create()
    {
        $user = new User();
        SEO::setTitle("Gestion des administrateurs - Ajouter") ;
        SEO::setDescription("Gestion des administrateurs");
        SEO::opengraph()->setUrl(route("ikcms.admin.users.create"));
        return view($this->viewPath . 'create', compact("user"));
    }

    public function store(UsersRequest $request)
    {
        $create = [
            'online' => ($request->online)?1:0,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'name' => $request->firstname .  ' ' . $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ];
        User::create($create);

        session()->flash('success', "L'administrateur a bien été créé.");
        return redirect()->route($this->route . 'index');
    }

    public function edit(string $uuid)
    {
        $user = User::where('uuid', $uuid)->FirstOrFail();
        SEO::setTitle("Gestion des administrateurs - Modifier") ;
        SEO::setDescription("Gestion des administrateurs");
        SEO::opengraph()->setUrl(route("ikcms.admin.users.create"));
        return view($this->viewPath . 'edit', compact("user"));
    }

    public function update(UsersRequest $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $update = [
            'online' => ($request->online)?1:0,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'name' => $request->firstname .  ' ' . $request->lastname,
            'email' => $request->email,
            'role' => $request->role
        ];
        if($request->password && !empty($request->password)) {
            $update['password'] = bcrypt($request->password);
        }

        $user->update($update);

        session()->flash('success', "L'administrateur a bien été modifié.");
        return redirect()->route($this->route . 'index');

    }

    public function destroy(string $uuid)
    {
        $user = User::where('uuid', $uuid)->FirstOrFail();
        $user->delete();
        session()->flash('success',"L'administrateur a bien supprimé.");
        return redirect()->route($this->route . 'index');
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $datatable = new DataTable();
        $users = User::select([
            'id',
            'uuid',
            'online',
            'name',
            'email',
            'role',
            'updated_at'
        ]);

        return DataTables::of($users)
            ->addColumn('actions', function(User $user) {
                return $this->getTableButtons($user->uuid);
            })
            ->editColumn("online",function(User $user) use ($datatable) {
                return $datatable->yesOrNot($user->online);
            })
            ->editColumn("email",function(User $user) use ($datatable) {
                return $datatable->email($user->email);
            })
            ->editColumn("updated_at",function(User $user) use ($datatable) {
                return $datatable->date($user->updated_at);
            })
            ->rawColumns(['actions','email','online'])
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