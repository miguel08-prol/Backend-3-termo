<?php
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\EstoqueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



//Rotas para estruturas de cientes para cadastro,edição e exclusão
//Rotas para montar o formulario
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
//Rota para editar os dados
// Route::get('/clientes/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
//Rota para receber os dados e salvar(post)
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::resource('clientes', ClienteController::class); 


Route::resource('estoque', EstoqueController::class);
Route::resource('fornecedores', FornecedorController::class);
Route::resource('produtos', ProdutoController::class);
Route::resource('pedidos', PedidoController::class);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
