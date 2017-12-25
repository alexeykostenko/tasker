<?php

namespace App\Controllers;

use App\Classes\Image;
use Library\Validation\Validator;

class TaskController
{
    public function create()
    {
        $tasks = [];
        return view('create', compact('tasks'));
    }

    public function store()
    {
        request()->validate([
            'id' => 'number|exists:tasks|number',
            'name' => 'required|max:45',
            'email' => 'required|max:255',
            'text' => 'required|max:255',
        ]);

        $data = [];

        $data = request()->all();
        $formImage = request()->files['image'];

        if ($formImage['tmp_name']) {
            $type = strtoupper(basename($formImage['type']));
            $allowedTypes = config('image_type');

            if (Validator::typeValidate($type, $allowedTypes)) {
                $img = Image::make($formImage['tmp_name']);
                $data['image'] = $img->save($formImage['name']);
            }
        }

        model('Task')->create($data);
        return request()->redirect('/');
    }

    public function edit()
    {
        if (! auth()->check()) {
            return request()->redirect('/');
        }

        $task = model('Task')->find(request()->id);
        return view('edit', compact('task'));
    }

    public function update()
    {
        request()->validate([
            'id' => 'number|exists:tasks|number',
            'name' => 'required|max:45',
            'email' => 'required|max:255',
            'text' => 'required|max:255',
        ]);

        if (! auth()->check()) {
            return request()->redirect('/');
        }

        $data = [];

        $data = request()->all();
        $formImage = request()->files['image'];

        if ($formImage['tmp_name']) {
            $type = strtoupper(basename($formImage['type']));
            $allowedTypes = config('image_type');

            if (Validator::typeValidate($type, $allowedTypes)) {
                $img = Image::make($formImage['tmp_name']);
                $data['image'] = $img->save($formImage['name']);
            }
        }

        model('Task')->update($data, request()->id);

        return request()->redirect('/');
    }

    public function show()
    {
        $tasks = model('Task')->orderBy('id', 'desc')->get();
        return view('list', compact('tasks'));
    }
}
