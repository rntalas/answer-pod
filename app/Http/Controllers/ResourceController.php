<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class ResourceController extends Controller
{
    abstract protected function modelClass(): string;

    abstract protected function validatedData(Request $request, ?int $id = null): array;

    public function create()
    {
        return view($this->viewPath('create'));
    }

    public function store(Request $request): RedirectResponse
    {
        $class = $this->modelClass();
        $class::create($this->validatedData($request));

        return redirect()->route('page.show', ['slug' => '']);
    }

    public function show($id)
    {
        $class = $this->modelClass();
        $variable = strtolower(class_basename($class));
        $$variable = $class::findOrFail($id);

        return view($this->viewPath('view'), compact($variable));
    }

    public function edit($id)
    {
        $class = $this->modelClass();
        $variable = strtolower(class_basename($class));
        $$variable = $class::findOrFail($id);

        return view($this->viewPath('edit'), compact($variable));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $class = $this->modelClass();
        $item = $class::findOrFail($id);
        $item->update($this->validatedData($request, $id));

        return redirect()->route($this->routeName('show'), $id);
    }

    public function destroy($id): RedirectResponse
    {
        $class = $this->modelClass();
        $item = $class::findOrFail($id);
        $item->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function viewPath(string $view): string
    {
        return $this->viewPrefix().'.'.$view;
    }

    protected function routeName(string $name): string
    {
        return $this->routePrefix().'.'.$name;
    }

    abstract protected function viewPrefix(): string;

    abstract protected function routePrefix(): string;
}
