<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    View::composer('*', function ($view) {
      $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
      $verticalMenuData = json_decode($verticalMenuJson);

      // Filter menu based on user role if authenticated
      if (auth()->check()) {
        $userRole = auth()->user()->role->name ?? null;

        if ($userRole) {
          $filteredMenu = $this->filterMenuByRole($verticalMenuData->menu, $userRole);
          $verticalMenuData->menu = $filteredMenu;
        }
      }

      $view->with('menuData', [$verticalMenuData]);
    });
  }

  /**
   * Filter menu items based on user role
   */
  private function filterMenuByRole($menuItems, $userRole)
  {
    $filtered = [];

    foreach ($menuItems as $item) {
      // Check if item has role restriction
      if (isset($item->roles) && is_array($item->roles)) {
        // Super admin has access to all admin menus
        $hasAccess = in_array($userRole, $item->roles);
        if (!$hasAccess && $userRole === 'super_admin' && in_array('admin', $item->roles)) {
          $hasAccess = true;
        }
        
        // If user doesn't have access, skip this item
        if (!$hasAccess) {
          continue;
        }
      }

      // If item has submenu, filter submenu recursively
      if (isset($item->submenu)) {
        $item->submenu = $this->filterMenuByRole($item->submenu, $userRole);

        // If submenu is empty after filtering, skip parent item
        if (empty($item->submenu)) {
          continue;
        }
      }

      $filtered[] = $item;
    }

    return $filtered;
  }
}
