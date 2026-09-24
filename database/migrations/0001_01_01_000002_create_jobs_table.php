<?php

// Apertura del script PHP

// Clase base para las migraciones de Laravel
use Illuminate\Database\Migrations\Migration;
// Blueprint permite definir la estructura de las tablas
use Illuminate\Database\Schema\Blueprint;
// Fachada de esquema para crear y eliminar tablas
use Illuminate\Support\Facades\Schema;

// Define la clase anónima de la migración que Laravel ejecutará
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Método que ejecuta la migración (crea las tablas)
    public function up(): void
    {
        // Crea la tabla 'jobs' (cola de trabajos)
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental del trabajo
            $table->string('queue')->index(); // Nombre de la cola a la que pertenece (con índice)
            $table->longText('payload'); // Datos serializados del trabajo
            $table->unsignedSmallInteger('attempts'); // Número de intentos de ejecución del trabajo
            $table->unsignedInteger('reserved_at')->nullable(); // Marca de tiempo en que fue reservado (nullable)
            $table->unsignedInteger('available_at'); // Marca de tiempo en que estará disponible para ejecutarse
            $table->unsignedInteger('created_at'); // Marca de tiempo de creación del trabajo
        });

        // Crea la tabla 'job_batches' (lotes de trabajos)
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // Identificador del lote como clave primaria
            $table->string('name'); // Nombre del lote de trabajos
            $table->integer('total_jobs'); // Total de trabajos del lote
            $table->integer('pending_jobs'); // Trabajos pendientes del lote
            $table->integer('failed_jobs'); // Trabajos fallidos del lote
            $table->longText('failed_job_ids'); // IDs de los trabajos fallidos
            $table->mediumText('options')->nullable(); // Opciones adicionales del lote (nullable)
            $table->integer('cancelled_at')->nullable(); // Marca de tiempo de cancelación (nullable)
            $table->integer('created_at'); // Marca de tiempo de creación del lote
            $table->integer('finished_at')->nullable(); // Marca de tiempo de finalización (nullable)
        });

        // Crea la tabla 'failed_jobs' (trabajos fallidos)
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->string('uuid')->unique(); // Identificador único universal del trabajo fallido
            $table->string('connection'); // Conexión de la cola desde la que se ejecutaba
            $table->string('queue'); // Cola a la que pertenecía el trabajo
            $table->longText('payload'); // Datos serializados del trabajo fallido
            $table->longText('exception'); // Excepción o error ocurrido
            $table->timestamp('failed_at')->useCurrent(); // Marca de tiempo del fallo (usa la fecha actual)

            $table->index(['connection', 'queue', 'failed_at']); // Índice compuesto para consultas de fallos
        });
    }

    /**
     * Reverse the migrations.
     */
    // Método que revierte la migración (elimina las tablas)
    public function down(): void
    {
        Schema::dropIfExists('jobs'); // Elimina la tabla 'jobs'
        Schema::dropIfExists('job_batches'); // Elimina la tabla 'job_batches'
        Schema::dropIfExists('failed_jobs'); // Elimina la tabla 'failed_jobs'
    }
};
