<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsulanKegiatanController;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;

// Authentication Routes
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Main Page Route - redirect to login if not authenticated
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard-analytics');
    }
    return redirect()->route('login');
});

// Dashboard Route - protected by auth
Route::get('/dashboard', [Analytics::class, 'index'])->middleware('auth')->name('dashboard-analytics');

// Alias route dengan name 'dashboard' untuk backward compatibility
Route::get('/dashboard-old', function () {
    return redirect()->route('dashboard-analytics');
})->name('dashboard');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

// Pengaturan routes - protected by auth and admin/wadek middleware
Route::middleware(['auth', 'admin.or.wadek'])->group(function () {
    // Kelola User
    Route::resource('prodi', App\Http\Controllers\ProdiController::class);
    Route::resource('users', UsersController::class);

    // Kelola Kegiatan
    Route::resource('jenis-kegiatan', App\Http\Controllers\JenisKegiatanController::class);
    Route::resource('jenis-pendanaan', App\Http\Controllers\JenisPendanaanController::class);
});

// Usulan Kegiatan routes (tanpa auth untuk testing - DEPRECATED, will be removed)
Route::resource('usulan-kegiatan', UsulanKegiatanController::class);

// Kegiatan routes dengan middleware auth dan role
use App\Http\Controllers\KegiatanController;

Route::middleware(['auth', 'role:admin,hima,pembina_hima,kaprodi,wadek_iii'])->group(function () {
    // Riwayat Kegiatan routes
    Route::get('kegiatan/riwayat', [KegiatanController::class, 'riwayat'])->name('kegiatan.riwayat');
    Route::get('kegiatan/riwayat/{kegiatan}', [KegiatanController::class, 'showRiwayat'])->name('kegiatan.riwayat.show');

    Route::resource('kegiatan', KegiatanController::class);

    // Additional routes untuk approval workflow
    Route::post('kegiatan/{kegiatan}/submit', [KegiatanController::class, 'submit'])->name('kegiatan.submit');
    Route::post('kegiatan/{kegiatan}/approve', [KegiatanController::class, 'approve'])->name('kegiatan.approve');
    Route::post('kegiatan/{kegiatan}/revisi', [KegiatanController::class, 'revisi'])->name('kegiatan.revisi');
    Route::post('kegiatan/{kegiatan}/tolak', [KegiatanController::class, 'tolak'])->name('kegiatan.tolak');

    // Proposal routes
    Route::get('proposal', [KegiatanController::class, 'indexProposal'])->name('kegiatan.proposal.index');
    Route::get('kegiatan/{kegiatan}/proposal', [KegiatanController::class, 'showProposal'])->name('kegiatan.proposal.show');
    Route::get('kegiatan/{kegiatan}/proposal/upload', [KegiatanController::class, 'uploadProposalForm'])->name('kegiatan.proposal.upload');
    Route::post('kegiatan/{kegiatan}/proposal', [KegiatanController::class, 'storeProposal'])->name('kegiatan.proposal.store');
    Route::post('kegiatan/{kegiatan}/proposal/submit', [KegiatanController::class, 'submitProposal'])->name('kegiatan.proposal.submit');
    Route::delete('kegiatan/{kegiatan}/proposal/{file}', [KegiatanController::class, 'deleteProposal'])->name('kegiatan.proposal.delete');

    // Pendanaan routes
    Route::get('pendanaan', [KegiatanController::class, 'indexPendanaan'])->name('kegiatan.pendanaan.index');
    Route::get('kegiatan/{kegiatan}/pendanaan', [KegiatanController::class, 'showPendanaan'])->name('kegiatan.pendanaan.show');
    Route::get('kegiatan/{kegiatan}/pendanaan/upload', [KegiatanController::class, 'uploadPendanaanForm'])->name('kegiatan.pendanaan.upload');
    Route::post('kegiatan/{kegiatan}/pendanaan', [KegiatanController::class, 'storePendanaan'])->name('kegiatan.pendanaan.store');
    Route::post('kegiatan/{kegiatan}/pendanaan/submit', [KegiatanController::class, 'submitPendanaan'])->name('kegiatan.pendanaan.submit');
    Route::delete('kegiatan/{kegiatan}/pendanaan/{file}', [KegiatanController::class, 'deletePendanaan'])->name('kegiatan.pendanaan.delete');

    // Laporan routes
    Route::get('laporan', [KegiatanController::class, 'indexLaporan'])->name('kegiatan.laporan.index');
    Route::get('kegiatan/{kegiatan}/laporan', [KegiatanController::class, 'showLaporan'])->name('kegiatan.laporan.show');
    Route::get('kegiatan/{kegiatan}/laporan/upload', [KegiatanController::class, 'uploadLaporanForm'])->name('kegiatan.laporan.upload');
    Route::post('kegiatan/{kegiatan}/laporan', [KegiatanController::class, 'storeLaporan'])->name('kegiatan.laporan.store');
    Route::post('kegiatan/{kegiatan}/laporan/submit', [KegiatanController::class, 'submitLaporan'])->name('kegiatan.laporan.submit');
    Route::delete('kegiatan/{kegiatan}/laporan/{file}', [KegiatanController::class, 'deleteLaporan'])->name('kegiatan.laporan.delete');
});

// Test Toast route (hapus setelah testing selesai)
Route::get('/test-toast', function (Illuminate\Http\Request $request) {
  if ($request->has('toast')) {
    $type = $request->get('toast');
    $messages = [
      'success' => 'Ini adalah pesan sukses dari session!',
      'error' => 'Ini adalah pesan error dari session!',
      'warning' => 'Ini adalah pesan warning dari session!',
      'info' => 'Ini adalah pesan info dari session!'
    ];

    if (isset($messages[$type])) {
      return redirect('/test-toast')->with($type, $messages[$type]);
    }
  }

  return view('test-toast');
});
