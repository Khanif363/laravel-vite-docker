<?php

use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\AuthController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\DeviceController;
use App\Http\Controllers\Panel\CatalogController;
use App\Http\Controllers\Panel\ServiceController;
use App\Http\Controllers\Panel\LocationController;
use App\Http\Controllers\Panel\DashboardController;
use App\Http\Controllers\Panel\User3EasyController;
use App\Http\Controllers\Panel\DepartmentController;
use App\Http\Controllers\Panel\ChangeManageController;
use App\Http\Controllers\Panel\ProblemManageController;
use App\Http\Controllers\Panel\ResumeClosingController;
use App\Http\Controllers\Panel\TroubleTicketController;
use App\Http\Controllers\Panel\UploadController;
use App\Http\Controllers\Panel\UserManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'login'])->name('/');

Route::name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login.show');
    Route::post('/login', 'loginProcess')->name('login');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/refresh-session', 'refreshSession')->name('refresh.session');
});


Route::middleware(['auth', 'permission'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('/home');

    Route::prefix('tickets')->group(function () {
        Route::controller(TroubleTicketController::class)->group(function () {
            Route::get('/', 'index')->name('tickets.index');
            Route::get('/alert', 'getAlert')->name('tickets.alert');
            Route::get('/total', 'getTotalTicket')->name('tickets.total');
            Route::get('/mttr', 'getMTTR')->name('tickets.mttr');
            Route::get('/mtta', 'getMTTA')->name('tickets.mtta');
            Route::get('/top-ticket', 'getTopTicket')->name('tickets.top-ticket');
            Route::get('/total-open-ticket-more-than-3-days', 'getTotalOpenTicketMoreThan3Days')->name('tickets.total-open-ticket-more-than-3-days');
            Route::get('/total-skor-kepuasan-pelanggan', 'getTotalSkorKepuasanPelanggan')->name('tickets.total-skor-kepuasan-pelanggan');
            Route::get('/{ticket}/depart', 'lastDepartment');
            Route::get('/create', 'viewCreate')->name('tickets.create');
            Route::get('/{id}/detail', 'detail')->name('tickets.detail');
            Route::post('/', 'createTicket')->name('tickets.store');
            Route::get('/{ticket}/update', 'viewUpdate')->name('tickets.edit');
            Route::put('/{ticket}', 'updateProgress')->name('tickets.update');
            Route::put('/{ticket}/edit-progress', 'editProgress')->name('tickets.edit-progress');
            Route::put('/{ticket}/edit-create', 'editCreate')->name('tickets.edit-create');
            Route::get('/export', 'export')->name('tickets.export');
            Route::get('/{id}/send-resume', 'viewSendResume')->name('tickets.send-resume');
            Route::post('/{id}/send-resume', 'sendResume')->name('tickets.send-resume-post');
            Route::post('/push-notification/{id}', 'pushNotif')->name('tickets.push');
            // Route::post('/delete-ticket-notif/{id}', 'deleteTicketNotif')->name('tickets.notif-delete');
            Route::delete('/delete-ticket/{id}', 'deleteTicket')->name('tickets.delete');
            Route::post('/notification-read/{id}', 'readNotif')->name('notif.read');
        });
    });
    Route::prefix('departments')->group(function () {
        Route::controller(DepartmentController::class)->group(function () {
            Route::get('/', 'index')->name('departments.index');
            Route::get('/create', 'viewCreate')->name('departments.create');
            Route::post('/', 'createDepartment')->name('departments.store');
            Route::get('/{id}/update', 'viewUpdate')->name('departments.edit');
            Route::put('/{id}', 'updateDepartment')->name('departments.update');
            Route::get('/export', 'export')->name('departments.export');
            Route::delete('/{id}', 'deleteDepartment')->name('departments.delete');
            Route::get('/get-by-type', 'getDepartmentByTipe')->name('departments.get-by-tipe');
        });
    });
    Route::prefix('devices')->group(function () {
        Route::controller(DeviceController::class)->group(function () {
            Route::get('/', 'index')->name('devices.index');
            Route::get('/create', 'viewCreate')->name('devices.create');
            Route::post('/', 'createDevice')->name('devices.store');
            Route::get('/{id}/update', 'viewUpdate')->name('devices.edit');
            Route::put('/{id}', 'updateDevice')->name('devices.update');
            Route::delete('/{id}', 'deleteDevice')->name('devices.delete');
            Route::get('/export', 'export')->name('devices.export');
        });
    });
    Route::prefix('services')->group(function () {
        Route::controller(ServiceController::class)->group(function () {
            Route::get('/', 'index')->name('services.index');
            Route::get('/create', 'viewCreate')->name('services.create');
            Route::post('/', 'createService')->name('services.store');
            Route::get('/{id}/update', 'viewUpdate')->name('services.edit');
            Route::put('/{id}', 'updateService')->name('services.update');
            Route::delete('/{id}', 'deleteService')->name('services.delete');
            Route::get('/export', 'export')->name('services.export');
        });
    });
    Route::prefix('resumes')->group(function () {
        Route::controller(ResumeClosingController::class)->group(function () {
            Route::get('/', 'index')->name('resumes.index');
            Route::get('/create', 'viewCreate')->name('resumes.create');
            Route::post('/', 'createResume')->name('resumes.store');
            Route::get('/{id}/update', 'viewUpdate')->name('resumes.edit');
            Route::put('/{id}', 'updateResume')->name('resumes.update');
            Route::delete('/{id}', 'deleteResume')->name('resumes.delete');
            Route::get('/export', 'export')->name('resumes.export');
        });
    });
    Route::prefix('user-managements')->group(function () {
        Route::controller(UserManagementController::class)->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('/create', 'viewCreate')->name('users.create');
            Route::post('/', 'createUser')->name('users.store');
            Route::get('/{user}/update', 'viewUpdate')->name('users.edit');
            Route::put('/{user}', 'updateUser')->name('users.update');
            Route::get('/export', 'export')->name('users.export');
            Route::post('/update-password', 'updatePassword')->name('users.update-password');
        });
    });
    Route::prefix('roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/create', 'viewCreate');
            Route::post('/', 'createRole');
            Route::put('/{id}', 'updateRole');
        });
    });
    Route::prefix('problem-managements')->group(function () {
        Route::controller(ProblemManageController::class)->group(function () {
            Route::get('/', 'index')->name('problem-managements.index');
            Route::get('/{id}/detail', 'detail')->name('problem-managements.detail');
            Route::get('/create', 'viewCreate')->name('problem-managements.create');
            Route::post('/', 'createProblem')->name('problem-managements.store');
            Route::get('/{problem}/update', 'viewUpdate')->name('problem-managements.edit');
            Route::put('/{problem}', 'updateProblem')->name('problem-managements.update');
            Route::put('/{problem}/verif', 'verifProblem')->name('problem-managements.verif');
            Route::get('/export', 'export')->name('problem-managements.export');
        });
    });
    Route::prefix('change-managements')->group(function () {
        Route::controller(ChangeManageController::class)->group(function () {
            Route::get('/', 'index')->name('change-managements.index');
            Route::get('/comment/{change}/all', 'getAllComment')->name('change-managements.comment.all');
            Route::put('/comment/{change}', 'createComment')->name('change-managements.comment.create');
            Route::get('/comment/{change}', 'comment')->name('change-managements.comment');
            Route::get('/{id}/detail', 'detail')->name('change-managements.detail');
            Route::get('/{changes}/update', 'viewUpdate')->name('change-managements.edit');
            Route::delete('/delete-changes/{id}', 'deleteChanges')->name('change-managements.delete');
            Route::put('/{changes}', 'updateProgress')->name('change-managements.update');
            Route::put('/{changes}', 'updateByColumn')->name('change-managements.update-by-column');
            Route::delete('/{changes}', 'deleteComment')->name('change-managements.delete-comment');
            Route::put('/{id}/verif', 'verifCR')->name('change-managements.verif');
            Route::get('/create', 'viewCreateOrEdit')->name('change-managements.create');
            Route::get('/edit', 'viewCreateOrEdit')->name('change-managements.edit');
            Route::post('/', 'createChange')->name('change-managements.store');
            Route::get('/export/{type}', 'export')->name('change-managements.export');
        });
    });
    Route::prefix('catalogs')->group(function () {
        Route::controller(CatalogController::class)->group(function () {
            Route::get('/', 'index')->name('catalogs.index');
        });
    });
    Route::prefix('user3easy')->group(function () {
        Route::controller(User3EasyController::class)->group(function () {
            Route::get('/', 'getUserAll')->name('user3easy.get-user-all');
            Route::get('/{id}', 'getById')->name('user3easy.detail');
        });
    });
    Route::prefix('locations')->group(function () {
        Route::controller(LocationController::class)->group(function () {
            Route::get('/', 'index')->name('locations.index');
            Route::get('/create', 'viewCreate')->name('locations.create');
            Route::post('/', 'createLocation')->name('locations.store');
            Route::get('/{id}/update', 'viewUpdate')->name('locations.edit');
            Route::put('/{id}', 'updateLocation')->name('locations.update');
            Route::get('/export', 'export')->name('locations.export');
            Route::delete('/{id}', 'deleteLocation')->name('locations.delete');
        });
    });
    Route::prefix('upload-manual')->group(function () {
        Route::controller(UploadController::class)->group(function () {
            Route::get('/', 'index')->name('upload.index');
            Route::post('/', 'uploadImage')->name('upload.store');
        });
    });
});


// Route::resource('/user', UserManagementController::class);
Route::get('kebijakan-keamanan', function () {
    $title = "Kebijakan & Keamanan";
    return view('pages.reference.kebijakan-keamanan', compact('title'));
})->name('kebijakan-keamanan');
Route::get('pusat-pengetahuan', function () {
    $title = "Pusat Pengetahuan";
    return view('pages.reference.pusat-pengetahuan', compact('title'));
})->name('pusat-pengetahuan');
Route::get('rating', function (\Illuminate\Http\Request $request) {
    $token = $request->input('token');
    $idTiket = \Illuminate\Support\Facades\Crypt::decrypt($token);
    $tiket = \App\Models\TroubleTicket::where('id', $idTiket)->whereNull('rate')->first();

    if (!$tiket) abort(404);
    if ($tiket->rate) abort(404);

    $title = "Rating Customer";
    return view('pages.rating.index', compact('title', 'token'));
})->name('rating');

Route::post('rating', function (\Illuminate\Http\Request $request) {
    try {
        $idTiket = \Illuminate\Support\Facades\Crypt::decrypt($request->input('token'));
        $tiket = \App\Models\TroubleTicket::where('id', $idTiket)->whereNull('rate')->first();

        if (!$tiket) throw new \Symfony\Component\HttpFoundation\Exception\BadRequestException('Tiket tidak ditemukan');

        $tiket->update([
            'rate' => $request->input('rate')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Rating',
            'data' => null
        ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    } catch (Exception $e) {
        \App\Helpers\Log::exception($e, __METHOD__);

        throw $e;
    }
})->name('rating.save');

Route::get('/{id}/preview-email', [TroubleTicketController::class, 'previewEmail'])->name('preview-email');
Route::get('/{id}/preview-pdf', [TroubleTicketController::class, 'previewPdf'])->name('preview-pdf');

