<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Register alias for blade components.
         */

        // Generic re-usable components.
        Blade::component('information_sheets.components.base.cardbox', 'cardbox');
        Blade::component('information_sheets.components.base.form', 'form');
        Blade::component('information_sheets.components.base.form_info', 'form_info');
        Blade::component('information_sheets.components.base.form_section', 'form_section');
        Blade::component('information_sheets.components.base.form_assessment', 'form_assessment');
        Blade::component('information_sheets.components.base.field_group', 'field_group');
        Blade::component('information_sheets.components.base.field', 'field');
        Blade::component('information_sheets.components.base.field_audit', 'field_audit');
        Blade::component('information_sheets.components.base.field_boolean', 'field_boolean');
        Blade::component('information_sheets.components.base.field_date', 'field_date');
        Blade::component('information_sheets.components.base.field_list', 'field_list');
        Blade::component('information_sheets.components.base.field_lpa_number', 'field_lpa_number');

        // Project components.
        Blade::component('information_sheets.components.project.details', 'project_details');
        Blade::component('information_sheets.components.project.business_case', 'business_case');
        Blade::component('information_sheets.components.project.planned_product_list', 'planned_product_list');
        Blade::component('information_sheets.components.project.gate_one_approval', 'gate_one_approval');
        Blade::component('information_sheets.components.project.learning_products', 'learning_products');

        /*
         * Register blade directives.
         */

        // Add logger for debugging.
        Blade::directive('logger', function ($expression) {
            return "<?php logger($expression) ?>";
        });
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
