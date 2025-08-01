<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Список услуг для CRM
    public function index()
    {
        $services = Service::orderByDesc('created_at')->paginate(20);
        return view('crm.services', compact('services'));
    }

    // Форма создания услуги (CRM)
    public function create()
    {
        return view('crm.service_create');
    }

    // Сохранение услуги
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:4096',
        ]);
        $path = $request->file('image')->store('services', 'public');
        $service = Service::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => '/storage/' . $path,
        ]);
        return redirect()->route('crm.services.index')->with('success', 'Услуга добавлена!');
    }

    // Форма редактирования услуги
    public function edit(Service $service)
    {
        return view('crm.service_edit', compact('service'));
    }

    // Обновление услуги
    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:4096',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $service->image = '/storage/' . $path;
        }
        $service->title = $data['title'];
        $service->description = $data['description'];
        $service->save();
        return redirect()->route('crm.services.index')->with('success', 'Услуга обновлена!');
    }

    // Удаление услуги
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('crm.services.index')->with('success', 'Услуга удалена!');
    }
}
