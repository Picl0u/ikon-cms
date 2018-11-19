<?php

namespace Piclou\Ikcms\Helpers;

class DataTable
{
    public function yesOrNot($key): string
    {
        if (!$key || empty($key)) {
            return '<span class="label is-error">' . __("ikcms::admin.no") . '</span>';
        }
        return '<span class="label is-success">' . __("ikcms::admin.yes") . '</span>';
    }

    public function image(string $imageResize, string $image, $alt = null)
    {
        return '<img src="' . $imageResize . '" alt="' . $alt . '" class="remodalImg" data-src="/' . $image . '">';
    }

    public function date($date)
    {
        return date('d/m/Y H:i', strtotime($date));
    }

    public function email($email)
    {
        return '<a href="mailto:' . $email . '">' . $email . '</a>';
    }

    public function order(int $order)
    {
        return ' <div class="input-number" data-kube="number">
            <span class="is-down">-</span>
            <input type="number" min="1" step="1" value="' . $order . '">
            <span class="is-up">+</span>
        </div>';
    }
}