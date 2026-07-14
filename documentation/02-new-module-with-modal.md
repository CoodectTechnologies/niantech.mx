# Creación de un módulo administrativo basado en un crud basico 

Cuando necesitamos realizar un módulo basico, podemos usar modales donde ahi se tengan los formularios, en una misma vista. Esto por la sencilles del módulo basico.

## Contenido

- [1. Modelo y migración](#1-modelo-y-migración)
  - [1.1. Creación del modelo](#11-creación-del-modelo)
  - [1.2. Modificación del modelo App/Models/Task](#12-modificación-del-modelo-appmodelstask)
  - [1.3. Modificación de la migración _create_tasks_table.php](#13-modificación-de-la-migración-_create_tasks_tablephp)
- [2. Controladores y Rutas](#2-controladores-y-rutas)
  - [2.1. Creación del controlador](#21-creación-del-controlador)
  - [2.2. Creación de la ruta que apuntará al controlador](#22-creación-de-la-ruta-que-apuntará-al-controlador)
  - [2.3. Modificación del controlador TaskController](#23-modificación-del-controlador-taskcontroller)
  - [2.4. Creación de las vistas](#24-creación-de-las-vistas)
- [3. Listado y formulario con Livewire](#3-listado-y-formulario-con-livewire)
  - [3.1. Creación del componente de listado](#31-creación-del-componente-de-listado)
  - [3.2. Creación del componente de formulario](#32-creación-del-componente-de-formulario)
- [4. Menú del sistema](#4-menú-del-sistema)

### 1.- Modelo y migración:

#### Creación del módelo
Recuerda estar posicionado sobre la ruta del proyecto.
```bash
php artisan make:model Task -m
```
Como podemos observar se agregó un parametro llamado -m el cual significa que tambien queremos especificar que queremos crear su migración correspondiente para la creación de la tabla de base de datos

#### Módificación del modelo App/Models/Task
```php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    //Agregamos esta linea de codigo para permitir insercciones en este modelo
    protected $guarded = [];

    //Agregamos una función de obtención de fecha (opcional)
    public function dateToString(){
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
}
```

#### Módificación de la migración _create_taks_table.php
```php
return new class extends Migration
{
    public function up()
    {
        Schema::create('taks', function (Blueprint $table) {
            $table->id();
            //Agregamos los campos que contendra la tabla
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
}
```
Una vez agregadas las columnas name y description, podemos ejecutar el comando 
```bash
php artisan migrate
```
Este comando nos ayudará a ejecutar las migraciones nuevas, tal cual como esta que acabamos de crear. Una vez ejecutado ya tendremos las tablas listas en la base de datos.

### 2.- Controladores y Rutas

#### Creación del controlador
```bash
php artisan make:controller Admin/Task/TaskController
```
Con este comando podemos notar que los controladores administrativos son adjuntados dentro de la carpeta Admin, y el nombre del controlador deberá de tener el nombre de su objeivo, en este caso crearemos un crud de tareas, por lo tanto la carpeta se llamará Task y dentro de ésta su controlador.

#### Creación de la ruta que apuntará al controlador
En el archivo routes\admin.php deberemos de colocar este código
```php
use App\Http\Controllers\Admin\Task\TaskController;

Route::get('/task', [TaskController::class, 'index'])->name('task.index');
```

Con metodo resource se ha logrado tener la ruta GET ejem:
```bash
MÉTODO      RUTA                    NOMBRE DE RUTA          FUNCIÓN A LA QUE APUNTA
========    =================       =================       ===============================
GET|HEAD    admin/task              admin.task.index    ›   Admin\Task\TaskController@index  
```

#### Modificaremos el controlador TaskController creando las funciones que tendrán las vistas necesarias
```php
use App\Models\Task;

public function index(){
    return view('admin.task.index');
}
public function create(Task $task){
    return view('admin.task.create', compact('task'));
}
public function edit(Task $task){
    return view('admin.task.edit', compact('task'));
}
```

#### Crearemos las vistas que especificamos en el controlador
* resources/views/admin/task/index.blade.php
* resources/views/admin/task/create.blade.php
* resources/views/admin/task/edit.blade.php
* resources/views/admin/task/delete.blade.php

Vista resources/views/admin/task/index.blade.php
```html
@extends('admin.layouts.main')

@section('head')
    <title>{{ __('Tasks') }}</title>
@endsection

@section('breadcrumb')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div 
            data-kt-swapper="true" 
            data-kt-swapper-mode="prepend" 
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" 
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                {{ __('Tasks') }}
            </h1>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_content_container" class="container-xxl">
        <!-- Aqui estará el listado de tasks con livewire -->
    </div>
@endsection
```

Vista resources/views/admin/task/create.blade.php
```html
<button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_task">
    <span class="svg-icon svg-icon-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="black" />
            <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="black" />
            <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="black" />
        </svg>
    </span>
    {{ __('New') }}
</button>
<div wire:ignore.self class="modal fade" id="kt_modal_add_task" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">{{ __('New') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!-- Aqui estará el formulario de tasks con livewire -->
            </div>
        </div>
    </div>
</div>
```

Vista resources/views/admin/task/edit.blade.php
```html
<button class="btn btn-icon btn-active-light-success w-30px h-30px me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_update_task_{{ $task->id }}">
    <span class="svg-icon svg-icon-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="black" />
            <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="black" />
        </svg>
    </span>
</button>
<div wire:ignore.self class="modal fade" id="kt_modal_update_task_{{ $task->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">{{ __('Update') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7">
                <!-- Aqui estará el formulario de tasks con livewire -->
            </div>
        </div>
    </div>
</div>
```

Vista resources/views/admin/task/delete.blade.php
```html
<button onclick="event.preventDefault(); confirmDestroyCatalogTask('{{ $task->id }}')" class="btn btn-icon btn-active-light-danger w-30px h-30px">
    <span class="svg-icon svg-icon-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
        </svg>
    </span>
</button>
@once
    @push('footer')
        <script>
            function confirmDestroyCatalogTask(id){
                swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You will not be able to retrieve this record') }}",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Yes, delete') }}</span>",
                    cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancel') }}</span>",
                    reverseButtons: true,
                    cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                    confirmButtonClass: "btn btn-danger",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        <!-- Aquí llamaremos al método destroy del componente de livewire -->
                    }
                });
            }
        </script>
    @endpush
@endonce
```

Hasta este punto ya podemos ingresar a las rutas del listado en su respectiva url /admin/task/ aun que estará vacia en este momento.

### Creación del listado y formulario reutilizable con Livewire

Ahora es el turno de Livewire para darle reactividad al código

```
php artisan make:livewire admin.task.index
```
```
php artisan make:livewire admin.task.form
```

Con estos comandos ejecutados se debieron de crear 4 archivos
* app/Http/Livewire/Admin/Task/Index.php
* resources/views/livewire/admin/task/index.blade.php

* app/Http/Livewire/Admin/Task/Form.php
* resources/views/livewire/admin/task/form.blade.php

Empezaremos modificando el componente app/Http/Livewire/Admin/Task/Index.php

```php
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Task;
use Exception;

class Index extends Component
{
    //Especificamos que queremos usar la paginación de livewire
    use WithPagination;

    //Especificamos que la url del navegador será dinamica segun lo que contenga la variable publica search 
    protected $queryString = ['search'];

    //Colocamos un escuchador de eventos, si desde el formulario se lanzara un evento llamado "render" será actualizada la función del mismo nombre
    protected $listeners = ['render'];

    //Especificamos que queremos utilizar la paginación de bootstrap
    protected $paginationTheme = 'bootstrap';

    //Variable publica que podrá ser manipulada desde el frontend
    public $search;

    //Método para resetear el paginado si la variable publica $seach esta siendo ejecutada
    public function updatingSearch(){
        $this->resetPage();
    }
    public function render(){
        $taks = Task::orderBy('id', 'desc')->paginate();
        return view('livewire.admin.task.index', compact('taks'));
    }
    public function destroy(Task $task){
        try{
            $task->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        }catch(Exception $e){
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
```

Seguimos con la vista del componente el cual recordemos que estaba en resources/views/livewire/admin/task/index.blade.php
```html
<div>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black" />
                        </svg>
                    </span>
                    <input wire:model.live.debounce.500ms="search" type="search" class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Search...') }}" />
                </div>
            </div>
            <div class="card-toolbar">
                @include('admin.task.create')
            </div>
        </div>
        <div class="card-body py-4">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">{{ __('Task') }}</th>
                            <th class="min-w-125px">{{ __('Description') }}</th>
                            <th class="min-w-125px">{{ __('Date') }}</th>
                            <th class="min-w-125px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold">
                        @foreach ($taks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->dateToString() }}</td>
                            <td class="">
                                <!-- Incluimos las vistas edit y delete -->
                                @include('admin.task.edit')
                                @include('admin.task.delete')
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $taks->links() }}
        </div>
    </div>
    @push('footer')
        <script>
            <!-- Cuando se escuche el evento 'render' entonces ocultaremos los modales abiertos -->
            Livewire.on('render', function(){
                $('.modal').modal('hide');
            });
        </script>
    @endpush
</div>
```

Ahora nos pasaremos a modificar el componente app/Http/Livewire/Admin/Task/Form.php

```php
use Livewire\Component;
use App\Models\Task;

class Form extends Component
{
    public $task;
    public $method;

    protected function rules(){
        return [
            'task.name' => 'required',
            'task.description' => 'required'
        ];
    }
    public function mount(Task $task, $method){
        $this->task = $task;
        $this->method = $method;
    }
    public function render(){
        return view('livewire.admin.task.form');
    }
    public function store(){
        $this->validate();
        $this->task->save();
        $this->task = new Task();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update(){
        $this->validate();
        $this->task->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}
```

Seguimos con la vista del componente el cual recordemos que estaba en resources/views/livewire/admin/task/form.blade.php
```html
<div>
    @include('admin.components.errors')
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Name') }}</span>
            </label>
            <input type="text" required wire:model="task.name" class="form-control form-control-solid @error('task.name') invalid-feedback @enderror" placeholder="{{ __('Task name') }}" name="" />
            @error('task.name') <small  class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Description') }}</span>
            </label>
            <input type="text" wire:model="task.description" class="form-control form-control-solid @error('task.description') invalid-feedback @enderror" placeholder="{{ __('Task name') }}" name="" />
            @error('task.description') <small  class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i class="fa fa-arrow-left"></i></button>
            <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit" class="btn btn-primary">
                <span class="indicator-label">{{ __('Save changes') }}</span>
                <span wire:loading wire:target="{{ $method }}" class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
    </form>
</div>
```

Una vez terminados los componentes de livewire, procederemos a llamarlos en sus respectivos lugares donde serán utilizados

Vista resources/views/admin/task/index.blade.php
```html
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <!-- Aquí mandaremos a llamar el componente de listado -->
        @livewire('admin.task.index', key('index'))
    </div>
@endsection
```

Vista resources/views/admin/task/create.blade.php
```html
<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
    <!-- Aquí mandaremos a llamar el componente del formulario con el método store -->
    @livewire('admin.task.form', ['method' => 'store'], key('create'))
</div>
```

Vista resources/views/admin/task/edit.blade.php
```html
<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
    <!-- Aquí mandaremos a llamar el componente del formulario con el método store -->
    @livewire('admin.task.form', ['task' => $task, 'method' => 'update'], key('edit-'.$task->id))
</div>
```

Vista resources/views/admin/task/delete.blade.php
```js
if(result.isConfirmed){
    @this.call('destroy', id);
}
```

¡Bien! hasta ahora ya tienes lista la ruta, controlador, modelo, migración, Vistas, Componentes de livewire. ¿Ahora que sigue? ...

## Menu de sistema
Actualmente en el archvio config\menu-system.php se divide por secciones,  (Dashborad, Web, Ecommerce, Setting). Este archivo solo impacta en el menu del administrador.

Podemos agregar o modificar las secciones presentes, en este ejemplo agregaremos a la sección Web
Modifica el archivo config\menu-system.php agregando un nuevo menu ejem:
```php
 [
    'section' => [
        'name' => 'Web',
        'modules' => [
            ... // Resto del código
            //Aqui tu código
            [
                'name' => 'Tasks',
                'icon' => 'fa-solid fa-pen',
                'urlName' => 'admin.task.index',
                'active' => 'admin.task*',
                'canany' => [null],
                'submodules' => []
            ],
        ]
    ]
]
```

¡Felicidades! Has creado tu primer módulo dentro de la ecommerce. Si todo lo has realizado bien, deberás de ver un nuevo menu en la sección web del administrador.
