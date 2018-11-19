<?php
namespace Piclou\Ikcms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Piclou\Ikcms\Entities\Slider;
use Piclou\Ikcms\Facades\Ikcms;
use Piclou\Ikcms\Helpers\DataTable;
use Piclou\Ikcms\Requests\Admin\SlidersRequest;
use SEO;
use Yajra\DataTables\DataTables;

class SlidersController extends Controller
{
    protected $viewPath = 'ikcms::sliders.';
    private $route = 'ikcms.admin.sliders.';

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

        $total = Slider::count();
        $active = Slider::where('published',1)->count();
        $desactive = Slider::where('published',0)->count();

        SEO::setTitle("Gestion du slider");
        SEO::setDescription("Gestion du slider");
        SEO::opengraph()->setUrl(route($this->route . "index"));
        return view($this->viewPath . 'index',compact("total","active","desactive"));
    }

    public function create()
    {
        $slider = new Slider();
        SEO::setTitle("Gestion du slider - Ajouter");
        SEO::setDescription("Gestion du slider");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'create', compact("slider"));
    }

    public function store(SlidersRequest $request)
    {;
        $create = [
            'published' => ($request->published)?1:0,
            'link' => $request->link,
            'name' => json_encode($request->name, true),
            'description' => json_encode($request->description,true),
            'position' => $request->position,
        ];
        $slider = Slider::create($create);
        // Upload Image
        if($request->hasFile('image')){
            $slider->uploadImage('image', 'sliders', $request->image);
            $slider->update();
        }
        // Upload Vidéo
        if($request->hasFile('video')){
            $slider->uploadFile('video', 'sliders', $request->video);
            $slider->update();
        }

        session()->flash('success', "La slide a bien été créée.");
        return redirect()->route($this->route . 'index');
    }

    public function edit(string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        SEO::setTitle("Gestion du slider - Modifier") ;
        SEO::setDescription("Gestion du slider");
        SEO::opengraph()->setUrl(route($this->route . "create"));
        return view($this->viewPath . 'edit', compact("slider"));
    }

    public function update(SlidersRequest $request, string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $update = [
            'published' => ($request->published)?1:0,
            'name' => json_encode($request->name, true),
            'description' => json_encode($request->description,true),
            'link' => $request->link,
            'position' => $request->position,
        ];
        if($request->hasFile('image')){
            $medias = $slider->getMedias("image");
            $slider->uploadImage('image', 'sliders', $request->image);
            if($medias && file_exists($medias['target_path'])) {
                unlink($medias['target_path']);
            }
        }
        if($request->hasFile('video')){
            $medias = $slider->getMedias("video");
            $slider->uploadFile('video', 'sliders', $request->video);
            if($medias && file_exists($medias['target_path'])) {
                unlink($medias['target_path']);
            }
        }

        $slider->update($update);

        session()->flash('success', "La slide a bien été modifiée.");
        return redirect()->route($this->route . 'index');

    }

    public function destroy(string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $slider->deleteFile('image');
        $slider->deleteFile('video');
        $slider->delete();
        session()->flash('success',"La slide a bien supprimée.");
        return redirect()->route($this->route . 'index');
    }

    public function positions()
    {
        $positions = Slider::ordered()->get();
        SEO::setTitle("Gestion du slider - Gestion des positions");
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
            Slider::setNewOrder($positions);
        }
        return "Les positions ont bien été mises à jours.";
    }

    public function updateImage(Request $request, string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $medias = $slider->getMedias("image");
        $updateMedia = [
            'target_path' => $medias['target_path'],
            'file_name' => $medias['file_name'],
            'file_type' => $medias['file_type'],
            'alt' => ($request->alt)?$request->alt:$medias['alt'],
            'description' => ($request->description)?$request->description:$medias['description'],
        ];
        Slider::where('id', $slider->id)->update([
            'image' => json_encode($updateMedia),
        ]);
        return response()->json(["message" => "L'image a bien été modifiée."]);
    }

    public function deleteImage(string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $slider->deleteFile('image');
        $slider->update([
            'image' => null,
        ]);
        session()->flash('success',"L'image a bien été supprimée");
        return redirect()->route($this->route . 'edit',["uuid" => $uuid]);
    }

    public function updateVideo(Request $request, string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $medias = $slider->getMedias("video");
        $updateMedia = [
            'target_path' => $medias['target_path'],
            'file_name' => $medias['file_name'],
            'file_type' => $medias['file_type'],
            'alt' => ($request->alt)?$request->alt:$medias['alt'],
            'description' => ($request->description)?$request->description:$medias['description'],
        ];
        Slider::where('id', $slider->id)->update([
            'video' => json_encode($updateMedia),
        ]);

        return response()->json(["message" => "La vidéo a bien été modifiée."]);
    }

    public function deleteVideo(string $uuid)
    {
        $slider = Slider::where('uuid', $uuid)->FirstOrFail();
        $slider->deleteFile('video');
        $slider->update([
            'video' => null,
        ]);

        session()->flash('success',"La vidéo a bien été supprimée");
        return redirect()->route($this->route . 'edit',["uuid" => $uuid]);
    }

    /**
     * @return mixed
     */
    private function dataTable()
    {
        $datatable = new DataTable();
        $ikCms = new Ikcms();
        $sliders = Slider::select([
            'id',
            'uuid',
            'published',
            'name',
            'image',
            "order",
            'updated_at'
        ]);

        return DataTables::of($sliders)
            ->addColumn('actions', function(Slider $slider) {
                return $this->getTableButtons($slider->uuid);
            })
            ->editColumn("published",function(Slider $slider) use ($datatable) {
                return $datatable->yesOrNot($slider->published);
            })
            ->editColumn("updated_at",function(Slider $slider) use ($datatable) {
                return $datatable->date($slider->updated_at);
            })
            ->editColumn("image",function(Slider $slider) use ($datatable, $ikCms) {
                $medias = $slider->getMedias("image");
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
            ->editColumn("name",function(Slider $slider){
                return $slider->translate("name");
            })
            ->editColumn("order",function(Slider $slider) use ($datatable){
                return $slider->order;
            })
            ->rawColumns(['actions','image','published','order'])
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