<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Statikbe\NovaMailEditor\MailEditor;
use Statikbe\NovaTranslationManager\TranslationManager;
use Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::ignoreMigrations();
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()->withAuthenticationRoutes()->withPasswordResetRoutes()->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $this->checkRoles($user, Role::NOVA_ACCESS);
        });
    }

    public function checkRoles(User $user, array $roles)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ($user->hasRole($roles)) {
            return true;
        }

        $hasRole = false;
        $employees = $user->employees;
        foreach ($employees as $employee) {
            $hasRole = $employee->hasRole($roles);
            if ($hasRole) {
                break;
            }
        }

        return $hasRole;
    }

    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole(['statik']) || $user->can(['nova-admin']);
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            NovaPermissionTool::make()->rolePolicy(RolePolicy::class)
                                                         ->permissionPolicy(PermissionPolicy::class),
            TranslationManager::make()->canSee(function ($request) {
                if ($request->user() && $request->user()->can('nova-admin-translation')) {
                    return true;
                }

                return false;
            }),
            MailEditor::make()->canSee(function ($request) {
                if ($request->user() && $request->user()->can('nova-admin-mail-editor')) {
                    return true;
                }

                return false;
            }),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
