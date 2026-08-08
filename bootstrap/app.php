<?php

use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('member.login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('admin/login') || $request->is('login')) {
                return route('admin.dashboard');
            }

            return route('galeria');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Formulário aberto por muito tempo (ou sessão renovada em outra aba) gera
        // token inválido. Em vez da página 419, devolvemos o formulário preenchido.
        // O handler do framework converte TokenMismatchException em HttpException 419
        // antes dos callbacks, por isso a checagem é feita pelo status.
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sua sessão expirou. Recarregue a página e envie novamente.',
                ], 419);
            }

            return redirect()
                ->back(fallback: url('/'))
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->with('error', 'Sua sessão expirou por inatividade. Confira os dados, informe a senha novamente e reenvie.');
        });
    })->create();
