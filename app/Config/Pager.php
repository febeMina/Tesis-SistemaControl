<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public array $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'bootstrap_pagination' => 'App\Views\Pagers\bootstrap_pagination', 
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
     
     public $group1 = [
        'full_tag_open' => '<nav><ul class="pagination">',
        'full_tag_close' => '</ul></nav>',
        'num_tag_open' => '<li class="page-item">',
        'num_tag_close' => '</li>',
        'cur_tag_open' => '<li class="page-item active"><a href="#">',
        'cur_tag_close' => '</a></li>',
        'next_tag_open' => '<li class="page-item">',
        'next_tag_close' => '</li>',
        'prev_tag_open' => '<li class="page-item">',
        'prev_tag_close' => '</li>',
        'first_tag_open' => '<li class="page-item">',
        'first_tag_close' => '</li>',
        'last_tag_open' => '<li class="page-item">',
        'last_tag_close' => '</li>',
    ];
    
    public int $perPage = 20;
}
