<?php
namespace Piclou\Ikcms\Helpers;
use \Form;

class IkForm
{
    private $placeholder = false;

    public function __construct($form, $session, $ikcms)
    {
        $this->form = $form;
        $this->session = $session;
        $this->ikcms = $ikcms;
    }

    /**
     * Ouvre le formulaire en fonction d'une route
     * @param string $route
     * @param array $options
     * @return mixed
     */
    public function open(string $route, array $options = [])
    {
        $options['url'] = $route;
        if (isset($options['placeholder']) && $options['placeholder'] === true) {
            $this->placeholder = true;
        }
        return $this->form->open($options);
    }

    /**
     * Ouvre le formulaire en fonction d'un model
     * @param $model
     * @param array $options
     * @return mixed
     */
    public function model($model, array $options = [])
    {
        $class = new \ReflectionClass(get_class($model));
        $routeName = "ikcms.admin." . strtolower(str_plural($class->getShortName()));

        if($model->id) {
           $options['method'] = 'PUT';
           if (!isset($options['route'])) {

               $options['route'] = [$routeName . '.update', $model->uuid];
           }
        } else {
            if (!isset($options['route'])) {
                $options['route'] = [$routeName . '.store'];
            }
        }
        if (isset($options['placeholder']) && $options['placeholder'] === true) {
            $this->placeholder = true;
        }
        return $this->form->model($model, $options);
    }

    public function text(string $name, $label = null, $value = null, array $options = [])
    {
        if($value == "null"){ $value = null; }
        return $this->input("text", $name, $label, $value, $options);
    }

    public function textarea(string $name, $label = null, $value = null, array $options = [])
    {
        if($value == "null"){ $value = null; }
        return $this->input("textarea", $name, $label, $value, $options);
    }

    public function editor(string $name, $label = null, $value = null, array $options = [])
    {
        if (!isset($options['class'])) {
            $options['class'] = 'html-editor';
        } else {
            $options['class'] .= ' html-editor';
        }
        return $this->input("textarea", $name, $label, $value, $options);
    }

    public function select(string $name, $label = null, $value = null, array $list = [], array $options = [])
    {
        if(is_array($label)) {
            $list = $label;
            $label = null;
        }
        if(is_array($value)) {
            $list = $value;
            $value = null;
        }
        return $this->input("select", $name, $label, $value, $options, $list);
    }

    public function image(string $name, $label = null, $value = null, array $options = [])
    {
        return $this->input("image", $name, $label, $value, $options);
    }

    public function file(string $name, $label = null, $value = null, array $options = [])
    {
        return $this->input("file", $name, $label, $value, $options);
    }

    public function email(string $name, $label = null, $value = null, array $options = [])
    {
        return $this->input("email", $name, $label, $value, $options);
    }

    public function password(string $name, $label = null, $value = null, array $options = [])
    {
        return $this->input("password", $name, $label, $value, $options);
    }

    public function checkbox(string $name, $label = null, $value = null, array $options = []) {
        return $this->input("checkbox", $name, $label, $value, $options);
    }

    /**
     * Génère le bouton Sauvegarder
     * @param null $name
     * @return string
     */
    public function submit($name = null)
    {
        if(!$name) {
            $name = __('ikcms::admin.save');
        }
        return '
        <div class="form-item is-buttons">
            <button type="submit" class="button">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                '. $name .'
            </button>
        </div>';
    }

    /**
     * Ferme le formulaire
     * @return mixed
     */
    public function close()
    {
        return $this->form->close();
    }

    /**
     * Génère l'HTML pour les champs
     * @param $type
     * @param string $name
     * @param null $label
     * @param null $value
     * @param array $options
     * @return string
     */
    private function input($type, string $name, $label = null, $value = null, array $options = [], array $list = [])
    {
        $errors = $this->session->get('errors');
        if(is_array($label)) {
            $options = $label;
            $label = null;
        }
        if(is_array($value)) {
            $options = $value;
            $value = null;
        }
        if (!$label) {
           $label = __("ikcms::form.{$name}");
        }
        if($errors && $errors->has($name)) {
            if (isset($options['class'])) {
                $options['class'] .= 'is-error';
            } else {
                $options['class'] = 'is-error';
            }
        }
        if($this->placeholder) {
            $label = false;
            $options['placeholder'] = $label;
        }

        $return = '<div class="ik-field form-' . $type . '">';
            $return .= '<div class="is-row">';
                if($label !== false) {
                    $return .= '<div class="is-col is-25">';
                    $return .= $this->form->label($name, $label);
                    if(isset($options['desc'])) {
                        $return .= '<div class="is-desc">' . $options['desc'] .'</div>';
                        unset($options['desc']);
                    }
                    $return .= '</div>';
                }
                $return .= '<div class="is-col is-75">';
                    if ($type == "textarea") {
                        $return .= $this->form->textarea($name, $value , $options);
                    } elseif ($type == "select") {
                        $return .= $this->form->select($name, $list, $value, $options);
                    } elseif ($type == "checkbox") {
                        $return .='<label class="rocker rocker-small">';
                            $return .= $this->form->checkbox($name, 1, (isset($options["default"]) && $options["default"] == $value)?true:false);
                            $return .='<span class="switch-left">Oui</span>';
                            $return .='<span class="switch-right">Non</span>';
                        $return .='</label>';
                    } elseif ($type == "image") {
                       $return .= $this->getImageInput($name, $value , $options);
                    }  else {
                        $return .= $this->form->input($type, $name, $value , $options);
                    }
                    if($errors && $errors->has($name)) {
                        $return .='<div class="is-error">' . $errors->first($name) . '</div>';
                    }
                $return .= '</div>';

            $return .= '</div>';
        $return .= '</div>';

        return $return;
    }

    private function getImageInput(string $name, $value = null, array $options = [])
    {
        $return = "";
        if($value) {
            $return .= '<div class="image-form">';
            $return .= ' <img src="'.$this->ikcms->resizeImage($value, 100, 100).'" alt="" class="remodalImg" data-src="/'.$value.'" >';
            $return .= '</div>';
        }
        $return .= $this->form->input("file", $name, $value , $options);

        return $return;
    }
}