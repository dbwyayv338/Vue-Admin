<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:config list', ['only' => ['setting']]);
    }

    /**
     * 可配置内容
     */
    const fields = [
        [
            'name' => 'base.site_state',
            'desc' => 'site state, 0: normal, 1: maintenance',
        ],
        [
            'name' => 'base.consumer_hotline',
            'desc' => 'Phone number for consumer hotline',
        ],
    ];

    /**
     * 系统配置
     */
    public function setting(Request $request)
    {
        if ($request->isMethod('GET')) {
            $config = collect(self::fields)->transform(function ($item) {
                $item['value'] = sysconf($item['name']);
                return $item;
            });
            return Inertia::render('Admin/Setting/Edit', [
                'fields' => $config->toArray(),
            ]);
        }
        if ($request->isMethod('POST')) {
            $post = $request->post();
            foreach ($post as $k => $v) sysconf($k, $v);
            return redirect()->route('admin.setting')
                ->with('message', 'Config updated successfully.');
        }
    }

}
