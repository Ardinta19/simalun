<?php

namespace App\View\Components;

use App\Support\BackUrl;
use Illuminate\View\Component;

class BackButton extends Component
{
    public string $url;

    public string $label;

    public string $style;

    /**
     * @param  string  $fallback  Named route fallback (e.g. 'customer.orders')
     * @param  array  $params  Route parameters untuk fallback
     * @param  string  $label  Label teks (default: 'Kembali')
     * @param  string  $style  Variant: 'hero' (putih transparan), 'default' (solid), 'text' (link only)
     */
    public function __construct(
        string $fallback = 'dashboard',
        array $params = [],
        string $label = 'Kembali',
        string $style = 'default'
    ) {
        $this->url = BackUrl::resolve(request(), $fallback, $params);
        $this->label = $label;
        $this->style = $style;
    }

    public function render()
    {
        return view('components.back-button');
    }
}
