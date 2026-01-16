<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de configuración de cotizaciones (debe crearse primero)
        Schema::create('quote_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_ruc', 30)->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->unsignedBigInteger('company_logo_id')->nullable();
            $table->unsignedBigInteger('background_image_id')->nullable();
            $table->unsignedBigInteger('last_page_image_id')->nullable();
            $table->text('default_terms_conditions')->nullable();
            $table->text('default_notes')->nullable();
            $table->decimal('default_tax_rate', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('company_logo_id')->references('id')->on('media_assets')->nullOnDelete();
            $table->foreign('background_image_id')->references('id')->on('media_assets')->nullOnDelete();
            $table->foreign('last_page_image_id')->references('id')->on('media_assets')->nullOnDelete();
        });

        // Tabla principal de cotizaciones
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Cliente/usuario al que se le hace la cotización');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Usuario que creó la cotización');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            
            // Campos para cliente sin cuenta en el sistema
            $table->string('client_name')->nullable();
            $table->string('client_ruc', 30)->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            
            // Imágenes personalizadas para esta cotización (sobrescriben las de settings)
            $table->unsignedBigInteger('custom_background_image_id')->nullable();
            $table->unsignedBigInteger('custom_last_page_image_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
            $table->foreign('custom_background_image_id')->references('id')->on('media_assets')->nullOnDelete();
            $table->foreign('custom_last_page_image_id')->references('id')->on('media_assets')->nullOnDelete();
        });

        // Tabla de items/productos de la cotización
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit')->nullable()->comment('Unidad de medida: pza, kg, hr, etc.');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('quote_settings');
    }
};
