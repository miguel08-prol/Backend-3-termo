<?php

use App\Http\Controllers\Api\AuthorizationController;
use App\Http\Controllers\Api\GatewayController;
use App\Http\Controllers\Api\FaltaController;
use App\Http\Controllers\Api\ProfessorAuthorizationController;

use App\Http\Controllers\Api\TurmaController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (requerem token)
Route::middleware('auth:sanctum')->group(function () {
    
    // ========== AUTORIZAÇÕES SAFE ==========
    
    // Dashboard Admin - CORRIGIR AQUI
    Route::get('/admin/dashboard/stats', [AuthorizationController::class, 'getDashboardStats']);
    Route::get('/gateway/stats', [GatewayController::class, 'getStats']);
    
    // Admin/Coordenação
    Route::prefix('authorizations')->group(function () {
        Route::get('/', [AuthorizationController::class, 'index']);
        Route::post('/', [AuthorizationController::class, 'store']);
        Route::get('/professores', [AuthorizationController::class, 'getProfessores']);
        Route::get('/{id}', [AuthorizationController::class, 'show']);
        Route::put('/{id}', [AuthorizationController::class, 'update']);
        Route::delete('/{id}', [AuthorizationController::class, 'destroy']);
    });
    
    // Professor
// Professor
Route::prefix('professor')->group(function () {
    // Estatísticas do dashboard
    Route::get('/authorizations/stats', [ProfessorAuthorizationController::class, 'getStats']);
    
    // Listar todas autorizações do professor (histórico)
    Route::get('/authorizations/history', [ProfessorAuthorizationController::class, 'getHistory']);
    
    // Dados para o gráfico dos últimos 7 dias
    Route::get('/authorizations/daily', [ProfessorAuthorizationController::class, 'getDailyStats']);
    
    // Listar pendentes
    Route::get('/authorizations/pending', [ProfessorAuthorizationController::class, 'getPending']);
    
    // Buscar uma autorização específica
    Route::get('/authorizations/{id}', [ProfessorAuthorizationController::class, 'show']);
    
    // Aprovar/rejeitar
    Route::post('/authorizations/{id}/approve', [ProfessorAuthorizationController::class, 'approve']);
    Route::post('/authorizations/{id}/reject', [ProfessorAuthorizationController::class, 'reject']);
});
    
    // Portaria (Gateway)
Route::prefix('gateway')->group(function () {
    Route::get('/pending', [GatewayController::class, 'getPendingExits']);
    Route::get('/history', [GatewayController::class, 'getHistory']);
    Route::get('/search', [GatewayController::class, 'search']);
    Route::post('/{id}/exit', [GatewayController::class, 'registerExit']);
    Route::get('/stats', [GatewayController::class, 'getStats']);
});
    
    // ========== USUÁRIOS (MaintSys original) ==========
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
    
    // Perfil do usuário
    Route::get('/user/profile', [UserController::class, 'getProfile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::put('/user/notifications', [UserController::class, 'updateNotifications']);


    // Rotas de Turmas
Route::prefix('turmas')->group(function () {
    Route::get('/', [TurmaController::class, 'index']);
    Route::get('/list', [TurmaController::class, 'list']);
    Route::post('/', [TurmaController::class, 'store']);
    Route::get('/{id}', [TurmaController::class, 'show']);
    Route::put('/{id}', [TurmaController::class, 'update']);
    Route::delete('/{id}', [TurmaController::class, 'destroy']);
});

Route::get('/professores/list', [UserController::class, 'getProfessores']);
Route::get('/authorizations/professores', [AuthorizationController::class, 'getProfessores']);

// Rotas de Faltas
Route::prefix('faltas')->group(function () {
    Route::get('/aluno/{id}', [FaltaController::class, 'getFaltasByAluno']);
    Route::get('/aluno/{id}/resumo', [FaltaController::class, 'getResumoFaltas']);
    Route::get('/turma/{id}', [FaltaController::class, 'getFaltasByTurma']);
    Route::get('/relatorio', [FaltaController::class, 'getRelatorioGeral']);
    Route::post('/marcar', [FaltaController::class, 'marcarFalta']);
    Route::post('/{id}/justificar', [FaltaController::class, 'justificarFalta']);
});
});

